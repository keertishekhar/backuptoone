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
define( 'DB_NAME', 'wordpress_0' );

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
define( 'AUTH_KEY',         'w&>fGT{G=8(5Vng-Urd*Sr!A@tanFK_N0A&6Kg#FppS-0G2b+U7Vb y81`&kk+[x' );
define( 'SECURE_AUTH_KEY',  'I^./mv>{H/Ik,`N?v=~~vVbUBs}qFW?yB1Zx2o&]`m`U+v#tk*p(zri}y%o(^cGk' );
define( 'LOGGED_IN_KEY',    'ww1{EHJ.PW#GHqMe:]$#utGc7rgt*}n2k+CgSRueg9M>*;Q``qe~eKS>Y7:9h=1`' );
define( 'NONCE_KEY',        '5uhs$@#,WVQ?*H%U_AzY|@#!lhu&!2?iZ{Pk2t1^{P3Hp.Oy8!A_Y.8zFdi%*)^g' );
define( 'AUTH_SALT',        'gs;4!y{OCAkvBP.RJCYkh],dl_1` Oy>^|i6>YC2D$VZuse~h@.ly27B28?(eQ O' );
define( 'SECURE_AUTH_SALT', 'n:kvuC*C0k,fhE9IwE`iKSg|u10o@E<}]a6F6zg<Er#5!3VPc|?r&Rc1gZCe8BKk' );
define( 'LOGGED_IN_SALT',   '5XFEV7$!4g5Ikrt;MZd0}3!To.6VrWlgj_dNB)NRS+ihH<3vVf@PT`yQ7|_yl9eV' );
define( 'NONCE_SALT',       '.FLJ;$xr~oq$F7/x4]k}fiX#2s!L5oQ:!T[86L]l?s8Xl*_zsCV[yS[T:syi_Ry*' );

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
