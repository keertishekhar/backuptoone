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
define( 'DB_NAME', 'news_mag' );

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
define( 'AUTH_KEY',         'j>J|S/RPTqB)@XJwKA&if-$x-,Rfdd3|xdcrV!Yt9,n^_]0bg3-t*=TQ@F`}+g11' );
define( 'SECURE_AUTH_KEY',  'G-OcG0T1*v[@-{y?@=tRGXkcAPGS<HY!55DX6M3`@8B-RMU@qjlC9B01hJgc7-{:' );
define( 'LOGGED_IN_KEY',    'FJr-ec>Ca_J(e]6B)&$.Y4}#3dN0X^:J.YNRQHLrj+=Jb^n%OZk|syT_}k5BNQHz' );
define( 'NONCE_KEY',        '&<>V1F2q4>^*E0YP2 (RbV((nFq#e|]41N$6FF5eCdb>%)b81b^@s$R?PbR[qK_W' );
define( 'AUTH_SALT',        '@}gq<MTmR8D/RTkr6FUGfJs$~&N4HqqgASNV07`g$~e4;mC3q9LblM8fu.%-cqk-' );
define( 'SECURE_AUTH_SALT', ',?yYCq@dz#<4XUp3#p&}?BEgd47]Ty~*{c05:8C=t5`Ckap&e2<t*>$v$)Gn1yuk' );
define( 'LOGGED_IN_SALT',   'hX/?%l0w&485dF~Dx!6U8@7d+>Yl)wbD@d!90#%GnTToKe:mV:EFOcY?s#VG~;nY' );
define( 'NONCE_SALT',       'iuA+|~O0u_OiHi`p}A4F2LoY<5,@hhyN22@33}-1s<^mA/k7[]htlj6(W.w(~;V}' );

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
