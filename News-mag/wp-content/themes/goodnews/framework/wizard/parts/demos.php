<div class="wizard-content">
			<div class="header">
				<h2 class="start-header"><?php echo __('Demo Installation', 'meza'); ?></h2>
			</div>
			<div class="wizard-content-inner demo">
				<p><?php echo __('Select demo to install now.', 'meza'); ?></p>
				<?php include get_template_directory(). '/framework/demos/demos-setup.php';?>
			</div>
			<div class="footer">
				<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="link next center">
					<?php esc_html_e( 'Next', 'meza' ); ?>
				</a>
			</div>
</div>