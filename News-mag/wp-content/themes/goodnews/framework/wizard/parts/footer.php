<a class="return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>">
	<?php 
		echo '<i>'.mom_content_sanitize($this->svg('arrow-left')).'</i>'; 
		esc_html_e( 'Return to the WordPress Dashboard', 'meza' ); 
	?>
</a>
