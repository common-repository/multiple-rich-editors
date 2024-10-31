<?php

/**
 * To register a new rich editor, use add_theme_support('multiple-rich-editors', $editors), where
 * $editors is an associative array of $editor_name => $editor_args. $editor_name should contain
 * lowercase letters, numbers, and hyphens only. If it does not, it will be sanitized to read as such.
 * $editor_args is an array which can have the following contents:
 *
 * - label - The text that should appear above the editor - contextualizes the editor
 * - post_types - An array of post_type keys on which this rich editor should appear
 * - wp_editor - An array of overrides for the wp_editor function. See See _WP_Editors::editor()
 */

/**
 * If you want to apply the default content filters to the values returned from the mre_get_content and
 * mre_the_content template tags below, insert the following code into your theme.
 */

/*
function apply_content_filters_to_mre_get_content($content, $name, $post_id) {
	return apply_filters('get_the_content', $content);
}
add_filter('mre_get_content', 'apply_content_filters_to_mre_get_content', 11, 3);

function apply_content_filters_to_mre_the_content($content, $name, $post_id) {
	return apply_filters('the_content', $content);
}
add_filter('mre_the_content', 'apply_content_filters_to_mre_the_content', 11, 3);
*/

/**
 * Returns a boolean value indicating whether an a particular registered editor for a post has content.
 *
 * @param string $name The name of the rich editor to query
 * @param int|null $post_id The id of the post to query - defaults to the current post if in the loop
 * @return boolean True if an author has entered content for the specified editor
 */
function mre_has_content($name, $post_id = null) {
	return apply_filters('mre_has_content', !!(mre_get_content($name, $post_id)), $name, $post_id);
}

/**
 * Returns the raw content for the queried rich editor and the post specified
 *
 * @param string $name The name of the rich editor to query
 * @param int|null $post_id The id of the post to query - defaults to the current post if in the loop
 * @return string A string containing all content entered for the specified rich editor
 */
function mre_get_content($name, $post_id = null) {
	return apply_filters('mre_get_content', MRE::get_content($name, $post_id), $name, $post_id);
}

/**
 * Echoes the return value of mre_get_content after applying the mre_the_content filter
 *
 * @param string $name The name of the rich editor to query
 * @param int|null $post_id The id of the post to query - defaults to the current post if in the loop
 * @param string $size The size of content to retrieve
 * @param array $attributes Extra attributes to add to the HTML content tag
 * @return string|boolean An HTML content tag for the chosen content for the queried rich editor if an author
 * has selected an content and false otherwise.
 */
function mre_the_content($name, $post_id = null) {
	echo apply_filters('mre_the_content', mre_get_content($name, $post_id), $name, $post_id);
}
