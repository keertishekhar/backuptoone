<div class="wizard-content">
	
	<div class="header">
		<h2 class="start-header"><?php echo MOM_THEME_NAME; ?> <?php echo __('Setup Wizard', 'meza'); ?></h2>
	</div>
	
	<div class="wizard-hero">
		<a href="<?php echo esc_attr($this->get_next_step_link()); ?>" class="wizard-button"><?php echo __('Getting Start', 'meza'); ?></a>
		<p><?php echo __('The quickest way to setup the theme and start working on your site.', 'meza'); ?></p>

		<span class="rocket"><?php echo mom_content_sanitize($this->svg('rocket')); ?></span>
		<span class="clouds"><?php echo mom_content_sanitize($this->svg('clouds')); ?></span>
	</div>
	
	<a href="<?php echo esc_attr($this->get_next_step_link()); ?>" class="start"><?php echo __('Start', 'meza'); ?></a>

</div>
