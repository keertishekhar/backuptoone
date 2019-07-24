<?php
function mom_video($atts, $content) {
	extract(shortcode_atts(array(
		'width' => '600',
		'height' => '400',
		'id' => '',
		'type' => 'youtube'
			), $atts));
			if($type == 'vimeo') {
				$type= "//player.vimeo.com/video/";
			} else {
				$type="//www.youtube.com/embed/";
			}
		return '<div class="video_wrap"><iframe width="'.$width.'" height="'.$height.'" src="'.$type.$id.'"></iframe></div>';

	}

add_shortcode('mom_video', 'mom_video');

?>
