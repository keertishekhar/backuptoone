<div id="<?php echo esc_attr($item_selector); ?>" class="insta-gallery-feed" data-feed="<?php echo htmlentities(json_encode($feed_options), ENT_QUOTES, 'UTF-8'); ?>" data-feed_layout="<?php echo esc_attr($instagram_feed['insta_layout']); ?>">
  <div class="insta-gallery-list">
  </div>
  <div class="insta-gallery-spinner"></div>
  <div class="insta-gallery-actions">
    <?php if ($instagram_feed['insta_button_load']) : ?>
      <a href="#" target="blank" class="insta-gallery-button load"><?php _e('Load more...', 'insta-gallery'); ?></a>
    <?php endif; ?>
    <?php if ($instagram_feed['insta_button']) : ?>
      <a href="<?php echo esc_url($profile_info['link']); ?>" target="blank" class="insta-gallery-button follow">
        <i class="qligg-icon-instagram-o"></i>
        <?php echo esc_html($instagram_feed['insta_button-text']); ?></a>
      <?php endif; ?>
  </div>
</div>