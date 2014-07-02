<?php
/*
Plugin Name: SB-RSS_feed-plus
Plugin URI: http://git.ladasoukup.cz/sb-rss-feed-plus
Description: This plugin will add post thumbnail to RSS feed items. Add signatur or simple ads. Create fulltext RSS (via special url).
Version: 1.4.5
Author: Ladislav Soukup (ladislav.soukup@gmail.com)
Author URI: http://www.ladasoukup.cz/
Author Email: ladislav.soukup@gmail.com
License:

  Copyright 2013-2014 Ladislav Soukup (ladislav.soukup@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class SB_RSS_feed_plus {
	private $plugin_path;
    private $wpsf;
	private $CFG;
	public $cfg_version = '1.1.1';
	private $update_warning = false;
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		$this->plugin_path = plugin_dir_path( __FILE__ );
		
		// Load plugin text domain
		load_plugin_textdomain( 'SB_RSS_feed_plus', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		
		/* wait for theme to load, then continue... */
		add_action( 'after_setup_theme',  array( $this, '__construct_after_theme' ) );
	} // end constructor
	
	public function __construct_after_theme() {
		/* admin options */
		require_once( $this->plugin_path .'wp-settings-framework.php' );
        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'settings/sbrssfeed-cfg.php' );
		
		/* load CFG */
		$this->CFG = wpsf_get_settings( $this->plugin_path .'settings/sbrssfeed-cfg.php' );  // print_r($this->CFG);
		
		if ( $this->CFG['sbrssfeedcfg_info_version'] !== $this->cfg_version ) {
			// SET defaults, mark this version as current
			if (!isset($this->CFG['sbrssfeedcfg_tags_addTag_enclosure'])) $this->CFG['sbrssfeedcfg_tags_addTag_enclosure'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_tags_addTag_mediaContent'])) $this->CFG['sbrssfeedcfg_tags_addTag_mediaContent'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_tags_addTag_mediaThumbnail'])) $this->CFG['sbrssfeedcfg_tags_addTag_mediaThumbnail'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_description_extend_description'])) $this->CFG['sbrssfeedcfg_description_extend_description'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_description_extend_content'])) $this->CFG['sbrssfeedcfg_description_extend_content'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_signature_addSignature'])) $this->CFG['sbrssfeedcfg_signature_addSignature'] = 0;
			if (!isset($this->CFG['sbrssfeedcfg_fulltext_fulltext_override'])) $this->CFG['sbrssfeedcfg_fulltext_fulltext_override'] = 0;
			if (!isset($this->CFG['sbrssfeedcfg_fulltext_fulltext_add2description'])) $this->CFG['sbrssfeedcfg_fulltext_fulltext_add2description'] = 0;
			
			$this->update_warning = true;
			add_action( 'admin_notices', array( $this, "addAdminAlert" ) );
		}
		add_action( 'wpsf_before_settings_fields', array( $this, 'update_current_version' ) );
		add_action( 'wpsf_after_settings', array( $this, 'plugin_donation' ) );
		
		// add admin menu item
		add_action( 'admin_menu', array(&$this, 'admin_menu') );
		
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
		
		// add_action( "rss2_ns", array( $this, "feed_addNameSpace") );
		add_action( "rss_item", array( $this, "feed_addMeta" ), 5, 1 );
		add_action( "rss2_item", array( $this, "feed_addMeta" ), 5, 1 );
		
		if ( $this->CFG['sbrssfeedcfg_description_extend_description'] == 1 )
			add_filter('the_excerpt_rss', array( $this, "feed_update_content") );
		
		if ( $this->CFG['sbrssfeedcfg_description_extend_content'] == 1 )
			add_filter('the_content_feed', array ( $this, "feed_update_content") );
		
		if ( $this->CFG['sbrssfeedcfg_inrssAd_inrssAd_enabled'] == 1 )
			add_filter('the_content_feed', array ( $this, "feed_update_content_injectAd") );
		
		if ( $this->CFG['sbrssfeedcfg_fulltext_fulltext_override'] == 1 )
			$this->fulltext_override();
	}
	
	public function addAdminAlert() {
		if ( current_user_can( 'install_plugins' ) ) { ?>
		<div class="updated">
			<p>
				<?php _e( '<b>SB RSS Feed plus Warning</b>: Settings needs to be updated...', 'SB_RSS_feed_plus' ); ?>
				&nbsp;&nbsp;
				<a href="options-general.php?page=sbrss_feed_plus" class="button"><?php _e( 'Update settings', 'SB_RSS_feed_plus' ); ?></a>
			</p>
		</div>
		<?php }
	}
	
	public function update_current_version() {
		echo '<input type="hidden" name="sbrssfeedcfg_settings[sbrssfeedcfg_info_version]" id="sbrssfeedcfg_info_version" value="'.$this->cfg_version.'" />';
	}
	
	public function plugin_donation() {
		echo '<div style="background: #ebebeb; border: 1px solid #cacaca; padding: 10px;">';
			echo '<div style="">' . __( 'If You like this plugin, please donate and support development. Thank You :)', 'SB_RSS_feed_plus' ) . '</div>';
			echo '<br/><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>';
			echo '<br/>';
			echo '<div style=""><a href="https://bitbucket.org/ladasoukup/sb-rss-feed-plus/issues" target="_blank">' . __( 'Support and issues tracking.', 'SB_RSS_feed_plus' ) . '</a></div>';
			echo '<br/><br/>';
			echo '<a href="http://profiles.wordpress.org/ladislavsoukupgmailcom" targe="_blank">' . __( 'More free plugins...', 'SB_RSS_feed_plus' ) . '</a>';
			echo '&nbsp;|&nbsp;<a href="http://git.ladasoukup.cz/" targe="_blank">' . __( 'More projects - GIT', 'SB_RSS_feed_plus' ) . '</a>';
		echo '</div>';
	}
	
	public function admin_menu()
    {
		if ( $this->update_warning === true ) {
			$menu_label = __( 'SB RSS feed +', 'SB_RSS_feed_plus' ) . "<span class='update-plugins count-1' title=''><span class='update-count'>!</span></span>";
		} else {
			$menu_label = __( 'SB RSS feed +', 'SB_RSS_feed_plus' );
		}
		add_submenu_page( 'options-general.php', __( 'SB RSS feed plus', 'SB_RSS_feed_plus' ), $menu_label, 'update_core', 'sbrss_feed_plus', array(&$this, 'settings_page') );
    }
	
	public function settings_page() { ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
			
			<!--
			<a href="?page=sbrss_feed_plus&amp;settings-updated=true" class="button" style="float: right; margin: 10px 5px 15px 0;"><?php _e( 'Clear RSS cache', 'SB_RSS_feed_plus' ); ?></a>
			-->
			<?php if ( $_GET['settings-updated'] == 'true' ) {
				$this->clear_WP_feed_cache();
			} ?>
			
			<h2><?php _e( 'SB RSS Feed plus - Settings', 'SB_RSS_feed_plus' ); ?></h2>
            <?php 
            // Output your settings form
            $this->wpsf->settings(); 
            ?>
			
			<hr/>
			<h2><?php _e( 'Debug - hooks & filters', 'SB_RSS_feed_plus' ); ?></h2>
			<?php $this->list_hooks( 'rss_item' ); ?>
			<?php $this->list_hooks( 'rss2_item' ); ?>
			<?php $this->list_hooks( 'pre_option_rss_use_excerpt' ); ?>
			<?php $this->list_hooks( 'the_excerpt_rss' ); ?>
			<?php $this->list_hooks( 'the_content_feed' ); ?>
			
			<?php $this->list_hooks( 'wp_title_rss' ); ?>
			<?php $this->list_hooks( 'get_wp_title_rss' ); ?>
			
			<?php $this->list_hooks( 'the_permalink_rss' ); ?>
			<?php $this->list_hooks( 'rss_enclosure' ); ?>
			<?php $this->list_hooks( 'atom_enclosure' ); ?>
			<?php $this->list_hooks( 'self_link' ); ?>
			<?php $this->list_hooks( 'feed_content_type' ); ?>
			<hr/>
        </div>
        
	<?php }
    
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
		$this->clear_WP_feed_cache();
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		$this->clear_WP_feed_cache();
	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {
		delete_option( 'sbrssfeedcfg_settings' );
		$this->clear_WP_feed_cache();
	} // end uninstall
	
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	public function clear_WP_feed_cache() {
		global $wpdb;
		//$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "options` WHERE `option_name` LIKE ('_transient%_feed_%')" );
		
		?>
		<div class="updated">
			<p>
				<?php _e( 'RSS feed will be updated after publishing new post...', 'SB_RSS_feed_plus' ); ?>
			</p>
		</div>
		<?php
	}
	
	public function feed_getImage( $size ) {
		global $post;
		$image = false;
		if ( empty( $size ) ) $size = 'full';
		
		if( function_exists ('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			if(!empty($thumbnail_id)) {
				$image = wp_get_attachment_image_src( $thumbnail_id, $size );
				$image[4] = 0;
				if ( $size == 'full' ) {
					$image[4] = @filesize( get_attached_file( $thumbnail_id ) ); // add file size
				}
			}
		}
		
		return ($image);
	}
	
	public function feed_addNameSpace() {
		echo 'xmlns:media="http://search.yahoo.com/mrss/"';
	}
	
	public function feed_addMeta($for_comments) {
		global $post;
		
		if(!$for_comments) {
			$image_enclosure = $this->feed_getImage( 'full' );
			$image_mediaContent = $this->feed_getImage( $this->CFG['sbrssfeedcfg_tags_addTag_mediaContent_size'] );
			$image_mediaThumbnail = $this->feed_getImage( $this->CFG['sbrssfeedcfg_tags_addTag_mediaThumbnail_size'] );
			if ($image_enclosure !== false) {
				
				if ( $this->CFG['sbrssfeedcfg_tags_addTag_enclosure'] == 1 ) {
					echo '<enclosure url="' . $image_enclosure[0] . '" length="' . $image_enclosure[4] . '" type="image/jpg" />' . "\n";
				}
				
				if ( $this->CFG['sbrssfeedcfg_tags_addTag_mediaContent'] == 1 ) {
					echo '<media:content xmlns:media="http://search.yahoo.com/mrss/" url="' . $image_mediaContent[0] . '" width="' . $image_mediaContent[1] . '" height="' . $image_mediaContent[2] . '" medium="image" type="image/jpeg">' . "\n";
					echo '	<media:copyright>' . get_bloginfo( 'name' ) . '</media:copyright>' . "\n";
					echo '</media:content>' . "\n";
				}
				
				if ( $this->CFG['sbrssfeedcfg_tags_addTag_mediaThumbnail'] == 1 ) {
					echo '<media:thumbnail xmlns:media="http://search.yahoo.com/mrss/" url="'. $image_mediaThumbnail[0] . '" width="' . $image_mediaThumbnail[1] . '" height="' . $image_mediaThumbnail[2] . '" />' . "\n";
				}
				
			}
		}
	}
	
	public function feed_update_content( $content ) {
		global $post;
		$content_new = '';
		
		if(has_post_thumbnail($post->ID)) {
			$image = $this->feed_getImage( $this->CFG['sbrssfeedcfg_description_extend_content_size'] );
			$content_new .= '<div style="margin: 5px 5% 10px 5%;"><img src="' . $image[0] . '" width="90%" /></div>';
		}
		
		if ( ( $this->CFG['sbrssfeedcfg_fulltext_fulltext_add2description'] == 1 ) && ( $this->check_fsk() ) ) {
			$content_new_full = apply_filters( 'the_content', get_the_content() );
			$content_new_full = str_replace(']]>', ']]&gt;', $content_new_full);
			$content_new .= '<div>' . $content_new_full . '</div>';
		} else {
			$content_new .= '<div>' . $content . '</div>';
		}
		
		if ( $this->CFG['sbrssfeedcfg_signature_addSignature'] == 1 ) {
			$content_new .= '<div>&nbsp;</div><div><em>';
			$content_new .=  __( 'Source: ', 'SB_RSS_feed_plus' );
			$content_new .= '<a href="' . get_permalink($post->ID) . '" target="_blank">' . get_bloginfo( 'name' ) . '</a>';
			$content_new .= '</em></div>';
		}
		
		//$content_new = str_replace( ']]>', ']]&gt;', $content_new );
		return $content_new;
	}
	
	public function feed_update_content_injectAd( $content ) {
		global $post;
		$content_ad = '';
		$content_new = '';
		
		$split_after = $this->CFG['sbrssfeedcfg_inrssAd_inrssAd_injectAfter'];
		if ( ($split_after < 1) || ($split_after > 8) ) $split_after = 2;
		
		$content_ad .= '<br/><div style="margin: 10px 5%; text-align: center;">';
		$content_ad .= '<em style="display: block; text-align: right;">' . __( 'advertisement: ', 'SB_RSS_feed_plus' ) . '</em><br/>';
		$content_ad .= '<a href="' . $this->CFG['sbrssfeedcfg_inrssAd_inrssAd_link'] . '" target="_blank" style="text-decoration: none;">';
		$content_ad .= '<img src="' . $this->CFG['sbrssfeedcfg_inrssAd_inrssAd_img'] . '" width="90%" style="width: 90%; max-width: 700px;" />';
		$content_ad .= '<br/><em style="display: block; text-align: center;">' . $this->CFG['sbrssfeedcfg_inrssAd_inrssAd_title'] . '</em>';
		$content_ad .= '</a>';
		$content_ad .= '</div><br/>';
		
		$tmp = $content;
		$tmp = str_replace('</p>', '', $tmp); // drop all </p> - we don't need them ;)
		$array = explode('<p>', $tmp); // split by <p> tag
		$tmp = '';
		$max = sizeof( $array );
		
		if ($max > ( $split_after + 1 )) {
			// add after nth <p>
			for ($loop=0; $loop<( $split_after + 1 ); $loop++) {
				$content_new .= '<p>' . $array[$loop] . '</p>';
			}
			$content_new .= $content_ad;
			for ($loop=( $split_after + 1 ); $loop<( $max + 1 ); $loop++) {
				$content_new .= '<p>' . $array[$loop] . '</p>';
			}
		} else {
			// add to end of post...
			$content_new = $content;
			$content_new .= $content_ad;
		}
		
		return $content_new;
	}
	
	public function check_fsk() {
		$result = false;
		$secret = $this->CFG['sbrssfeedcfg_fulltext_fulltext_override_secrete'];
		$passed_secret = $_GET['fsk'];
		if ( $secret == $passed_secret ) $result = true;
		
		return( $result );
	}
	
	public function fulltext_override() {
		if ( $this->check_fsk() ) {
			add_filter('pre_option_rss_use_excerpt', array( $this, 'fulltext_override_filter' ) );
		}
	}
	public function fulltext_override_filter() {
		return 0;
	}
	
	/*--------------------------------------------*
	 * DEBUG - List Hooks
	 * Code by Andrey Savchenko, http://www.rarst.net/script/debug-wordpress-hooks/
	*---------------------------------------------*/
	function list_hooks( $filter = false ){
		global $wp_filter;
		
		$hooks = $wp_filter;
		ksort( $hooks );

		foreach( $hooks as $tag => $hook )
			if ( false === $filter || false !== strpos( $tag, $filter ) )
				$this->dump_hook($tag, $hook);
	}

	function list_live_hooks( $hook = false ) {
		if ( false === $hook )
			$hook = 'all';

		add_action( $hook, array( $this, 'list_hook_details' ), -1 );
	}

	function list_hook_details( $input = NULL ) {
		global $wp_filter;
		
		$tag = current_filter();
		if( isset( $wp_filter[$tag] ) )
			$this->dump_hook( $tag, $wp_filter[$tag] );

		return $input;
	}

	function dump_hook( $tag, $hook ) {
		ksort($hook);

		echo "<pre>&gt;&gt;&gt;&gt;&gt;\t<strong>$tag</strong><br />";
		
		foreach( $hook as $priority => $functions ) {

		echo $priority;

		foreach( $functions as $function )
			if( $function['function'] != 'list_hook_details' ) {
			
			echo "\t";

			if( is_string( $function['function'] ) )
				echo $function['function'];

			elseif( is_string( $function['function'][0] ) )
				 echo $function['function'][0] . ' -> ' . $function['function'][1];

			elseif( is_object( $function['function'][0] ) )
				echo "(object) " . get_class( $function['function'][0] ) . ' -> ' . $function['function'][1];

			else
				print_r($function);

			echo ' (' . $function['accepted_args'] . ') <br />';
			}
		}

		echo '</pre>';
	}
	
} // end class

$SB_RSS_feed_plus = new SB_RSS_feed_plus();