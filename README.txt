=== SB RSS feed plus ===
Contributors: ladislav.soukup@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P6CKTGSXPFWKG&lc=CZ&item_name=Ladislav%20Soukup&item_number=SB%20RSS%20feed%20plus%20%5bWP%2dPlugin%5d&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: rss, feed, image, post thumbnail, add, enhance, enhanced, plus, better, flipboard, google currents, content:encoded, media:content, media, content, ad, ads, advertisement, override
Requires at least: 3.5.0
Tested up to: 4.1
Stable tag: 1.4.8

This plugin will add post thumbnail to RSS feed items. Add signatur or simple ads. Create fulltext RSS (via special url).

== Description ==

You can improve the default WordPress RSS feed to include:

- This plugin will add post thumbnail to RSS feed as "media:content" and "enclosure" tags.
- Image is also added to HTML part of "description" and "content:encoded" tags.
- You can also add server signature to end of feed content in form "Source: XYZ".
- Inject advertisement (image with link) after nth paragraph of each post.

= Post thumbnail =
Add post thumnail to each post's excerpt and full text (if enabled in WordPress configuration).
Image will be added just before text of each post in RSS feed.
Post thumbnail is also added as media:content and enclosure tag to RSS feed.

= Full-text RSS Feed when accessed via special URL =
You can access RSS Feed with full content posts even when WordPress is set to RSS "Excerpt only" mode. Just setup a "secret key" and enable the override...
You can then request feed url with special query param ?fsk= to override "excerpt only" mode to "fulltext" mode. This is great for services like Google Currents.

= Server signature =
You can add server signature just after the full text content of post to each RSS post.

= ADs =
Very simple implementation of advertisement to each RSS feed post item. Ad is a simple clickable image (stretched to 90% of width - maximum of 700px).
There is no click monitoring, so you should use something like bit.ly to track clicks.

= Support =
Please, if you need support or help, visit: https://bitbucket.org/ladasoukup/sb-rss-feed-plus/issues

= Translatable =
All text can be translated using standart language files, text domain is: "SB_RSS_feed_plus".

Included translations:

- English (default)
- Czech

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Feed is now updated, you should check it

No more settings are needed.

== Screenshots ==

1. Plugin settings

== Changelog ==
= 1.4.8 =
- added title, alt and data-description to <img> tag
- added media:copyright, media:title and media:description to media:content tag

= 1.4.7 =
- fixed one last PHP NOTICE

= 1.4.6 =
- fixed several PHP NOTICE problems

= 1.4.5 =
- added support for custom thumbnail sizes (issue #10)
- changed __constructor - plugin is now initialized AFTER theme

= 1.4.4 =
- added option to insert full article to <description> tag

= 1.4.3 =
- fix of bug introduced in version 1.4

= 1.4.2 =
- added another RSS-related hooks and filters debug info

= 1.4.1 =
- added RSS-related hooks and filters debug section ( code by: Andrey Savchenko, http://www.rarst.net/script/debug-wordpress-hooks/ )
- WordPress 3.8 tested

= 1.4.0 =
- image size can now be set in config panel

= 1.3.5 =
- Added media:thumbnail element to metadata (thanks to David Raison)

= 1.3.4 =
- bugfix: no description is shown for posts without thumbnail image. (thanks to Rodger Zeisler for reporting and sending fix)

= 1.3.3 =
- fixed problem with older PHP version (5.2)

= 1.3.2 =
- fixed possible "xmlns:media" collision
- fixed "enclosure" tag - length value was missing

= 1.3.1 =
- WordPress excerpt_only setting can be "temporary" overridden via special url (query param)

= 1.2 =

- added advertisement injection
- few core updates
- bug fixes

= 1.1.2 =

- admin notification code updated (code clean up)


= 1.1 =

- settings page updated
- settings moved under "Settings" section of WordPress
- default settings fixed
- warning notification if settings are out of date (after plugin update)
- settings updated - you need to update configurtion in admin

= 1.0 =

- added settings
- few fixes, code clanup
- Czech translation updated

= 0.2 =

- fixed <content:encoded> - now it extends WordPress default tag
- fixed repeated excerpt problem
- full text of post is only embeded if this option is allowed in WordPress (native RSS settings)
- "Source: XYZ" added to end of content (link back to your site)
- added Czech translation, plugin is multi-language ready

= 0.1 =

first beta version