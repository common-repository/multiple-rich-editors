=== Multiple Rich Editors ===

Contributors: nickohrn
Donate link: http://example.com/
Tags: admin, editor
Requires at least: 3.8
Tested up to: 3.8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows developers to easily register addition rich editors and retrieve / display the content entered within.

== Description ==

This plugin allows developers to easily register addition rich editors and retrieve / display the content entered within.

== Installation ==

1. Upload `multiple-rich-editors` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Register new rich editors using add_theme_support and use the provided template tags to retrieve and display the data you need

== Frequently Asked Questions ==

= How do I add a rich editor? =

Easy! In your theme's functions.php file, add the following code:

`function register_rich_editors() {
	add_theme_support('multiple-rich-editors', array(
		'additional-editor' => array(
			'label' => __('Additional Editor'),
			'post_types' => array('post'),
			'wp_editor' => array('media_buttons' => true),
		),
		'additional-editor-2' => array(
			'label' => __('Additional Editor 2'),
			'post_types' => array('post', 'page'),
			'wp_editor' => array('media_buttons' => false),
		),
	));
}
add_action('after_setup_theme', 'register_rich_editors');`

Feel free to provide whatever values you need.

= How do I know what I should provide when registering a rich editor? =

Easy! Just take a look at the `/lib/template-tags.php` - there are detailed
instructions in there on how to register editors and use the template
tags to get the data you need.

= My content loses the paragraphs that are specified in the editor. What do I do? =

Multiple Rich Editors doesn't apply the default WordPress content filters on its content by default. However, there is a code snippet in `/lib/template-tags.php` that explains how to do this. Follow the directions there and you should be good to go!

== Changelog ==

= 1.0.0 =

* Initial release

== Upgrade Notice ==

= 1.0.0 =

* Initial release
