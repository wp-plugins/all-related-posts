=== All Related Posts ===
Tags: Google, Yahoo, Bing, AOL, Ask, Baidu, related posts, widget, multiple widgets, seo, search, tags, read posts, previous, behavioral targeting, related posts, relevant posts, pages
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=79AKXNVRT8YSQ&lc=NL&item_name=All%20Related%20Posts%20plugin%20by%20Maarten&item_number=All%20Related%20Posts%20plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Stable tag: 1.0.4
Requires at least: 4.0
Tested up to: 4.3
Contributors: maartenjs, Apprique

Displays some related / relevant posts based on search engine terms, current post and previously viewed posts.

== Description ==
The All Related Posts plugin is a simple behavioral targetting plugin. It provides a widget which will display a specified number of relevant posts based on the user's behavior. It however does not create a user profile or impacts user privacy. 

This plugin combines functionality from several other plugins that show related posts in one way or another. 

It looks for related posts in the order indicated below until it has found a per widget specified number of relevant posts.

The widget can be configured to show one or more of the following:

*   **Posts related to seach engine search terms (tags and categories)**: This is based on the assumption that tags and categories corresponding to a user's search terms are relevant.
*   **The first post a visitor came to on a previous visit**: This is based on the assumption that he previously followed a relevant link from a search engine or other website. If he returns now, he might be looking for it again.
*   **Posts related to the shown post**: This is based on the assumption that tags and categories are indicative for related posts. Better tagging will result in better related posts.
*   **Posts related to seach engine search terms (full post content)**: If your tags and categories do not correspond with a user's search terms, a full content search may result in other related posts. As this search is relatively database intensive, it is performed last. Make sure to add your blog domain name to the list of to be ignored terms in the widget, to prevent all posts with internal links to match.

Multiple widgets are supported, enabling you to show different groups of links for different scenarios with different titles.

**Note: If no relevant posts or pages are found, no widget is shown.**

The admin interface supports the following translations:

*   Dutch (nl_NL)
*   Slovak (sk_SK) (thanks to Branco Radenovich, WebHostingGeeks.com)
*   Traditional Chinese (zh_TW)

== Installation ==
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.
4. Drag the widget to a sidebar.
5. Edit the sidebar title and other settings if needed.

== Screenshots ==

1. Widget settings for All Related Posts widget
2. Resulting widget

== Changelog ==

= 1.0.4 =
* Compatible with WP 4.3 which does not allow PHP 4 style constructors (this is an outdated programming style) 

= 1.0.3 =
* Tested compatibility up to Wordpress 3.5.1 
* Making it more clear in the admin interface that no widget will be shown when no related posst are found.
* Translation added: Slovak (sk_SK) (thanks to Branco Radenovich, WebHostingGeeks.com)

= 1.0.2 =
* Confirmed compatibility with Wordpress 3.2

= 1.0.1 =
* Fixed showing debug info.

= 1.0 =
* Improved algoritm to find the most relevant posts related to the current post.
* Added translation support for Widget settings page. 
* Added Dutch (nl_NL) and Traditional Chinese (zh_TW) translations.

= 0.9.1 =
* Corrected an issue in one of the database queries. Now supports any wordpress database prefix.

= 0.9 =
* Initial release 

== Support ==
To see how and that it works, do the following:
1. Install the plugin
2. Add a widget to a sidebar
3. Finding your blog in a search engine and then clicking on the link. 
4. Browse to another post. 
5. Restart your browser and go to your website's url by typing it in the url bar or using a bookmark. 