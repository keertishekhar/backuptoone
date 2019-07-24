<ol class="mom-wizard-steps">
	<?php 
	$i = 1;
	$array_keys = array_keys($ouput_steps);
	$last_key = end($array_keys);
	foreach ( $ouput_steps as $step_key => $step ) : ?>
		<li class="<?php echo esc_attr($step_key); ?> 
		<?php
		$show_link = false;

		if ( $step_key === $this->step && $last_key != $this->step) {
			echo 'active';
		} elseif ( (array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) )) || $last_key == $this->step ) {
			echo 'done';
			$show_link = false;
		}
		?>"><?php
			if ( $show_link ) {
				?>
				<a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
				<?php
			} else {
				echo esc_html( $step['name'] );
				echo '<span>'.$i.'<i class="check hidden">'.$this->svg('check').'</i></span>';
			}
			?></li>
	<?php 
	$i++;
	endforeach; ?>
</ol>