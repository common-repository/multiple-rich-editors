<div class="multiple-rich-editor">
	<?php if(!empty($editor_args['label'])) { ?>
	<h2><?php echo esc_html($editor_args['label']); ?></h2>
	<?php } ?>

	<div class="postarea edit-form-section">
		<?php wp_editor($content, $editor_args['wp_editor']['textarea_id'], $editor_args['wp_editor']); ?>
	</div>
</div>