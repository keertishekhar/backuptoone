<?php 
if ( class_exists( 'Envato_Market' ) ) {
	$update_link = admin_url('admin.php?page=envato-market');
} else {
	$update_link = admin_url('admin.php?page=momizat-plugins');
}
?>
<div class="wizard-content">
	<div class="header">
		<h2 class="start-header">
			<?php echo MOM_THEME_NAME; ?> <?php echo sprintf(__('Setup has been %1$s completed successfully! %2$s', 'meza'), '<span class="success">', '</span>'); ?>
		</h2>
	</div>
	<div class="wizard-hero">
		<a href="<?php echo home_url(); ?>" class="wizard-button">
			<?php echo __('View Site', 'meza'); ?>
		</a>
		<p>
			<?php echo sprintf(__('The theme has been activated and your website is ready. Best of luck with your project!.', 'meza')); ?>
		</p>
		<span class="whats-next"><?php echo __('What\'s next?', 'meza'); ?></span>
	</div>
	<div class="ready-links">
		<a href="<?php echo esc_url( $update_link );?>">
			<i><?php echo mom_content_sanitize($this->svg('update')); ?></i> 
			<?php echo __('Activate Theme Updates', 'meza'); ?>
		</a>
		<a href="//support.momizat.com" class="gray">
			<i><?php echo mom_content_sanitize($this->svg('help')); ?></i> 
			<?php echo __('Help Center / Support', 'meza'); ?>
		</a>

	</div>
</div>