<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp' );

/** MySQL database username */
define( 'DB_USER', 'admin' );

/** MySQL database password */
define( 'DB_PASSWORD', 'kolo4life' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'Lp>3=Db&`3jWHvOpI7b~I$>Tl2$v|{FDe-ZP-fe;DhfI@D1%VJ:ufF7H%bQc?*3|' );
define( 'SECURE_AUTH_KEY',  '?8y)kx*|roHqX2sMru!Z`x}%}F4(+zrZ_2.>k_x(,o_o-!n$i1TC6ip{>%!IPdg ' );
define( 'LOGGED_IN_KEY',    'lcaTm S7yEe{(eh`f)T|kaCA,lW)`4DICjb*Tf1_xoyOz^|06IR,pO:6g?/j>Hz?' );
define( 'NONCE_KEY',        'igi~f,j{u`0gsHH[GH|QLpzE)B/0YQ]fw-xLhbG@*l%2fds!{c2*47sGb_6+Rh~l' );
define( 'AUTH_SALT',        'iASf-X1<6b>wU3%CI(>.Ck#TLhkg;D!Hr?^!q4!zB[k)OC!j!wG`Ir/*P5n=WE-g' );
define( 'SECURE_AUTH_SALT', 'c]M vxbtQCJ3Z4vC|K}BD^<RL/&PELHTj]1FN#;-D E>5Y+p(_7Cd{KvG6E*tec%' );
define( 'LOGGED_IN_SALT',   'bB+0k0<9l*=FD-[b7.;2+QMTJNtN^)*Ibzi`=3fHp(}Wvp.!;Xy%K%L4NkxyJaJR' );
define( 'NONCE_SALT',       '4|b(BTHhC 3an~M.n!teKRc1U7.QIo.@*}p9=x5M#}icG_n,$:2f#J3GW|<.iSWg' );

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
