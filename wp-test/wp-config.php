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
define( 'DB_NAME', 'wptest' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WP_ALLOW_MULTISITE', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '7],dH}q ^S8+l;m1sP+W $di_]$<hAUA8FumnhE/$?eoz5Zl+W;mcGmp_z`8K<Cv' );
define( 'SECURE_AUTH_KEY',  'iWEWtN vF4RvgIcj-;fj{>NmD_,t~hs0UfE`)#N7S)_6FO*@xA)/_bU@Ij|ol7Mg' );
define( 'LOGGED_IN_KEY',    'U]Ye$<68*>N%f0!H](.GBuo2`9[sf}VW>U@Vh6=BgtlYhAxXy@F}MUN.?|h]9!0J' );
define( 'NONCE_KEY',        '; (w8{EFNO@d %Ws|g$|PHp[$(OO~n0t>k>%jtvoJ|j`Eq{A4Ys[`b5`hQrg~Hq~' );
define( 'AUTH_SALT',        'J4RW;`r47miP+n1+DJgvu`%7~5;-gaxsLu]5pN!* o;u=f)AHg>nPzt2riGTbIQj' );
define( 'SECURE_AUTH_SALT', 'f:2J)KR7FJ;%*,w/Sg_vS}HgqqVd]q:a!py y^%3MB/U$zi*!cYsh5y2n@VOsf%Q' );
define( 'LOGGED_IN_SALT',   'ix>/}($Nuv9zu-#ys;/).qVRsSS`%^}f&%_SPX7<zg;/aKO3d.,SnbQU0~Bf_m*H' );
define( 'NONCE_SALT',       'tzrZpq::5J}JT]khngpT}+&+|87Oyn3qmWI F{T9}||a.G]1Ixvg_,B{]bfJlC_u' );

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
