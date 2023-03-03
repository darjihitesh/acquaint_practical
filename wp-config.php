<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hitesh-practical' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'XbtM-q:SxP7>]*LK]S/s>ki+e<9<6@9$bX3ye-M<QVFd5?rGzop5*O=u,uC^&?p?' );
define( 'SECURE_AUTH_KEY',  'WgW}J-&^+mZ_tRT>8^L&OKp%*4@_d #}uL?MHv|1-V}mH10^2aXT`7iF/I[kvvv>' );
define( 'LOGGED_IN_KEY',    ' QFB,oX,Dr_W5BR~ZIIRv:503y3=ysP]#U5F#Yp%nS,,]$u2YhieEi}_xZq7MH&Z' );
define( 'NONCE_KEY',        'SW_Rw&<1vhz,]ki$Ps(5L;#=H{oP/Sa^nqdG)uZ*<72X}Rsd(ERx4b@hFH-6qe?^' );
define( 'AUTH_SALT',        '@1-XRz$_VQZvcxf<I[k1EAT3/Z>O!=4Ve?b1u/RZ$A_-27h7Xc[.*N ?[9P06rFP' );
define( 'SECURE_AUTH_SALT', 'br@;.)QWqDiwNAeuu@m`$z_T5*Plg-0qMQT$i#DJ&Hc!Qu!b%aq_c{Xm1e3y=&*<' );
define( 'LOGGED_IN_SALT',   '2XFnK}$6zLt{&H9_^As=nxl@F@yUW5@,qjr.sI}*yh|&4*p6-V9CnV!K5)rP>Gb)' );
define( 'NONCE_SALT',       ':xXB,vvo>K=-s;3fUQ$}5x(5n}:o]QoN{o;pU39jwG,2CaEs922}e-YeYF#4 4Fz' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define( 'WP_DEBUG_LOG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
