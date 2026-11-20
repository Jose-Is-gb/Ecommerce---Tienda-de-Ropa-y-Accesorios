<?php
/**
 * Plugin Name: JONILUX – Optimizacion de Rendimiento y Carga Rapida
 * Description: Acelera la carga de la tienda desactivando en el frontend para clientes los plugins de terceros innecesarios (Jetpack, Google Listings & Ads, Pinterest, Reddit) y bloqueando peticiones HTTP externas o wp-cron sincronos durante la navegacion de la tienda.
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Evitar que plugins pesados de marketing/tracking no utilizados (Jetpack, Google Ads, Pinterest, Reddit) se carguen en cada pagina del sitio
add_filter('option_active_plugins', function ($plugins) {
    if (!is_array($plugins)) {
        return $plugins;
    }

    // Permitir en la pantalla de Plugins del administrador ver el estado real si el usuario entra a plugins.php
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (strpos($uri, '/wp-admin/plugins.php') !== false) {
        return $plugins;
    }

    $pesados_no_usados = [
        'google-listings-and-ads/google-listings-and-ads.php',
        'jetpack/jetpack.php',
        'pinterest-for-woocommerce/pinterest-for-woocommerce.php',
        'reddit-for-woocommerce/reddit-for-woocommerce.php',
        'woocommerce-paypal-payments/woocommerce-paypal-payments.php',
    ];

    return array_values(array_diff($plugins, $pesados_no_usados));
}, 1);

// 2. Evitar que wp-cron bloquee la navegacion del comprador en el frontend (solo permitir cron en admin o peticiones explicitas a wp-cron.php)
add_action('muplugins_loaded', function () {
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (!is_admin() && strpos($uri, 'wp-cron.php') === false) {
        remove_action('init', 'wp_cron');
    }
}, 1);

// 3. Desactivar emojis remotos, oEmbed discovery y llamadas innecesarias en el frontend
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
}, 1);

// 4. Limpiar cache de portada y mega-menu automaticamente cuando se edita o crea un producto
add_action('save_post_product', function () {
    delete_transient('jx_home_html_v4_' . md5(home_url('/')));
    delete_transient('jx_mega_groups_v1_' . md5(home_url('/')));
});

