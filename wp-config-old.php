<?php
/** 
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
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
define('DB_NAME', 'mutual_wrdprss');

/** MySQL database username */
define('DB_USER', 'mutual_mdpress');

/** MySQL database password */
define('DB_PASSWORD', '}A,Mhw~M~8*N');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',        'Lt;h|xms.uW*>>3$z2UNsDqHy5N&{7QCqEG}A_[s{e||6CMTQ!uihLH96%UQ9(GE');
define('SECURE_AUTH_KEY', '73`ot~Vj$&Y|) eZ8MOB}v4Ac1/p5hK:t.%&=l+UGr_r0L>Z|G}wL*l)4f[FJxrm');
define('LOGGED_IN_KEY',   '{cJFVBU_GU#S|]v|-qyZ|>skwC8R)aVhLESrB.4zq8Jt0mld}:Sj SM(BB)!.ILg');
define('NONCE_KEY',       ',|9bqmZ[3[L-${ Kd>Vui0 7 n|4 Q+.zB/}MD-/.-s%FcYuLU+EQfBU~vR.OqB.');
define( 'AUTH_SALT', '4~4n7d)FNRyM+ qI1svPf;;8VHh}yP0o}tmLXRb+RJ6FAUm1q`TuHiE6+W3J8:fh' );
define( 'SECURE_AUTH_SALT', 'e?L*p6y+3kQEo[RNsLK++)N8-D9xMer}u~ZNIZal+*)cEk7<S~R-gX}j@Yj1KV]Y' );
define( 'LOGGED_IN_SALT', '^SBY{8lI?ul:ZBLY635j;(`16kZoOV7hrz~|7I|{.-^M1tHtMZSeMQL^60ZXD*M6' );
define( 'NONCE_SALT', '=549wF@tgpv+L0:?fn(h*aQgIsUq3B7c;U6Z*Uara4o]1AW`E-nn;M[|B[rBCEYr' );
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'md_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

define('WP_ALLOW_MULTISITE', true);
define( 'SUBDOMAIN_INSTALL', false );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'mutual-designs.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

