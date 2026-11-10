<?php
/**
 * Plugin Name: JONILUX - Google Analytics 4
 * Description: Inserta la etiqueta oficial de GA4 cuando se configura un Measurement ID valido.
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

/**
 * Configuracion: define JONILUX_GA4_MEASUREMENT_ID como G-XXXXXXXXXX en wp-config.php,
 * antes de la linea que carga wp-settings.php. Este archivo no modifica wp-config.php.
 * Como alternativa, filtra el valor con el hook jonilux_ga4_measurement_id.
 */
function jonilux_ga4_get_measurement_id() {
    $measurement_id = defined('JONILUX_GA4_MEASUREMENT_ID')
        ? JONILUX_GA4_MEASUREMENT_ID
        : apply_filters('jonilux_ga4_measurement_id', '');

    if (!is_string($measurement_id)) {
        return '';
    }

    $measurement_id = trim($measurement_id);

    return preg_match('/^G-[A-Z0-9]+$/', $measurement_id) ? $measurement_id : '';
}

function jonilux_ga4_print_tag() {
    if (is_admin()) {
        return;
    }

    $measurement_id = jonilux_ga4_get_measurement_id();
    if ($measurement_id === '') {
        return;
    }

    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($measurement_id); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($measurement_id); ?>');
    </script>
    <?php
}
add_action('wp_head', 'jonilux_ga4_print_tag', 5);
