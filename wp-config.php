<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tienda_wp' );

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
define( 'AUTH_KEY',         '<vm;^QPPN,Zj%;ZX^O=~Dm_56s(<in$@;:p*j1cnHQu^&4Y>93`1=bAbGVUT::[^' );
define( 'SECURE_AUTH_KEY',  'MhSFDz<cu~=sNZ*GuxvA_NvPk3)7gnv(=SxlT07)ktyT1R,?pc6b<KW7r ]?tDCv' );
define( 'LOGGED_IN_KEY',    '_a#0:t34iL>&i=ATJJ?`AI>-/H}U; Hx=S(CKLFlQ?d=;6sQd%)Wo}(KvF&S@^Wq' );
define( 'NONCE_KEY',        ')O{7!N}[{yA@{wuk9>7 SRnUoD!pt!P |?h9>^@h6(7`blRo+H~-;Z&73qeJ;EeQ' );
define( 'AUTH_SALT',        '+Bf:5r=f]~P+_z-7@qyeT2d_MSO,5;MT_0_z+u{sKB26#.^~w((LCQn|!uW&|f@l' );
define( 'SECURE_AUTH_SALT', '|1acCQHsqD?{Ny00(8vz@{Izj1h(woNsC8?$!I(C/d/X@4XpII0%3^6`p&PO;!|E' );
define( 'LOGGED_IN_SALT',   'q>D9D4!@~F}w@ 3;F(u~@AfS@Hu]=V;/GmAP@N8_.`|y#gg5G[;Fu!f%p9zS#&Pj' );
define( 'NONCE_SALT',       ' z2((PjR>=lAGG4V0k8FwHVp[`0d,p*X4({=7o1xUpf9LZkII7h{^n8x=NFE4si+' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
