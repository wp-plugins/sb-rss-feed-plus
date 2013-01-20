=== SB RSS feed plus ===
Contributors: ladislav.soukup@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P6CKTGSXPFWKG&lc=CZ&item_name=Ladislav%20Soukup&item_number=SB%20RSS%20feed%20plus%20%5bWP%2dPlugin%5d&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: rss, feed, image, post thumbnail, add, enhance, enhanced, plus, better, flipboard, content:encoded, media:content, media, content
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: 1.0

This plugin will add post thumbnail to RSS feed item and optimize feed for FlipBoard

== Description ==

This plugin will add post thumbnail to RSS feed as "media:content" and "enclosure" tags.
Image is also added to HTML part of "description" and "content:encoded" tags.
You can also add server signature to end of feed content in form "Source: XYZ".


== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Feed is now updated, you should check it

No more settings are needed.

== Changelog ==

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

