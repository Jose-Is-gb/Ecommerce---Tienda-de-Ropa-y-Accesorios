<?php
/**
 * Configuración de WordPress para cPanel (jonilux.mrmendz.com.pe)
 */

// Conexión a la base de datos de cPanel.
define( 'DB_NAME', 'mrmendzcom_tienda_wp' );
define( 'DB_USER', 'mrmendzcom_tienda_user' ); // Pon aquí tu usuario MySQL de cPanel
define( 'DB_PASSWORD', 'i~puff?.~QuW%bMj' );     // Pon aquí la contraseña que creaste
define( 'DB_HOST', 'localhost' );

// Codificación de la base de datos (coincide con tu archivo .sql).
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// Claves de autenticación y seguridad.
define( 'AUTH_KEY',         'fbae48d96b2cf142d2fadbb22e7dfa589979c383bbd5e6ec0706d0e0e6cb6195' );
define( 'SECURE_AUTH_KEY',  'f7ed152dbcd782e4391e24638ba29b5035ba47c8968844b5fef33ba12ee965b8' );
define( 'LOGGED_IN_KEY',    'dfbf09ecfe38db2ba86a411131d4517a156e8b0b992f769f82d019041647ff56' );
define( 'NONCE_KEY',        '9e70e4a67fa673c185521572d9fbae8c79e533f4eca6f4765e440f1183045986' );
define( 'AUTH_SALT',        'e610882209d88bf695187124ecc36058b206f476b9e04ac05662b2ac169da9dc' );
define( 'SECURE_AUTH_SALT', '3d7453d213f56dbb185d924dd42bed6c36d58d4191782785e908d6ad3355df2a' );
define( 'LOGGED_IN_SALT',   'da552cf265ffa40f07dde2186acf9cddbfddcf434fd3ad5743715a96b6606eee' );
define( 'NONCE_SALT',       '573f9d276136c568f8b383ce07f01ddb58116dd4b84f793418f97630720d547c' );

// Prefijo de las tablas existentes en tu base de datos (con guiones bajos).
$table_prefix = 'wp_';

// Depuración.
define( 'WP_DEBUG', false );

// Ruta absoluta a la carpeta de WordPress (con __DIR__).
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

// Cargar WordPress.
require_once ABSPATH . 'wp-settings.php';
