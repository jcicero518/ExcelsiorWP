<?php


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db149378_uberflip');

/** MySQL database username */
define('DB_USER', 'db149378_uf');

/** MySQL database password */
define('DB_PASSWORD', 'C3l$i0hF03mzFgssw?');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s149378.gridserver.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         '5z<~W@/|9kH?CLQO=e~X#kzVBK0g~&yFG7bhM*7v2#;k 4(V&K/x_Wej{RC/^}gi');
define('SECURE_AUTH_KEY',  'IK@:LG_dpR%@L/[zH0)AnBRgU-5EP8)>Ym^+EoXT2{!)5yY=dMYHe`PfR;0E)-`Y');
define('LOGGED_IN_KEY',    '4ICEhlST?2R_*>-CNME$A0lgnL6)@^/5XF^Ps}E4ehh@omAJAD4n^ExrJ!AX3K+m');
define('NONCE_KEY',        'fhYxvKnT`w:?O)d/IPjHt:%e(?x3.iC&o[2`G+m*Q:yc-@+c5kZp+/8V`nL/^O#:');
define('AUTH_SALT',        'JnI>@c2!Q4EVVXqe#:ZCmIv9|-f;7vQHUO!+JVDKbB?6NxM|-@+%LC^mu4)x^h+#');
define('SECURE_AUTH_SALT', 'Q>W*4`C;dSYuk;J5dsm}YwmlK M&kO2@ cLSMUR;<C-?YPqmMOvE$!mi/o+gt{i-');
define('LOGGED_IN_SALT',   '@}+rmC+[Mil+D$~Rr_@d-*P;y[&-=c-+eaZQIAgH BDltjH:]Wj1KyA._e?}W+}Q');
define('NONCE_SALT',       '`nWg#`E=5#:|:*:MRo(|P}StY-qnED}lf6EfcO2b<|3JVa:2e~R3,UsbU7YTl)GG');


$table_prefix = 'wp_';


// Match any requests made via xip.io.
if ( isset( $_SERVER['HTTP_HOST'] ) && preg_match('/^(local.wordpress.)\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(.xip.io)\z/', $_SERVER['HTTP_HOST'] ) ) {
	define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
	define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );
}

define( 'WP_DEBUG', false );



/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
