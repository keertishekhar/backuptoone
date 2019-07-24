<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'custom_post' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ff?Me6h{_}ltjT~*koUqrj#skGs^Ff%Mf-JsR| _tO9EN>JKN;.~w,Jt-/PlKnxd' );
define( 'SECURE_AUTH_KEY',  'DPRO;aO*?)m%>N*W.k|EIL|mx=kyAW%$lll 69bwI:34s(GYLZqf%o 8Dk%X*W%v' );
define( 'LOGGED_IN_KEY',    '}V`*%Rxe:CgXzbjQK,(vx;nj%$^L+?vCeP}tec_UBDpVR-APNdrtW#`atf5iG8$-' );
define( 'NONCE_KEY',        '`(fYZ#@U{j=2d(e8hiK^6z+=ycc)VRV8Gyb0kc,16&u 07wp;=qAm~OcOMQ%vnbh' );
define( 'AUTH_SALT',        'X+Qxn**#Aw9SK7Ic_K7bMSTXSmD^--~Q?&:T9ex^QNlxWe~RlS1qw_o>4k5rk~ {' );
define( 'SECURE_AUTH_SALT', '+eZL@TqP#%A$i%Y9YLmTftns=/LbV]2i v7^Q#7_@{#ubnY5lZ?w<+IXlufEgGb7' );
define( 'LOGGED_IN_SALT',   'P .;0kP+D QZRkjb3aF]FID;^KX*tu,15 v}mg>f+KxnhFk9U2[F BMe:?<$4t[M' );
define( 'NONCE_SALT',       'bkwKu9`Cz9r#/fM#c3q*GVn`kyzqHVZ$Ms2i<|}Rr.K,g_7.^-L#B~jw2W7a]A9{' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
