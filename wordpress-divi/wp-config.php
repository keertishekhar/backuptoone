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
define( 'DB_NAME', 'divi' );

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
define( 'AUTH_KEY',         'FFK{k7Fif~+e`nupf>ZJdjnxD6Qr,t(C9_Ob5oJ5f.1LE)6<m+;i=#`KEfN,F$aX' );
define( 'SECURE_AUTH_KEY',  '^E|Q4q`1B<Xpz}MGZpkP7d?]J{ZH2r{/.N~$:- -^b7a`O>;(?G*VJMx%wA=u:5s' );
define( 'LOGGED_IN_KEY',    'o-bB~$Z#.`psA-rh~f ;5^R0#:;K1X0)yMjA6W<J-+_dl]%v{Mj{[[:u/wx+:N9X' );
define( 'NONCE_KEY',        '$b1F[!M1Rf{KCy$>Y*|cy1S#ZoNHmZZ*g?|wz0d}=z!-[VZh`E[G`Ez4=A{f+(4n' );
define( 'AUTH_SALT',        ')ff6ce`-]ynX2.F@ $__Y* Pu[{UF!ADRI/rm`_|q$T^:36504[HsG?nb!tk_H -' );
define( 'SECURE_AUTH_SALT', 'gFcqLU{H;-W01~Pb0c]A:44)(9wR:`-JqU;-9jTzdv;h_CBpC~q&c1MqlL<P^$kW' );
define( 'LOGGED_IN_SALT',   'I6]MQCIS<68Em]KXO2GuV+i-jyql:aKL$<bg@A$Hn_G68Iz-oz.@]oK|;taTKdN3' );
define( 'NONCE_SALT',       '(mjp*Z #K#Ui9qGW79FN_5s#ESp,Fzxumj:iwJFn+=N[xw6ZfD&tgRxC41=Q{m^2' );

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
