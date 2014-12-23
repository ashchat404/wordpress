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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'i)f;YJTU}EB$#b.(?Pn+)R9!%Mzr =wBO6CE;{|RvOp=LGV0b?Uvmgm`<Io{5C(:');
define('SECURE_AUTH_KEY',  'MN|`_JuDqGl6#.VO;PO}quEQN|3ex1^n*H<z]O36-K x-T-Hdi>geGFSMxFu{QTb');
define('LOGGED_IN_KEY',    ']7+ ?`u|s5LVDq%&yFty?e$[U|cF(%|x)%K:+I=D^MN[`%4i<ZEvcVv;@jRLk#~/');
define('NONCE_KEY',        'EeS9ORLBBU+PW;mCHJ]xTHy0^HSLFb~BMW+*=jip=kmTm@>$ibu@%+xUlq|Gc-De');
define('AUTH_SALT',        '[`KN[9ScWfSTS{2NK#(i,@`PZ#ChI+y5| !]cFwTmaXAH#FP-Fu3[/&]gK:[GKl&');
define('SECURE_AUTH_SALT', '%-Z;fL::He9HWm6MyI(=p@0 v*N`c+iy<>S_,|m.1(Fn,-:8%V&1-+z$|I*-qsZu');
define('LOGGED_IN_SALT',   'go%3DrS*-^BWOh=sMraG3QZU,?`j&P/sRG+h|%9Gv.W:[v9XU[g5k0O.A^ o1kDN');
define('NONCE_SALT',       'ehL7x<SeKwoykxm1I0|=:SrI?D2eWxRD6WA@4uM1knY:zO+Cm:t8dv!{H9z#a4dy');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
