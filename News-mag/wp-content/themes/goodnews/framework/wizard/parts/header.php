<?php 
  $image = get_theme_mod('logo_img', '');
  if ($image && is_numeric($image)) {
    $image = wp_get_attachment_image_src( $image, 'full' );
    $image = isset($image[0]) ? $image[0] : '';
  }
  if ($image == '') {
    $image = get_template_directory_uri() . '/images/logo.png';
  }
?>
<div class="wizard-header">
  <img class="logo" src="<?php echo esc_url($image); ?>" alt="">
</div>
