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
define( 'DB_NAME', 'marconew' );

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
define( 'AUTH_KEY',         'YpY#Nti6%9Tggtz,3[5rspFjyK;B=2&{OW9<S+E6h6LV7HzuVsd3V?}fC@!NT}Qk' );
define( 'SECURE_AUTH_KEY',  'CpPxd.(@]Y9ko.uA*jyyD0]Q-5rWn=slbP5y/%E<#b]GH/H[3;.I/T3M!;&k1;+8' );
define( 'LOGGED_IN_KEY',    '_U/e;.@/p+jGH!tZ%PXs g.t9wM`FH_wH52c0Dm$l+>_.^ .z]BK~W.P%^YkGLcK' );
define( 'NONCE_KEY',        '`PA,%72c)@$R0k@GO(/%1D|CHC~tQzWJ}lnQS{1<Lk}g> 4^X|g]0AepSm5Mzhxj' );
define( 'AUTH_SALT',        '~w2S3Qdx9A0|1{,ryS&;liug5N8qGxK>}zpU%X+ NN%EkcLIiPV{u4V2|7kGSD!4' );
define( 'SECURE_AUTH_SALT', '.l=dCHa)m-76w:m{SnBHT^udHU R~_^H><6bxRHQ1DNq%Zc}t[C_qDJzQ.EA`Pi.' );
define( 'LOGGED_IN_SALT',   'y)k N&Rn),Rc^r@);[n:hkST?gglR9:^nI3smB.Qb98!2;tq-Jc-:rTB+j&30zZK' );
define( 'NONCE_SALT',       'lqx7_Dxsd+Mp$A.OX:TbB}2v~W0[))nKTHq{$#amraPt6<f}x8cjWfoO])#A!l2d' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'mr_';

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
