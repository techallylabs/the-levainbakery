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
define( 'DB_NAME', 'i8235382_wp9' );

/** Database username */
define( 'DB_USER', 'i8235382_wp9' );

/** Database password */
define( 'DB_PASSWORD', 'B.YjF9cNHXZRbq20AwP28' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'SLzOe4ThsMMtbTm16tyQUBfVmnVqFr2NGGNT4FYELw939i8NqJulXM3xKTkVHlXH');
define('SECURE_AUTH_KEY',  '0mwsTxbyYAcUagefouN85Dd5t5G9olw3iIjIGpll62pLqzSHVsMsxO7uNyyhUm5M');
define('LOGGED_IN_KEY',    'RjFH67cGxHvXUHbHKxzdenz9BeN8JqhBWti09nTTkwtTeG44XVvcuF5LvbN1cvuC');
define('NONCE_KEY',        '86webnRmg26ksStEevshat14eFAWVzT4HzH3BWAlZEhOlZzXwumDHsduQGRU2vMk');
define('AUTH_SALT',        'AasupniLtwL4uuzTqstemg2x7vyssO2wg8CBCt49mx8TuJCH10uNp78MCMFlJw2x');
define('SECURE_AUTH_SALT', 'p8RYQZpu1QPEvQpvm1bfhLO4a8BwYtaiYkzHjDMCxIW0CBLl0Hf8Mwf7uHPA7L9A');
define('LOGGED_IN_SALT',   'qqHkrNtmqmAFg6zWeK1T1iEiJhv9Iuct2CQOg8tZ6CPhe9SoN6BYACk2HdW7w0yM');
define('NONCE_SALT',       'ZFa8cAwUwgt0H1zXMMlyBpmWr6redt8775v7fKVTSrV7pfJJbyHp6TWigHTU6Qst');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


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
