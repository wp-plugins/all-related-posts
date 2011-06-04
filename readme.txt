=== All Related Posts ===
Tags: Google, Yahoo, Bing, AOL, Ask, Baidu, related posts, seo, search, tags, read posts, previous, behavioral targeting, related posts, relevant posts, pages
Donate link: blog.bigcircle.nl
Stable tag: 0.9.1
Requires at least: 3.0
Tested up to: 3.1
Contributors: maartenjs

Displays some related / relevant posts based on search engine terms, current post and previously viewed posts.

== Description ==
The All Related Posts plugin is a simple behavioral targetting plugin. It provides a widget which will display a specified number of relevant posts based on the user's behavior. It however does not create a user profile or impacts user privacy. 

This plugin combines functionality from several other plugins that show related posts in one way or another. 

It looks for related posts in the order indicated below until it has found a per widget specified number of relevant posts.

The widget can be configured to show one or more of the following:
*   **The first post a visitor came to on a previous visit**: This is based on the assumption that he previously followed a relevant link from a search engine or other website. If he returns now, he might be looking for it again.
*   **Posts related to seach engine search terms (tags and categories)**: This is based on the assumption that tags and categories corresponding to a user's search terms are relevant.
*   **Posts related to the shown post**: This is based on the assumption that tags and categories are indicative for related posts. Better tagging will result in better related posts.
*   **Posts related to seach engine search terms (full post content)**: If your tags and categories do not correspond with a user's search terms, a full content search may result in other related posts. As this search is relatively database intensive, it is performed last. Make sure to add your blog domain name to the list of to be ignored terms in the widget, to prevent all posts with internal links to match.

Multiple widgets are supported, enabling you to show different groups of links for different scenarios with different titles.

If no relevant posts or pages are found, no widget is shown.

== Installation ==
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.
4. Drag the widget to a sidebar.
5. Edit the sidebar title and other settings if needed.

== Changelog ==

= 0.9 =
* initial release 

= 0.9.1 =
* corrected an issue in one of the database queries. Now supports any wordpress database prefix.

== Support ==
To see how and that it works, do the following:
1. Install the plugin
2. Add a widget to a sidebar
3. Finding your blog in a search engine and then clicking on the link. 
4. Browse to another post. 
5. Restart your browser and go to your website's url by typing it in the url bar or using a bookmark. 