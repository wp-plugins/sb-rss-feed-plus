<?php
global $wpsf_settings;
$CFG = wpsf_get_settings( $this->plugin_path .'settings/sbrssfeed-cfg.php' );

$thumbs = array(
	'full' => __( '= Full size =', 'SB_RSS_feed_plus' ),
	'thumbnail' => __( 'Thumbnail', 'SB_RSS_feed_plus' ),
	'medium' => __( 'Medium size', 'SB_RSS_feed_plus' ),
	'large ' => __( 'Large size', 'SB_RSS_feed_plus' )
);
//$thumbs_raw = get_intermediate_image_sizes();
//foreach( $thumbs_raw as $th ) { $thumbs[$th] = __( $th, 'SB_RSS_feed_plus' ); }

$wpsf_settings[] = array(
    'section_id' => 'tags',
    'section_title' => __( 'Add Image RSS Feed tags', 'SB_RSS_feed_plus' ),
    'section_description' => __( '"enclosure" and "media:content" / "media:thumbnail" tags in RSS feed are used to tell RSS parser about post thumbnail.', 'SB_RSS_feed_plus' ),
    'section_order' => 10,
    'fields' => array(
		array(
            'id' => 'addTag_enclosure',
            'title' => __( 'Add "enclosure" tag', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
		array(
            'id' => 'addTag_mediaContent',
            'title' => __( 'Add "media:content" tag', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
		array(
            'id' => 'addTag_mediaContent_size',
            'title' => __( ' - image size', 'SB_RSS_feed_plus' ),
            'type' => 'select',
			'type' => 'select',
			'choices' => $thumbs
        ),
		array(
            'id' => 'addTag_mediaThumbnail',
            'title' => __( 'Add "media:thumbnail" tag', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
		array(
            'id' => 'addTag_mediaThumbnail_size',
            'title' => __( ' - image size', 'SB_RSS_feed_plus' ),
            'type' => 'select',
			'type' => 'select',
			'choices' => $thumbs
        )
	)
);

$wpsf_settings[] = array(
    'section_id' => 'description',
    'section_title' => __( 'Extend HTML content', 'SB_RSS_feed_plus' ),
    'section_description' => __( 'This will extend the HTML code of "description" and "content:encoded" tags with 90% wide image before the text.', 'SB_RSS_feed_plus' ),
    'section_order' => 20,
    'fields' => array(
		array(
            'id' => 'extend_description',
            'title' => __( 'Extend "description" (excerpt)', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
		array(
            'id' => 'extend_content',
            'title' => __( 'Extend "content:encoded" HTML', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
		array(
            'id' => 'extend_content_size',
            'title' => __( ' - image size', 'SB_RSS_feed_plus' ),
            'type' => 'select',
			'type' => 'select',
			'choices' => $thumbs
        )
	)
);

$rss_use_excerpt = get_option('rss_use_excerpt');
$rss_fulltext_link = site_url() . '/feed/?fsk=';
$CFG['sbrssfeedcfg_fulltext_fulltext_override_secrete'] ? $rss_fulltext_link .= $CFG['sbrssfeedcfg_fulltext_fulltext_override_secrete'] : $rss_fulltext_link .= '-NOT-SET-';

$wpsf_settings[] = array(
    'section_id' => 'fulltext',
    'section_title' => __( 'RSS Feed fulltext override', 'SB_RSS_feed_plus' ),
    'section_description' => __( 'Override "excerpt only" RSS feed when requested with "secret" key.', 'SB_RSS_feed_plus' ),
    'section_order' => 25,
    'fields' => array(
		array(
            'id' => 'fulltext_wp_option',
            'title' => __( 'WordPress RSS Feed mode', 'SB_RSS_feed_plus' ),
            'desc' => '',
			'type' => 'custom',
			'std' => $rss_use_excerpt ? __( 'Excerpt only - there is only excerpt in the standard RSS Feed...<br />However, requesting feed url with special "secret key" will display full content of each post (great for services like Google Currents).', 'SB_RSS_feed_plus' ) : __( 'Fulltext - your feed already contains whole post content.', 'SB_RSS_feed_plus' )
        ),
		array(
            'id' => 'fulltext_override',
            'title' => __( 'Enable fulltext override', 'SB_RSS_feed_plus' ),
            'desc' => $rss_use_excerpt ? '<em>' . __( 'When enabled, you can request RSS Feed with full post content with special URL (added query string <strong>?fsk=</strong>)', 'SB_RSS_feed_plus' ) . '</em>' : '<em>' . __( 'You don\'t need to override WordPress settings - your feed already contains full post content.', SB_RSS_feed_plus ) . '</em>' ,
            'type' => 'checkbox',
            'std' => 0
        ),
		array(
            'id' => 'fulltext_override_secrete',
            'title' => __( 'Override "secret" key (?fsk= param)', 'SB_RSS_feed_plus' ),
            'desc' => __( 'Fulltext RSS Feed:', 'SB_RSS_feed_plus' ) . ' <a href="'.$rss_fulltext_link.'" target="_blank">' . $rss_fulltext_link . '</a>',
            'type' => 'text',
			'std' => uniqid()
        )
	)
);

$wpsf_settings[] = array(
    'section_id' => 'signature',
    'section_title' => __( 'RSS Feed signature', 'SB_RSS_feed_plus' ),
    'section_description' => __( 'Add "Source: XYZ" text to end of the content of each feed item.', 'SB_RSS_feed_plus' ),
    'section_order' => 30,
    'fields' => array(
		array(
            'id' => 'addSignature',
            'title' => __( 'Add signature', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 0
        )
	)
);

$wpsf_settings[] = array(
    'section_id' => 'inrssAd',
    'section_title' => __( 'RSS Feed advertisement', 'SB_RSS_feed_plus' ),
	'section_description' => __( 'Inject "ad" to RSS feed items (image with link). Ad will be inserted only to full text "content:encoded" tag.', 'SB_RSS_feed_plus' ),
    'section_order' => 40,
    'fields' => array(
		array(
            'id' => 'inrssAd_enabled',
            'title' => __( 'Inject ad to feed posts', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 0
        ),
		array(
			'id' => 'inrssAd_img',
			'title' => __( 'Ad image', 'SB_RSS_feed_plus' ),
			'desc' => __( 'image will be stretched up to 700px of width', 'SB_RSS_feed_plus' ),
			'type' => 'file',
			'std' => ''
        ),
		array(
            'id' => 'inrssAd_title',
            'title' => __( 'Ad title', 'SB_RSS_feed_plus' ),
            // 'desc' => __( 'Will be inserted as &lt;figure&gt; tag and alt attribute of img tag', 'SB_RSS_feed_plus' ),
            'type' => 'text'
        ),
		array(
            'id' => 'inrssAd_link',
            'title' => __( 'Ad target link', 'SB_RSS_feed_plus' ),
            'desc' => __( 'Recommendation: use bit.ly or similar service to track clicks...', 'SB_RSS_feed_plus' ),
            'type' => 'text'
        ),
		array(
            'id' => 'inrssAd_injectAfter',
            'title' => __( 'Inject ad after nth paragraph', 'SB_RSS_feed_plus' ),
            'type' => 'select',
			'type' => 'select',
            'std' => '2',
			'choices' => array(
				'1' => __( '1st paragraph', 'SB_RSS_feed_plus' ),
				'2' => __( '2nd paragraph', 'SB_RSS_feed_plus' ),
				'3' => __( '3rd paragraph', 'SB_RSS_feed_plus' ),
				'4' => __( '4th paragraph', 'SB_RSS_feed_plus' ),
				'5' => __( '5th paragraph', 'SB_RSS_feed_plus' ),
				'6' => __( '6th paragraph', 'SB_RSS_feed_plus' ),
				'7' => __( '7th paragraph', 'SB_RSS_feed_plus' ),
			)
        )
	)
);


?>