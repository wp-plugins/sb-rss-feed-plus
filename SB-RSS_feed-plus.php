<?php
/*
Plugin Name: SB-RSS_feed-plus
Plugin URI: http://git.ladasoukup.cz/sb-rss-feed-plus
Description: This plugin will add post thumbnail to RSS feed items.
Version: 1.1.1
Author: Ladislav Soukup (ladislav.soukup@gmail.com)
Author URI: http://www.ladasoukup.cz/
Author Email: ladislav.soukup@gmail.com
License:

  Copyright 2013 Ladislav Soukup (ladislav.soukup@gmail.com)

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
		
		/* admin options */
		require_once( $this->plugin_path .'wp-settings-framework.php' );
        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'settings/sbrssfeed-cfg.php' );
		
		/* load CFG */
		$this->CFG = wpsf_get_settings( $this->plugin_path .'settings/sbrssfeed-cfg.php' );  // print_r($this->CFG);
		
		if ( $this->CFG['sbrssfeedcfg_info_version'] !== $this->cfg_version ) {
			// SET defaults, mark this version as current
			if (!isset($this->CFG['sbrssfeedcfg_tags_addTag_enclosure'])) $this->CFG['sbrssfeedcfg_tags_addTag_enclosure'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_tags_addTag_mediaContent'])) $this->CFG['sbrssfeedcfg_tags_addTag_mediaContent'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_description_extend_description'])) $this->CFG['sbrssfeedcfg_description_extend_description'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_description_extend_content'])) $this->CFG['sbrssfeedcfg_description_extend_content'] = 1;
			if (!isset($this->CFG['sbrssfeedcfg_signature_addSignature'])) $this->CFG['sbrssfeedcfg_signature_addSignature'] = 0;
			
			$this->update_warning = true;
			add_action( 'admin_head', array( $this, "addAdminAlert" ) );
		}
		add_action( 'wpsf_before_settings_fields', array( $this, 'update_current_version' ) );
		
		// add admin menu item
		add_action( 'admin_menu', array(&$this, 'admin_menu') );
		
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
		
		add_action( "rss2_ns", array( $this, "feed_addNameSpace") );
		add_action( "rss_item", array( $this, "feed_addMeta" ), 5, 1 );
		add_action( "rss2_item", array( $this, "feed_addMeta" ), 5, 1 );
		
		
		if ( $this->CFG['sbrssfeedcfg_description_extend_description'] == 1 )
			add_filter('the_excerpt_rss', array( $this, "feed_update_content") );
		
		if ( $this->CFG['sbrssfeedcfg_description_extend_content'] == 1 )
			add_filter('the_content_feed', array ( $this, "feed_update_content") );
		
	} // end constructor
	
	public function addAdminAlert() { ?>
		<script type="text/javascript">
			jQuery().ready(function(){
				jQuery('.wrap > h2').parent().prev().after('<div class="update-nag"><?php _e( '<b>SB RSS Feed plus Warning</b>: Settings needs to be updated...', 'SB_RSS_feed_plus' ); ?>&nbsp;&nbsp;<a href="options-general.php?page=sbrss_feed_plus" class="button"><?php _e( 'Update settings', 'SB_RSS_feed_plus' ); ?></a></div>');
			});
		</script>
	<?php }
	
	public function update_current_version() {
		echo '<input type="hidden" name="sbrssfeedcfg_settings[sbrssfeedcfg_info_version]" id="sbrssfeedcfg_info_version" value="'.$this->cfg_version.'" />';
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
            <h2><?php _e( 'SB RSS Feed plus - Settings', 'SB_RSS_feed_plus' ); ?></h2>
            <?php 
            // Output your settings form
            $this->wpsf->settings(); 
            ?>
        </div>
        
	<?php }
    
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
		
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		
	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {
		
	} // end uninstall
	
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	public function feed_getImage() {
		global $post;
		$image = false;
		$size = null;
		
		if( function_exists ('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			if(!empty($thumbnail_id)) {
				$image = wp_get_attachment_image_src( $thumbnail_id, $size );
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
			$image = $this->feed_getImage();
			if ($image !== false) {
				
				if ( $this->CFG['sbrssfeedcfg_tags_addTag_enclosure'] == 1 ) {
					echo '<enclosure url="' . $image[0] . '" length="' . $filesize . '" type="image/jpg" />' . "\n";
				}
				
				if ( $this->CFG['sbrssfeedcfg_tags_addTag_mediaContent'] == 1 ) {
					echo '<media:content url="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" medium="image" type="image/jpeg">' . "\n";
					echo '<media:copyright>' . get_bloginfo( 'name' ) . '</media:copyright>' . "\n";
					echo '</media:content>' . "\n";
				}
				
			}
		}
	}
	
	public function feed_update_content($content) {
		global $post;
		
		$content_new = '';
		$image = $this->feed_getImage();
		
		if(has_post_thumbnail($post->ID)) {
			$content_new .= '<div style="margin: 5px 5% 10px 5%;"><img src="' . $image[0] . '" width="90%" /></div>';
			$content_new .= '<div>' . $content . '</div>';
			
			if ( $this->CFG['sbrssfeedcfg_signature_addSignature'] == 1 ) {
				$content_new .= '<div>&nbsp;</div><div><em>';
				$content_new .=  __( 'Source: ', 'SB_RSS_feed_plus' );
				$content_new .= '<a href="' . get_permalink($post->ID) . '" target="_blank">' . get_bloginfo( 'name' ) . '</a>';
				$content_new .= '</em></div>';
			}
		}
		return $content_new;
	}
	
} // end class

$SB_RSS_feed_plus = new SB_RSS_feed_plus();