<?php
/*
Plugin Name: Multiple Rich Editors
Plugin URI: https://github.com/nickohrn/multiple-rich-editors
Description: Allows developers to quickly add multiple rich editors to any content type.
Version: 1.0.0
Author: Nick Ohrn of Plugin-Developer.com
Author URI: http://plugin-developer.com/
*/

if(!class_exists('MRE')) {
	class MRE {
		/// Version
		const VERSION = '1.0.0';

		public static function init() {
			self::add_actions();
			self::add_filters();
			self::initialize_defaults();
		}

		private static function add_actions() {
			if(is_admin()) {
				add_action('edit_form_after_editor', array(__CLASS__, 'add_rich_editors'));
				add_action('save_post', array(__CLASS__, 'save_rich_editors_content'));
			} else {
				// Frontend only actions
			}
		}

		private static function add_filters() {
			// Common filters

			if(is_admin()) {
				// Administrative only filters
			} else {
				// Frontend only filters
			}
		}

		private static function initialize_defaults() {

		}

		/// Callbacks

		public static function add_rich_editors() {
			$post_id = get_the_ID();
			$post_type = get_post_type();
			$support = get_theme_support('multiple-rich-editors');

			$editors = is_array($support) && isset($support[0]) && is_array($support[0]) ? $support[0] : array();
			$editors = self::_normalize($editors);

			$editor_once = false;
			foreach($editors as $editor_name => $editor_args) {
				if(in_array($post_type, $editor_args['post_types'])) {
					$content = self::_get_content($post_id, $editor_name);
					$editor_once = true;

					include('views/rich-editor.php');
				}
			}

			if($editor_once) {
				wp_nonce_field('multiple-rich-editors', 'multiple-rich-editors-nonce');
			}
		}

		public static function save_rich_editors_content($post_id) {
			$data = stripslashes_deep($_POST);

			if(wp_is_post_autosave($post_id)
				|| wp_is_post_revision($post_id)
				|| !isset($data['mre'])
				|| !is_array($data['mre'])
				|| !isset($data['multiple-rich-editors-nonce'])
				|| !wp_verify_nonce($data['multiple-rich-editors-nonce'], 'multiple-rich-editors')) {
				return;
			}

			foreach($data['mre'] as $editor_name => $editor_content) {
				$editor_content = apply_filters('content_save_pre', $editor_content);

				self::_set_content($post_id, $editor_name, $editor_content);
			}
		}

		/// Post meta

		private static function _get_content($post_id, $name) {
			$name = sanitize_title_with_dashes($name);
			$post_id = empty($post_id) && in_the_loop() ? get_the_ID() : $post_id;

			return (string)get_post_meta($post_id, "mre-{$name}", true);
		}

		private static function _set_content($post_id, $name, $content) {
			$name = sanitize_title_with_dashes($name);
			$post_id = empty($post_id) && in_the_loop() ? get_the_ID() : $post_id;

			update_post_meta($post_id, "mre-{$name}", $content);
		}

		/// Utility

		private static function _normalize($editors) {
			$normalized = array();

			foreach($editors as $editor_name => $editor_args) {
				$normalized_args = array();
				$normalized_name = sanitize_title_with_dashes($editor_name);

				if(!isset($editor_args['post_types'])) {
					$normalized_args['post_types'] = array();
				} else if(!is_array($editor_args['post_types'])) {
					$normalized_args['post_types'] = array($editor_args['post_types']);
				} else {
					$normalized_args['post_types'] = $editor_args['post_types'];
				}

				$normalized_args['post_types'] = array_filter($normalized_args['post_types']);

				$normalized_args['label'] = isset($editor_args['label']) ? $editor_args['label'] : '';
				$normalized_args['wp_editor'] = isset($editor_args['wp_editor']) && is_array($editor_args['wp_editor']) ? $editor_args['wp_editor'] : array();

				$editor_name_sanitized = sanitize_title_with_dashes($editor_name);
				$normalized_args['wp_editor']['textarea_id'] = "mre-{$editor_name_sanitized}";
				$normalized_args['wp_editor']['textarea_name'] = "mre[{$editor_name_sanitized}]";

				$normalized[$normalized_name] = $normalized_args;
			}

			return $normalized;
		}

		/// Template tags

		public static function get_content($name, $post_id) {
			return self::_get_content($post_id, $name);
		}
	}

	require_once('lib/template-tags.php');
	MRE::init();
}
