<?php

add_action('widgets_init','mom_widget_newsletter');

function mom_widget_newsletter() {
	register_widget('mom_widget_newsletter');

	}

class mom_widget_newsletter extends WP_Widget {
	function __construct() {

		$widget_ops = array('classname' => 'momizat-news_letter','description' => __('Widget display NewsLetter Subscribe form it support Mailchimp, feedburner','theme'));
		parent::__construct('momizatNewsLetter',__('Momizat - NewsLetter','theme'),$widget_ops);

		}

	function widget( $args, $instance ) {
		extract( $args );
		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$style = $instance['style'];
		$type = $instance['type'];
		$msg = $instance['msg'];
		$list = $instance['list'];
		$form = isset($instance['form']) ? $instance['form'] : '';
		$feed_url = $instance['feed_url'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
?>
                        <div class="mom-newsletter <?php echo $style; ?>">
                            <h4><?php echo $msg; ?></h4>
			    <?php if ($type == 'feedburner') { ?>
		<form class="mn-form" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $feed_url; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
		     <i class="mn-icon brankic-icon-envelope"></i><input type="text" class="nsf" name="email" name="uri" placeholder="<?php _e('Enter your e-mail ..', 'theme'); ?>" />
		     <input type="hidden" name="loc" value="en_US"/>
			<input type="hidden" value="<?php echo $feed_url; ?>" name="uri"/>
                                <button class="button" type="submit"><?php _e('Subscribe','theme');?></button>
                </form>

		<?php } else { echo do_shortcode($form); } ?>

                        </div>
<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = $new_instance['style'];
		$instance['type'] = $new_instance['type'];
		$instance['msg'] = $new_instance['msg'];
		$instance['form'] = $new_instance['form'];
		$instance['list'] = $new_instance['list'];
		$instance['feed_url'] = $new_instance['feed_url'];

		return $instance;
	}

function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Newsletter','theme'),
			'style' => '',
			'type' => 'mailchimp',
			'msg' => __('Subscribe to our email newsletter.', 'theme'),
			'list' => '',
			'form' => '',
			'feed_url' => ''
 			);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$api_key = mom_option('mailchimp_api_key');

		?>
	<script>
		jQuery(document).ready(function($) {
			$('.widget-content').on( 'change', '#<?php echo $this->get_field_id( 'type' ); ?>',function () {
				if ($(this).val() === 'mailchimp') {
					$('#<?php echo $this->get_field_id('form'); ?>').parent().fadeIn();
					$('#<?php echo $this->get_field_id('feed_url'); ?>').parent().fadeOut();
				} else {
					$('#<?php echo $this->get_field_id('form'); ?>').parent().fadeOut();
					$('#<?php echo $this->get_field_id('feed_url'); ?>').parent().fadeIn();
				}

			});
				if ($('#<?php echo $this->get_field_id( 'type' ); ?>').val() === 'mailchimp') {
					$('#<?php echo $this->get_field_id('form'); ?>').parent().fadeIn();
					$('#<?php echo $this->get_field_id('feed_url'); ?>').parent().fadeOut();
				} else {
					$('#<?php echo $this->get_field_id('form'); ?>').parent().fadeOut();
					$('#<?php echo $this->get_field_id('feed_url'); ?>').parent().fadeIn();
				}
		});
	</script>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','theme'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  class="widefat" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'msg' ); ?>"><?php _e('Message:','theme'); ?></label>
		<textarea id="<?php echo $this->get_field_id( 'msg' ); ?>" name="<?php echo $this->get_field_name( 'msg' ); ?>" class="widefat" cols="20" rows="2"><?php echo $instance['msg']; ?></textarea>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Style', 'theme') ?></label>
		<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" class="widefat">
		<option value="" <?php selected($instance['style'], ''); ?>><?php _e('Default', 'theme'); ?></option>
		<option value="compact" <?php selected($instance['style'], 'compact'); ?>><?php _e('Compact', 'theme'); ?></option>
		</select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Maillist Type', 'theme') ?></label>
		<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
		<option value="mailchimp" <?php selected($instance['type'], 'mailchimp'); ?>><?php _e('Mailchimp', 'theme'); ?></option>
		<option value="feedburner" <?php selected($instance['type'], 'feedburner'); ?>><?php _e('feedburner', 'theme'); ?></option>
		</select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'form' ); ?>"><?php _e('Mailchimp form shortcode', 'theme') ?></label>
		<textarea id="<?php echo $this->get_field_id( 'form' ); ?>" name="<?php echo $this->get_field_name( 'form' ); ?>" class="widefat" cols="20" rows="2"><?php echo $instance['form']; ?></textarea>
		<small><?php _e('Use <a href="https://goo.gl/wo9fWA" target="_blank">MailChimp for WordPress plugin</a> to get form shortcode.', 'theme'); ?></small>
		</p>

		<p class="hide">
		<label for="<?php echo $this->get_field_id( 'feed_url' ); ?>"><?php _e('feedburner name: (your name without http://feeds.feedburner.com/) ', 'theme'); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'feed_url' ); ?>" name="<?php echo $this->get_field_name( 'feed_url' ); ?>" value="<?php echo $instance['feed_url']; ?>" class="widefat" />
		</p>


<?php
}
	} //end class
