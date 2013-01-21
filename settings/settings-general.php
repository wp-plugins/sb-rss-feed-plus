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
?>