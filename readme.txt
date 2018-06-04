=== Externals ===
Contributors:       Michael Uno, miunosoft
Donate link:        http://en.michaeluno.jp/donate
Tags:               amazon, link, links, ad, ads, advertisement, widget, widgets, sidebar, post, posts, affiliate, affiliate marketing, ecommerce, internet-marketing, marketing, monetization, revenue, shortcode
Requires at least:  3.3
Tested up to:       4.2.2
Stable tag:         3
License:            GPLv2 or later
License URI:        http://www.gnu.org/licenses/gpl-2.0.html

Displays external contents.

== Description ==

If you are displaying data fetched from external sites, sometimes the page loads slow for the time to wait for the response from the external server.

This plugin will store those data in the cache database table and display them on the site.

There are several supported data types.

- Text - display text data from plain text files.
- HTML -  scrape part of a specified page element.
- XML - extract descriptions from feeds.
- Markdown -  display instructions from .md files.
- WordPress Readme - display instructions from WordPress readme files.
- Code - display code.
- Image - display images. 


= Features =

* **Caches** - the fetched data are caches in the site database.
* **Templates** - customize the design of the external elements.
* **Shortcode** - Insert the ads in posts and pages. 
* **PHP function** - Insert the ads in the theme template.
   
== Installation ==

1. Upload **`externals.php`** and other files compressed in the zip folder to the **`/wp-content/plugins/`** directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to **Dashboard** -> **Externals** -> **Add New External**.
1. Configure the options and select categories.
1. After saving the unit option, go to **'Manage Externals'** to get the shortcode or if you check one of the insert option, the links will automatically appear in posts or feeds depending on your choice. The widget is available in the **Apparent** -> **Widgets** page as well.

== Frequently asked questions ==

= What is this for? =

This plugin fetches data from external sources and stores them in the database and display them on the site.

WHen the external sites are, it will be useful 


== Other Notes ==

== Screenshots ==

1. **Setting Page** (Creating New External)
2. **Setting Page** (Selecting Categories)
3. **Embedding Links below Post**
4. **Widget Sample**

== Changelog ==
 
= 1.0.0 = 
- Released.