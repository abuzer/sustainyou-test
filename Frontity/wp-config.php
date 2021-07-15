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
define( 'DB_NAME', 'mainsite' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'xQZ*7)sk,tI(j,f7R!]K7kSsBA; uILN9Z3N^n>q_+[=2bQjJ0D~uT3P15WY*eQF' );
define( 'SECURE_AUTH_KEY',  'gvI/esvo^&fjih&w0$LHo.Uy;Y}6NK</bV~uZ8[W$RM#sY5$yqGJRV.n7?`y`Pee' );
define( 'LOGGED_IN_KEY',    '^X{T0OfK%)y(]zGzuF#J^})^!P)[]e06p2V9[R4}UfPMc1A*-28v<,>]pIAj~IwB' );
define( 'NONCE_KEY',        '-6V1a_h+N~H)ZfxC#|<Nw)aG]qWXJiMG5[-||EQp0-]UfGW6iKEe&%u>q7<re[e4' );
define( 'AUTH_SALT',        '}j(XPq?sBbnnU 2$l=35XX[u6$Bryg]/W[y/vR_456N05>{I) ,M~6-LZ7U6/e(0' );
define( 'SECURE_AUTH_SALT', '@{!z^;QrHRdZ[G#S;4{[Q_KP>-E6pF/LOBydP j)N*(s0},Xa^Qg}ajGz]+2drZ@' );
define( 'LOGGED_IN_SALT',   '/ Z1FB)|c*`*c9MMz#Z@mzy>+h)^@#F?tDm/9B.8/; 1S|)elGwz|=+s=t&n;[v7' );
define( 'NONCE_SALT',       '%<er6hTp0*C1&lH05~QGn{UGhe;|/<=pfXm):{&?:qH(FM,&)n9-/>!ZUR4=X2px' );

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

// define( 'WP_ALLOW_MULTISITE', true );
// define('MULTISITE', true);
// define('SUBDOMAIN_INSTALL', false);
// define('DOMAIN_CURRENT_SITE', 'localhost');
// define('PATH_CURRENT_SITE', '/testsite/');
// define('SITE_ID_CURRENT_SITE', 1);
// define('BLOG_ID_CURRENT_SITE', 1);

// define( 'FRONTITY_SERVER', 'https://myfrontityserver.com' );
define('JWT_AUTH_SECRET_KEY', 'your-top-secret-key');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
