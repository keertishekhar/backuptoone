<?php
/**
 * Framework
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*
 * Main Helper Class
 */
locate_template( 'framework/classes/class-tielabs-helper.php', true, true );

/**
 * Framework Functions
 */
locate_template( 'framework/functions/general-functions.php', true, true );
locate_template( 'framework/functions/media-functions.php',   true, true );
locate_template( 'framework/functions/post-functions.php',    true, true );
locate_template( 'framework/functions/breadcrumbs.php',       true, true );
locate_template( 'framework/functions/formatting.php',        true, true );
locate_template( 'framework/functions/post-actions.php',      true, true );
locate_template( 'framework/functions/page-templates.php',    true, true );

/**
 * Framework Classes
 */
locate_template( 'framework/classes/class-tielabs-filters.php',       true, true );
locate_template( 'framework/classes/class-tielabs-advertisment.php',  true, true );
locate_template( 'framework/classes/class-tielabs-ajax.php',          true, true );
locate_template( 'framework/classes/class-tielabs-foxpush.php',       true, true );
locate_template( 'framework/classes/class-tielabs-speeder.php',       true, true );
locate_template( 'framework/classes/class-tielabs-styles-footer.php', true, true );
locate_template( 'framework/classes/class-tielabs-postviews.php',     true, true );
locate_template( 'framework/classes/class-tielabs-mega-menu.php',     true, true );
locate_template( 'framework/classes/class-tielabs-videos-list.php',   true, true );
locate_template( 'framework/classes/class-tielabs-pagination.php',    true, true );
locate_template( 'framework/classes/class-tielabs-opengraph.php',     true, true );
locate_template( 'framework/classes/class-tielabs-wp-helper.php',     true, true );
locate_template( 'framework/classes/class-tielabs-styles.php',        true, true );
locate_template( 'framework/classes/class-tielabs-weather.php',       true, true );
locate_template( 'framework/classes/class-tielabs-instagram.php',     true, true );

/**
 * Mobile Detector
 */
locate_template( 'framework/vendor/Mobile_Detect/devices.php', true, true );

/**
 * Backend Loader
 */
locate_template( 'inc/updates.php', true, true );

locate_template( 'framework/admin/framework-admin.php', true, true );


/**
 * Extensions
 *
 * By: TieLabs
 */
locate_template( 'framework/plugins/class-tielabs-extensions.php', true, true );

/**
 * AMP
 *
 * By: Automattic
 * https://wordpress.org/plugins/amp/
 */
locate_template( 'framework/plugins/class-tielabs-amp.php', true, true );

/**
 * WooCommerce
 *
 * By: Automattic
 * https://wordpress.org/plugins/woocommerce/
 */
locate_template( 'framework/plugins/class-tielabs-woocommerce.php', true, true );

/**
 * Sensei
 *
 * By: Automattic
 * https://woocommerce.com/products/sensei/
 */
locate_template( 'framework/plugins/class-tielabs-sensei.php', true, true );

/**
 * BuddyPress
 *
 * By: Multiple Authors
 * https://wordpress.org/plugins/buddypress/
 */
locate_template( 'framework/plugins/class-tielabs-buddypress.php', true, true );

/**
 * bbPress
 *
 * By: Multiple Authors
 * https://wordpress.org/plugins/buddypress/
 */
locate_template( 'framework/plugins/class-tielabs-bbpress.php', true, true );

/**
 * Jetpack
 *
 * By: Automattic
 * https://wordpress.org/plugins/jetpack/
 */
locate_template( 'framework/plugins/class-tielabs-jetpack.php', true, true );

/**
 * Taqyeem
 *
 * By: TieLabs
 */
locate_template( 'framework/plugins/class-tielabs-taqyeem.php', true, true );

 /**
  * Instant Articles for WP
  *
  * By: Automattic, Dekode, Facebook
  * https://wordpress.org/plugins/fb-instant-articles/
  */
locate_template( 'framework/plugins/class-tielabs-fbinstant-articles.php', true, true );

 /**
  * Cryptocurrency All-in-One
  * WP Ultimate Crypto
  *
  */
locate_template( 'framework/plugins/crypto.php', true, true );
