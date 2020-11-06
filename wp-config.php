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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
if(strstr($_SERVER['SERVER_NAME'], 'localhost')) {
	define( 'DB_NAME', 'test1' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', '' );
	define( 'DB_HOST', 'localhost' );
} else {
	define( 'DB_NAME', 'test1' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', '' );
	define( 'DB_HOST', 'localhost' );
}


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
define( 'AUTH_KEY',         '2[Fmb>KdP]=b-(rPRStXoke%@9s4M!N`%E&+fG0?i]Wps`W4W[4GAAk!rF=R*FJq' );
define( 'SECURE_AUTH_KEY',  'p:-=+%t!7%!o3N@GOap!z:;GdnTrRO_,y{mDY}B+f`NTj?Mb9!gV}i&_sv~msg?d' );
define( 'LOGGED_IN_KEY',    'p7ozXX%0t3pJb_H@=MS 6,:3=eHr={2cIQHyEhg=-jo67ZU)P.^q$#_jP?do8|(Q' );
define( 'NONCE_KEY',        '|+tk({P;c^zK;9y@S7w82UV)D/X[h8co<Xju9oIQtmheZ8Bp/6Y?UG=~)_VRHfy3' );
define( 'AUTH_SALT',        '+m=}K@[Et6<b9it{Zq~!iTgH%>yc[rzXWl`2@Kn%Xvu.|IsGZY6JC,&@:(kqev{B' );
define( 'SECURE_AUTH_SALT', 'q3<hLDQ7t5u~(3pzhB,ZNt<(2`E*RKq~|OW^oCyv?Yi6/Q s&3CvTz63vP$s--8}' );
define( 'LOGGED_IN_SALT',   'UbnALZJHMixY]WnSziI&,s$7];khTwjp2?>N<)4;^x29}6O3<Yw6~:V3QR^MdKTf' );
define( 'NONCE_SALT',       '9|d ZZ.%ehkp@3i~MY0o5)O]*yD[E]!Y->eRuVGgToN2au_U0>>0MUfQ^n.)&6<A' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define( 'FS_METHOD', 'direct' );
