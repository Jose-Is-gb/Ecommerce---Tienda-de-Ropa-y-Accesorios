<?php
/**
 * Plugin Name: JONILUX - URLs dinamicas para Cloudflare Quick Tunnel
 * Description: Usa el host HTTPS del Quick Tunnel sin cambiar las URLs guardadas en la base de datos.
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

/**
 * Devuelve el host del tunel solo para solicitudes HTTPS de Quick Tunnel.
 * No confia en un Host arbitrario ni activa el modo tunel en el sitio local.
 */
function jonilux_cloudflare_quick_tunnel_host() {
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower(trim($_SERVER['HTTP_HOST'])) : '';
    $host = preg_replace('/:\\d+$/', '', $host);

    if (!preg_match('/^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.trycloudflare\\.com$/D', $host)) {
        return '';
    }

    // El Quick Tunnel local conecta Apache desde loopback; no aceptar cabeceras
    // falsificadas por clientes que alcancen Apache directamente en la LAN.
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if (!in_array($remote_addr, array('127.0.0.1', '::1'), true)) {
        return '';
    }

    $forwarded_proto = '';
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $forwarded_proto = strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
    }

    $cloudflare_visitor = isset($_SERVER['HTTP_CF_VISITOR']) ? $_SERVER['HTTP_CF_VISITOR'] : '';
    $cloudflare_https = is_string($cloudflare_visitor)
        && preg_match('/"scheme"\\s*:\\s*"https"/i', $cloudflare_visitor);

    if ($forwarded_proto !== 'https' && !$cloudflare_https) {
        return '';
    }

    return $host;
}

$jonilux_quick_tunnel_host = jonilux_cloudflare_quick_tunnel_host();

if ($jonilux_quick_tunnel_host !== '') {
    // Cloudflare termina HTTPS en el borde y conecta por HTTP con Apache local.
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = '443';

    /**
     * Conserva el subdirectorio configurado en WordPress y cambia solo el origen.
     */
    $jonilux_quick_tunnel_url = function ($url) use ($jonilux_quick_tunnel_host) {
        if (!is_string($url) || $url === '') {
            return $url;
        }

        $path = function_exists('wp_parse_url')
            ? wp_parse_url($url, PHP_URL_PATH)
            : parse_url($url, PHP_URL_PATH);

        if (!is_string($path)) {
            $path = '';
        }

        return 'https://' . $jonilux_quick_tunnel_host . untrailingslashit($path);
    };

    // Algunas instalaciones definen WP_CONTENT_URL en wp-config.php. WordPress
    // usa esa constante para temas, plugins y uploads, sin pasar por siteurl.
    $jonilux_quick_tunnel_resource_url = function ($url) use ($jonilux_quick_tunnel_host) {
        if (!is_string($url) || $url === '') {
            return $url;
        }

        $parts = function_exists('wp_parse_url') ? wp_parse_url($url) : parse_url($url);
        if (!is_array($parts) || empty($parts['host'])) {
            return $url;
        }

        $resource_host = strtolower($parts['host']);
        if (!in_array($resource_host, array('localhost', '127.0.0.1', '::1'), true)) {
            return $url;
        }

        $path = isset($parts['path']) ? $parts['path'] : '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return 'https://' . $jonilux_quick_tunnel_host . $path . $query . $fragment;
    };

    add_filter('option_home', $jonilux_quick_tunnel_url, 99);
    add_filter('option_siteurl', $jonilux_quick_tunnel_url, 99);

    // Evita que Ajustes > Generales persista la URL temporal al guardar desde el tunel.
    $jonilux_preserve_database_url = function ($new_value, $old_value) {
        return $old_value;
    };
    add_filter('pre_update_option_home', $jonilux_preserve_database_url, 10, 2);
    add_filter('pre_update_option_siteurl', $jonilux_preserve_database_url, 10, 2);

    add_filter('content_url', $jonilux_quick_tunnel_resource_url, 99);
    add_filter('plugins_url', $jonilux_quick_tunnel_resource_url, 99);
    add_filter('stylesheet_directory_uri', $jonilux_quick_tunnel_resource_url, 99);
    add_filter('template_directory_uri', $jonilux_quick_tunnel_resource_url, 99);
    add_filter('style_loader_src', $jonilux_quick_tunnel_resource_url, 99);
    add_filter('script_loader_src', $jonilux_quick_tunnel_resource_url, 99);

    // _wp_upload_dir() usa WP_CONTENT_URL directamente en esta instalación;
    // filtra también las URLs calculadas de uploads y las variantes responsive.
    $jonilux_quick_tunnel_upload_dir = function ($uploads) use ($jonilux_quick_tunnel_resource_url) {
        if (!is_array($uploads)) {
            return $uploads;
        }

        foreach (array('baseurl', 'url') as $key) {
            if (isset($uploads[$key])) {
                $uploads[$key] = $jonilux_quick_tunnel_resource_url($uploads[$key]);
            }
        }

        return $uploads;
    };
    add_filter('upload_dir', $jonilux_quick_tunnel_upload_dir, 1000);

    $jonilux_quick_tunnel_attachment_image = function ($image) use ($jonilux_quick_tunnel_resource_url) {
        if (is_array($image) && isset($image[0])) {
            $image[0] = $jonilux_quick_tunnel_resource_url($image[0]);
        }

        return $image;
    };
    add_filter('wp_get_attachment_url', $jonilux_quick_tunnel_resource_url, 1000);
    add_filter('wp_get_attachment_image_src', $jonilux_quick_tunnel_attachment_image, 1000);

    $jonilux_quick_tunnel_srcset = function ($sources) use ($jonilux_quick_tunnel_resource_url) {
        if (is_array($sources)) {
            foreach ($sources as &$source) {
                if (isset($source['url'])) {
                    $source['url'] = $jonilux_quick_tunnel_resource_url($source['url']);
                }
            }
            unset($source);
        }

        return $sources;
    };
    add_filter('wp_calculate_image_srcset', $jonilux_quick_tunnel_srcset, 1000);
}
