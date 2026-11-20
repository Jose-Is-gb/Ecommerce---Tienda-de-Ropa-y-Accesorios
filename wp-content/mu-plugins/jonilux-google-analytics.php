<?php
/**
 * Plugin Name: JONILUX - Google Analytics 4 y Event Tracking E-Commerce
 * Description: Inserta la etiqueta oficial de GA4 (gtag.js) con Measurement ID activo y registra automaticamente eventos de comercio electronico (view_item, add_to_cart, add_to_wishlist, open_size_guide, contact_whatsapp, copy_coupon, site_search, generate_lead) conforme a la Tabla 4 del Silabo UPN.
 * Version: 2.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

function jonilux_ga4_get_measurement_id() {
    $measurement_id = defined('JONILUX_GA4_MEASUREMENT_ID')
        ? JONILUX_GA4_MEASUREMENT_ID
        : apply_filters('jonilux_ga4_measurement_id', 'G-JX2026UPN1');

    if (!is_string($measurement_id) || trim($measurement_id) === '') {
        $measurement_id = 'G-JX2026UPN1';
    }

    $measurement_id = trim($measurement_id);
    return preg_match('/^G-[A-Z0-9]+$/', $measurement_id) ? $measurement_id : 'G-JX2026UPN1';
}

function jonilux_ga4_print_tag() {
    if (is_admin()) {
        return;
    }

    $measurement_id = jonilux_ga4_get_measurement_id();
    ?>
    <!-- Google Analytics 4 (GA4) + Event Tracking E-Commerce JONILUX -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($measurement_id); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        var hasAnalyticsConsent = document.cookie.indexOf('jonilux_cookie_consent=essential') === -1;
        gtag('consent', 'default', {
            'analytics_storage': hasAnalyticsConsent ? 'granted' : 'denied',
            'ad_storage': hasAnalyticsConsent ? 'granted' : 'denied'
        });
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($measurement_id); ?>', {
            'currency': 'PEN',
            'site_brand': 'JONILUX'
        });

        // Registro de eventos locales para el Cuadro de Mando (Dashboard) y GA4 (respeta consentimiento de cookies)
        window.joniluxTrackEvent = function(eventName, params) {
            params = params || {};
            try {
                if (localStorage.getItem('jonilux_cookie_consent') === 'essential') {
                    return;
                }
                gtag('event', eventName, params);
                var stats = JSON.parse(localStorage.getItem('jonilux_ga4_events_log') || '{}');
                stats[eventName] = (stats[eventName] || 0) + 1;
                localStorage.setItem('jonilux_ga4_events_log', JSON.stringify(stats));
            } catch (e) {}
        };

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Evento view_item en ficha de producto
            if (document.body.classList.contains('single-product')) {
                var titleEl = document.querySelector('h1.wp-block-post-title, .product_title');
                window.joniluxTrackEvent('view_item', {
                    item_name: titleEl ? titleEl.innerText.trim() : 'Prenda JONILUX',
                    currency: 'PEN'
                });
            }

            // 2. Evento site_search cuando hay busqueda o filtro
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('s') || urlParams.get('jx_q') || urlParams.get('jx_cat') || urlParams.get('jx_price')) {
                window.joniluxTrackEvent('site_search', {
                    search_term: urlParams.get('s') || urlParams.get('jx_q') || urlParams.get('jx_cat') || 'filtro_catalogo'
                });
            }

            // 3. Delegacion de clics para Event Tracking (Tabla 4)
            document.body.addEventListener('click', function(e) {
                var target = e.target.closest('a, button');
                if (!target) return;

                if (target.matches('.single_add_to_cart_button, .add_to_cart_button, .wc-block-components-product-button__button')) {
                    window.joniluxTrackEvent('add_to_cart', { currency: 'PEN' });
                } else if (target.matches('#jonilux-open-sizes, .jonilux-size-guide-btn')) {
                    window.joniluxTrackEvent('open_size_guide', { section: 'ficha_producto' });
                } else if (target.matches('.jonilux-card-wishlist-btn, .jonilux-btn-wishlist-single')) {
                    window.joniluxTrackEvent('add_to_wishlist', { item_id: target.getAttribute('data-id') || '' });
                } else if (target.matches('#jonilux-copy-coupon-btn')) {
                    window.joniluxTrackEvent('copy_coupon', { coupon: 'JONILUXVIP' });
                } else if (target.href && target.href.indexOf('wa.me') !== -1) {
                    window.joniluxTrackEvent('contact_whatsapp', { link_url: target.href });
                }
            });
        });
    </script>
    <?php
}
add_action('wp_head', 'jonilux_ga4_print_tag', 5);
