<?php
if (!defined('ABSPATH'))
  exit;
?>
<div id="ig-generate-token" class="<?php echo!empty($qligg_token) ? 'premium' : ''; ?>">
  <p>
    <a class="btn-instagram-account" target="_self" href="<?php echo esc_url($qligg_api->get_create_account_link()); ?>" title="<?php _e('Add New Account', 'insta-gallery'); ?>">
      <?php _e('Add New Account', 'insta-gallery'); ?>
    </a>
    <span style="float: none; margin-top: 0;" class="spinner"></span>
    <a data-qligg-toggle="#ig-update-token" href="#"><?php _e('Button not working?', 'insta-gallery'); ?></a>
  </p>
  <form id="ig-update-token" class="qligg-box hidden" method="post">
    <h4><?php _e('Manually connect an account', 'insta-gallery'); ?></h4>
    <p class="field-item">
      <input class="widefat" name="ig_access_token" type="text" maxlength="200" placeholder="<?php _e('Enter a valid Access Token', 'insta-gallery'); ?>" required />
    </p>
    <button type="submit" class="btn-instagram secondary"><?php _e('Update', 'insta-gallery'); ?></button>    
    <span style="float: none; margin-top: 0;" class="spinner"></span>
    <a target="_blank" href="https://quadlayers.com/insta-token/"><?php _e('Get access token', 'insta-gallery'); ?></a>
    <?php wp_nonce_field('qligg_update_token', 'ig_nonce'); ?>
  </form>
</div>
<?php if (is_array($qligg_token) && count($qligg_token)) : ?>
  <table class="widefat ig-table">
    <thead>
      <tr>
        <th><?php _e('Image', 'insta-gallery'); ?></th>
        <th><?php _e('ID', 'insta-gallery'); ?></th>
        <th><?php _e('User', 'insta-gallery'); ?></th>
        <th><?php _e('Name', 'insta-gallery'); ?></th>
        <th><?php _e('Token', 'insta-gallery'); ?></th>
        <th><?php _e('Action', 'insta-gallery'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      //if (count($qligg_token)) {
      foreach ($qligg_token as $id => $access_token) {
        $profile_info = qligg_get_user_profile($id);
        ?>
        <tr>
          <td class="profile-picture"><img src="<?php echo esc_url($profile_info['picture']); ?>" width="30" /></td>
          <td><?php echo esc_attr($id); ?></td>
          <td><?php echo esc_html($profile_info['user']); ?></td>
          <td><?php echo esc_html($profile_info['name']); ?></td>
          <td>
            <input id="<?php echo esc_attr($id); ?>-access-token" type="text" value="<?php echo esc_attr($access_token); ?>" readonly />
          </td>
          <td>
            <a data-qligg-copy="#<?php echo esc_attr($id); ?>-access-token" href="#" class="btn-instagram">
              <span class="dashicons dashicons-edit"></span><?php _e('Copy', 'insta-gallery'); ?>
            </a>
            <a href="#" data-item_id="<?php echo esc_attr($id); ?>" class="btn-instagram ig-remove-token">
              <span class="dashicons dashicons-trash"></span><?php _e('Delete', 'insta-gallery'); ?>
            </a>
            <span class="spinner"></span>
          </td>
        </tr>
        <?php
      }
      //}
      ?>
    </tbody>
  </table>  
<?php endif; ?>
<form id="ig-save-settings" method="post">
  <table class="widefat form-table ig-table">
    <tbody>
      <tr>
        <td colspan="100%">
          <table>
            <tbody>
              <tr>
                <th><?php _e('Loader icon', 'insta-gallery'); ?></th>
                <td>
                  <?php
                  $mid = '';
                  $misrc = '';
                  if (isset($qligg['insta_spinner_image_id'])) {
                    $mid = $qligg['insta_spinner_image_id'];
                    $image = wp_get_attachment_image_src($mid);
                    if ($image) {
                      $misrc = $image[0];
                    }
                  }
                  ?>
                  <input type="hidden" name="insta_spinner_image_id" value="<?php echo esc_attr($mid); ?>" data-misrc="<?php echo esc_attr($misrc); ?>" />
                  <a class="btn-instagram" id="ig-spinner-upload" /><?php _e('Upload', 'insta-gallery'); ?></a>
                  <a class="btn-instagram" id="ig-spinner-reset" /><?php _e('Reset Spinner', 'insta-gallery'); ?></a> 
                  <span class="description">
                    <?php _e('Please select the image from media to replace with default loader icon.', 'insta-gallery'); ?>
                  </span>
                </td>
                <td rowspan="2">
                  <div class="insta-gallery-spinner">
                  </div>
                </td>
              </tr>
              <tr>
                <th><?php _e('Remove data on uninstall', 'insta-gallery'); ?></th>
                <td>
                  <input id="ig-remove-data" type="checkbox" name="insta_flush" value="1" <?php if (!empty($qligg['insta_flush'])) echo 'checked'; ?> />
                  <span class="description">
                    <?php _e('Check this box to remove all data related to this plugin when removing the plugin.', 'insta-gallery'); ?>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3">
          <span class="spinner"></span>
          <button  type="submit" class="btn-instagram secondary"><?php _e('Update', 'insta-gallery'); ?></button>
          <span class="description">
            <?php //_e('Update settings and copy/paste generated shortcode in your post/pages or go to Widgets and use Instagram Gallery widget', 'insta-gallery');   ?>
          </span>
        </td>
      </tr>
    </tfoot>
  </table>
  <?php wp_nonce_field('qligg_save_settings', 'ig_nonce'); ?>
</form>