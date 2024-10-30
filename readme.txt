=== Modify Visual Editor ===
Plugin URI: http://themes.tradesouthwest.com/wordpress/plugins/Modify-Visual-Editor/
Donate link: http://tradesouthwest.com/paystation.php/
Author: tradesouthwest
Author URI: http://tradesouthwest.com/
License: GPLv3 or higher
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: visual editor, disable visual editor, tinymce, disable tinymce
Requires at least: 4.0
Tested up to: 4.7.1
Stable tag: 1592145
Plugin version: 0.2.2

This plugin disables the visual editor for all users. You may allow the use of the Visual Editor for Admins if you would like. This plugin leaves the editor in place for posts and pages as well as custom posts types but only has the "Text" editor option for users to utilize. Very handy for developers who do not want other users changing HTML text when writing in the Visual elements. Plugin, on deactvation, will update usermeta and reset the database back to 'true' for meta_value 'rich_edting' and clears options so plugin will not linger after uninstall.

== Description ==

This plugin disables the visual editor drastically at any user login state. But if a user checks the box to enable visual editor, in the Profile page, the setting will be saved so they may edit with Visual Editor yet, will always return to disabled when they log back in. This is done for version 0.2.0 and higher as an experiment to see how well users can accept the fact that visual Editor is a dangerous thing in the hands of non-experienced HTML writers.

== Installation ==

1. Download the zipped file.
2. Upload the file to your `/wp-contents/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

Q.: I see a dropdown for users but it does not work
A.: the dropdown is for Administrators only. You must set each Admin one by one if you want each admin to have access to the visual editor.

= Can I add features? =

Yes, please contribute and fork from Github or ask on WordPress plugin page for support. This is Open Source.

= What is the "hidden editor bar?" =

WordPress removed a lot of components from the latest versions to make way for more room on the page. Older versions of MVEd would open up these hidden elements to use, which will show just below the two active menu bars of the Visual Editor.

Font Family, Font Sizes, Formats (dropdown), Background Color (Hightlighter), New Document
Cut, Copy, Special Characters, Horizontal Line

There are a few more that are commented out in the mved-modify-media-buttons file. After version 0.2.0 we discontinued these to streamline the plugin.

== Change Log ==
0.2.0
- added uninstall
- removed editor buttons option
- added WP ver check

0.2.1
- change textdomain load to init
- reworked activation and deactivation
- switched method of update user_status
** Have a Wonderful Time **

0.2.2
- added unistall hook
- removed options to pick users
- added option to allow admins only
