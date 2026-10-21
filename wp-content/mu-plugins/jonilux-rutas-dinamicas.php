<?php
/**
 * Plugin Name: JONILUX – Rutas Dinámicas y Corrección de Imágenes
 * Description: Hace que el sitio y todas las imágenes de productos funcionen automáticamente en la computadora de cualquier compañero (XAMPP/WAMP), sin importar el nombre de la carpeta, el puerto ni el host.
 * Author: JONILUX Team
 * Version: 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * 1. Detección automática y dinámica de la URL del sitio y home
 */
function jonilux_get_dynamic_base_url() {
    if (empty($_SERVER['HTTP_HOST'])) {
        return false;
    }

    $is_https = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    );

    $protocol = $is_https ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];

    // Determinar la subcarpeta relativa desde SCRIPT_NAME o REQUEST_URI
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $clean_script = preg_replace('#/(wp-admin|wp-content|wp-includes|wp-json|index\.php).*$#i', '', $script);
    $folder = trim(dirname($clean_script), '/\\');

    $subpath = ($folder === '' || $folder === '.') ? '' : '/' . str_replace('\\', '/', $folder);

    return rtrim($protocol . $host . $subpath, '/');
}

// Sobrescribir dinámicamente siteurl y home si no están forzados en wp-config
if (!defined('WP_SITEURL')) {
    add_filter('pre_option_siteurl', function ($value) {
        $dynamic = jonilux_get_dynamic_base_url();
        return $dynamic ? $dynamic : $value;
    }, 1);
}

if (!defined('WP_HOME')) {
    add_filter('pre_option_home', function ($value) {
        $dynamic = jonilux_get_dynamic_base_url();
        return $dynamic ? $dynamic : $value;
    }, 1);
}

/**
 * 2. Normalizador de URLs de uploads:
 * Convierte cualquier URL antigua (ej: http://localhost/wordpress/wp-content/uploads/...)
 * a la URL actual del entorno en ejecución.
 */
function jonilux_fix_upload_url($url) {
    if (empty($url) || !is_string($url)) {
        return $url;
    }

    if (strpos($url, '/wp-content/uploads/') !== false) {
        $parts = explode('/wp-content/uploads/', $url);
        $relative = end($parts);
        return content_url('/uploads/' . ltrim($relative, '/'));
    }

    return $url;
}

// Filtro para URLs de adjuntos
add_filter('wp_get_attachment_url', 'jonilux_fix_upload_url', 999);

// Filtro para fuentes de imágenes (thumbnail, medium, large, full)
add_filter('wp_get_attachment_image_src', function ($image) {
    if (is_array($image) && !empty($image[0]) && is_string($image[0])) {
        $image[0] = jonilux_fix_upload_url($image[0]);
    }
    return $image;
}, 999);

// Filtro para srcset responsivo
add_filter('wp_calculate_image_srcset', function ($sources) {
    if (is_array($sources)) {
        foreach ($sources as &$src) {
            if (isset($src['url']) && is_string($src['url'])) {
                $src['url'] = jonilux_fix_upload_url($src['url']);
            }
        }
    }
    return $sources;
}, 999);

// Filtro para el directorio base de uploads
add_filter('upload_dir', function ($uploads) {
    if (is_array($uploads)) {
        $uploads['baseurl'] = content_url('/uploads');
        if (isset($uploads['subdir'])) {
            $uploads['url'] = content_url('/uploads' . $uploads['subdir']);
        }
    }
    return $uploads;
}, 999);

/**
 * 3. Normalizador de URLs incrustadas en HTML o bloques
 */
function jonilux_fix_html_upload_urls($html) {
    if (empty($html) || !is_string($html)) {
        return $html;
    }
    $target_base = content_url('/uploads/');
    return preg_replace('#https?://[^/\"\'\s<>]+/.*?/wp-content/uploads/#i', $target_base, $html);
}

add_filter('the_content', 'jonilux_fix_html_upload_urls', 999);
add_filter('render_block', 'jonilux_fix_html_upload_urls', 999);
add_filter('post_thumbnail_html', 'jonilux_fix_html_upload_urls', 999);
add_filter('woocommerce_single_product_image_thumbnail_html', 'jonilux_fix_html_upload_urls', 999);
