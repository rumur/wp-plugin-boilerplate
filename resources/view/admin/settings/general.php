<div class="wrap" id="<?php echo $id; ?>">
	<h2><?php echo $title; ?></h2>
	<form action="options.php" method="POST">
		<?php settings_fields($options_group); ?>
		<?php do_settings_sections($page); ?>
		<?php submit_button(); ?>
	</form>
</div>
