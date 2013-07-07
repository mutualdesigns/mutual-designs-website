<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mutualdev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'mutualdesigns');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'g!tI=(<av}U!Jt9KO,`w}B3H=_[MtG<<uR4/1A2;u9CJ&!vR%3ZD odoF6,,v-i~');
define('SECURE_AUTH_KEY',  'BA@XDO]y>#uJh4{,LjpHG]a%^V9~TzwqkHY{zIZITh0vCkq-+WtW0e(/Xci*e6<E');
define('LOGGED_IN_KEY',    'LOHq5^*SY5YbH(6T7Io<jUsz8K%},}tL-W*O5Cd-F&lOvD5M4UhL)`qA2dhz(7XL');
define('NONCE_KEY',        'dIo@&U]Rx2:+zo&MMf<}9n9fQnRLw!flw|_^+w%ztmZr(P*Up.bvn6>dpAf.Q~4<');
define('AUTH_SALT',        'Aua=*j?I_|N;Hn$U?TKS$Spoe;fDs+,B|^jMB93Ld#?[mJL*KbIUqk,o8H3$2Jlq');
define('SECURE_AUTH_SALT', 'vNFKb8D1C?#LxI-0mG+!o(IMWSwHR*BAN|;PO&4a?u@oqN8ZH|Hxo.DbK#PD[*]y');
define('LOGGED_IN_SALT',   'b(p]L)f?;ZC({%=LCoaF9Cogho1Xw%UP@w{(yXP3R >;IJ4;k&|4(YjXvVSf5BT/');
define('NONCE_SALT',       'MJw#GG6kI:/d~!F!1m1Je [>>+Z+ZR{xn>9M8Ny3`=4d-XTZ~`j8$(;(u1&R&r*v');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mu_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');
define("WP_CACHE", true);

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define('WP_ALLOW_MULTISITE', true);
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'mutual.dev' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

?>
