=== Wp Tag Magic Widget ===
Contributors: Ramachandran Mariapan
Tags: tag, category, cloud, widget
Requires at least: 2.8
Tested up to: 4.0
Stable tag: 1.0

Display selected tags or categories as a tag cloud using a sidebar widget or shortcode.

== Description ==
This plugin allows you to add a tag/category cloud with count widget  to your sidebar or use a shortcode to show a category/tag cloud with count in post & page.  You can select color combination of cloud, the minimum number of posts in a category to show and which categories to include or exclude.

= Features =

* Use as a widget or shortcode
* Configurable color combination of your cloud display
* Order by number of posts in each category or alphabetically
* Specify the minimum number of posts that a category has to have before it shows
* Specify categories to include or exclude, or use them all

= Usage =
**Shortcode**
Optionally enter [tagmagic] in a page or post to show the category cloud. See the FAQ for examples.

**Title**
This is the usual widget title that will appear in your theme's sidebar.

**Order by**

Choose between ordering by number of posts in a category, or alphabetically by category name.

**Show by**
Either the category with the most posts first or the category with the fewest posts first if using Order by: count, or A-Z or Z-A if Order by: name.

**Minimum number of posts**
Categories where the total number of posts is less than this number will not be shown. Set to 1 to hide empty categories.

**Comma separated category IDs**
If you only want to include specific categories, enter their IDs in a list. If you want to exclude a category, enter its ID as a negative number. Leave blank for all categories.

* Example: 1,10,20
This will create a category cloud with only categories 1,10,20 in it.

* Example: -1,-3
This will create a category cloud hiding categories 1 and 3.

**Parent category ID**
If you only want to include  categories cloud of particular parent category, enter their IDs in a list.  Leave blank for all categories.

* Example: 1
This will create a category cloud of parent category  1.

== Installation ==

Installing the plugin:

1. Download Wp Tag magic and unzip
2. Upload wp-tag-magic  folder to the /wp-content/plugins/ directory
3. Activate the plugin through the ‘Plugins’ menu in WordPress
4. Add the widget named :tag cloud (wp tag magic) to your sidebar through the ‘Appearance > Widgets’ menu

== Frequently Asked Questions ==

= How to show categories clouds of particular parent category? =

Enter its ID in 'Parent category ID'

= How to I hide empty categories? =

Set the minimum number of posts to 1.

= How do I exclude a category? =

Enter its ID as a negative in the 'Comma separated category IDs' field e.g. to exlude category 5, enter -5

= How do I specify options when using the shortcode? =
Override the following defaults:

* color: red
* orderby: name
* order: ASC
* min_count: 1
* cats_inc_exc
* cats_inc_parent

Examples

* [tagmagic]
* [tagmagic order="DESC"]
* [tagmagic color="red"]

== Screenshots ==
Please visit plugin homepage: http://www.guidingwp.com/wordpress-tag-magic-widget/

== Changelog ==
= 1.0 =
* Initial version of the plugin