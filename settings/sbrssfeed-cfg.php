<?php
global $wpsf_settings;

$wpsf_settings[] = array(
    'section_id' => 'tags',
    'section_title' => __( 'Add Image RSS Feed tags', 'SB_RSS_feed_plus' ),
    'section_description' => __( '"enclosure" and "media:content" tag in RSS feed are used to tell RSS parser about post thumbnail.', 'SB_RSS_feed_plus' ),
    'section_order' => 10,
    'fields' => array(
		array(
            'id' => 'addTag_enclosure',
            'title' => __( 'Add "enclosure" tag to RSS feed', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
		array(
            'id' => 'addTag_mediaContent',
            'title' => __( 'Add "media:content" tag to RSS feed', 'SB_RSS_feed_plus' ),
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
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