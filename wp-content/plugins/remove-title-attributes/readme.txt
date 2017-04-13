=== Remove Title Attributes ===
Contributors: Technokinetics
Donate link: http://www.technokinetics.com/donations/
Tags: title attributes, accessibility, pages, categories, archives, tags
Requires at least: 2.5
Tested up to: 2.8.4
Stable tag: 1.0

Improves accessibility by removing the redundant title attributes that WordPress automatically adds to your website's Page lists, category lists, archives, and tag clouds.

== Description ==

The title attribute is best used to add optional advisory information to a page element. WordPress's default behaviour is to add title attributes to pretty much every link that it can, whether it has advisory information about it to offer or not. For example, links in Page lists, archive menus, and in the default "Recent Posts" widget simply duplicate the link text in their title attributes. This helps no one but hinders some users.

Remove Title Attributes lets you remove these title attributes, making your website more accessible.

== Installation ==

1. Download the plugin's zip file, extract the contents, and upload them to your wp-content/plugins folder.
2. Login to your WordPress dashboard, click "Plugins", and activate Remove Title Attributes.
2. Customise your settings on the Settings > Remove Title Attributes page.

== Frequently Asked Questions ==

= Why aren't all of my title attributes removed? =

There are two ways that title attributes are added to WordPress websites. Some title attributes are added by WordPress functions such as wp_list_pages(), wp_list_categories(), wp_get_archives(), etc.; these can be removed with Remove Title Attributes. Other title attributes are hard-coded in theme files, so cannot be removed using a plugin. To remove these, you will need to edit your theme files.

= Why aren't title attributes removed from my Recent Posts sidebar widget? =

WordPress includes a built-in Recent Posts sidebar widget that includes hard-coded title attributes that can't be removed using a plugin. Remove Title Attributes creates an alternative Recent Posts (No Title Attributes) sidebar widget that you can use in its place. 

== Screenshots ==

