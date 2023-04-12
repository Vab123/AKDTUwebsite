<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
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
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/var/www/akdtu.dk/public_html/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'akdtu_dk_db');

/** MySQL database username */
define('DB_USER', 'akdtu_dk');

/** MySQL database password */
define('DB_PASSWORD', 'te62n9pd');

/** MySQL hostname */
define('DB_HOST', 'mysql78.unoeuro.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'v;HLzK7g/x9oA:Z,`hVag)A.m_a}]>_:S<t}[h~:7]]O<y`7~S$*rYGx_|-l(df@');
define('SECURE_AUTH_KEY',  '%4 yBL%H._e=Z{&xWfBRa>KUPtLi 5Tm.[h,_r}? qs!$x= b~x#:<y@55W,:`&$');
define('LOGGED_IN_KEY',    '/h8I*sYVI/f/@B3Hb?>kZ|Q2% o RhY9Cc/rq|ofX~.!&/uB/WzeoIc8~R(4)tm6');
define('NONCE_KEY',        'wYG/fII$~N[!%J(@Zj=dB5vYy?-u=Eyb)we:lEGxMdJp vsX}?WH&i#f2-)&YEB$');
define('AUTH_SALT',        '}>&+ $1JAghj12l;7h_^0+<_[/2a,$N4R#qd.]7p,k,8]D#{X;:m&vII*/Zk/MF1');
define('SECURE_AUTH_SALT', '~sxt8xUUUNqNU*8z<i~JY=iA(^>1@ Wc6Zg~c^4P.2u`X/!co*}#5KCtNDqh<G.[');
define('LOGGED_IN_SALT',   'D0qv$&iuFp1Xvf4Z`JpEcKsI(Q-}TKZ/(!Q]8+;+ovD>tCWcjF)==F=_n9jUf_jZ');
define('NONCE_SALT',       'K3vl,N{b2K4An}c0[J8]jCF&Zb&Um/9s!gQIqQ*5O~`N>HMn4(e&bhA*<>&~[Lqm');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );

// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );