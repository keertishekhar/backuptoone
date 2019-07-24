<div class="wizard-content">
				<div class="header">
					<h2 class="start-header"><?php echo __('Plugin Installation', 'meza'); ?></h2>
				</div>
				<div class="wizard-content-inner">
					<p><?php echo __('Install the required plugins before importing the demos.', 'meza'); ?></p>

<?php
$plugins = $this->_get_plugins();
if ( count( $plugins['all'] ) ) {
	?>
	<div class="plugins-lists">
	<ul class="mom-wizard-plugins">
		<?php foreach ( $plugins['all'] as $slug => $plugin ) { 
				if (!isset($plugin['required']) || !$plugin['required']) {
							continue;						
				}
				if ( !isset( $plugins['installed'][ $slug ] ) ) {
					continue;						
				}
			?>
			<li class="installed" data-slug="<?php echo esc_attr( $slug ); ?>">
			<?php 
				if (isset($plugin['logo'])) {
					echo '<img src="'.$plugin['logo'].'" alt="">';
				}
			?>
				<span class="name"><?php echo esc_html( $plugin['name'] ); ?>
					<span class="ajax-message"></span>
				</span>
				<span class="icon check"><?php echo mom_content_sanitize($this->svg('check')); ?></span>
			</li>
		<?php } ?>
	</ul>

<ul class="mom-wizard-plugins not-installed">
		<?php 
		$plugins_to_install = 0;
		foreach ( $plugins['all'] as $slug => $plugin ) { 
				if (!isset($plugin['required']) || !$plugin['required']) {
							continue;						
				}
				if ( isset( $plugins['installed'][ $slug ] ) ) {
					continue;						
				}

				$keys = array();
				if ( isset( $plugins['install'][ $slug ] ) ) {
					$keys[] = 'install';
				}
				if ( isset( $plugins['update'][ $slug ] ) ) {
					$keys[] = 'update';
				}
				if ( isset( $plugins['activate'][ $slug ] ) ) {
					$keys[] = 'activate';
				}
				$class = implode( ' and ', $keys );
				$plugins_to_install++;
			?>
			<li class="<?php echo esc_attr($class); ?>" data-slug="<?php echo esc_attr( $slug ); ?>">
			<?php 
				if (isset($plugin['logo'])) {
					echo '<img src="'.$plugin['logo'].'" alt="">';
				}
			?>
				<span class="name"><?php echo esc_html( $plugin['name'] ); ?>
					<span class="ajax-message"></span>
				</span>
				<span class="icon check"><?php echo mom_content_sanitize($this->svg('check')); ?></span>
				<span class="icon fail"><?php echo mom_content_sanitize($this->svg('close')); ?></span>
			</li>
		<?php } ?>
	</ul>
	</div>

	<?php
} else {
	//echo '<p><strong>' . esc_html_e( 'Good news! All plugins are already installed and up to date. Please continue.' ) . '</strong></p>';
} ?>

<!-- <p><?php //esc_html_e( 'You can add and remove plugins later on from within WordPress.' ); ?></p> -->

	</div>
	<div class="footer">
	<?php 
		if ($plugins_to_install) { 
		?>

		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="link skip">
			<?php esc_html_e( 'Skip', 'meza' ); ?>
		</a>
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="link button-next" data-callback="install_plugins">
			<?php esc_html_e( 'Install', 'meza' ); ?>
		</a>
		<?php 
			}
		?>
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="link next <?php echo $plugins_to_install ? 'hidden' : 'center'; ?>">
			<?php esc_html_e( 'Next', 'meza' ); ?>
		</a>

	</div>
</div>