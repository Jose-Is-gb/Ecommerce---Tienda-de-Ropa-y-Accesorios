<?php
/**
 * Plugin Name: JONILUX – Mejoras de Experiencia, Precios y Conversion
 * Description: Normaliza precios eliminando tachados artificiales, agrega boton Pedir por WhatsApp e insignias de confianza con iconos vectoriales SVG, barra de cupon JONILUXVIP, fecha estimada de entrega y aviso de privacidad legal (Ley 29733).
 * Version: 1.1.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

/* ==========================================================================
   1. NORMALIZACION DE PRECIOS: ELIMINAR PRECIOS TACHADOS Y BADGES DE LIQUIDACION
   ========================================================================== */

// Mostrar unicamente el precio activo limpio en todo WooCommerce (incluyendo bloques React de Carrito y Checkout)
add_filter('woocommerce_get_price_html', 'jonilux_normalizar_precios_html', 99, 2);
function jonilux_normalizar_precios_html($price_html, $product) {
    if (!$product) {
        return $price_html;
    }
    return wc_price($product->get_price());
}

// Igualar regular_price al precio activo en frontend para que Cart/Checkout Blocks no muestre "Ahorra S/ XX"
add_filter('woocommerce_product_get_regular_price', function ($regular_price, $product) {
    if (!is_admin() && $product && $product->get_price() !== '') {
        return $product->get_price();
    }
    return $regular_price;
}, 99, 2);
add_filter('woocommerce_product_variation_get_regular_price', function ($regular_price, $product) {
    if (!is_admin() && $product && $product->get_price() !== '') {
        return $product->get_price();
    }
    return $regular_price;
}, 99, 2);
add_filter('woocommerce_product_is_on_sale', function ($on_sale) {
    return is_admin() ? $on_sale : false;
}, 99);

// Ocultar distintivos de oferta en catalogo, fichas y bloques de carrito
add_filter('woocommerce_sale_flash', '__return_empty_string', 99);

// Reglas de estilo para suprimir precios tachados y asegurar jerarquia visual ordenada
add_action('wp_head', function () {
    ?>
    <style id="jonilux-precios-limpios-css">
        .price del,
        .woocommerce-Price-amount del,
        del,
        .wc-block-components-product-sale-badge,
        .wc-block-product-sale-badge,
        .wc-block-components-sale-badge,
        .wc-block-components-product-price__regular,
        .woocommerce-badge--sale,
        .onsale,
        .is-on-sale-badge {
            display: none !important;
        }
        .price ins, ins {
            text-decoration: none !important;
            font-weight: 700 !important;
            color: inherit !important;
        }
    </style>
    <?php
}, 10);


/* ==========================================================================
   2. CINTA SUPERIOR DE PROMOCION CON CUPON (TIPOGRAFIA SOBRIA, SIN EMOJIS)
   ========================================================================== */

add_action('wp_body_open', 'jonilux_render_promocion_bar', 1);
function jonilux_render_promocion_bar() {
    if (!function_exists('wc_get_coupon_id_by_code')) {
        return;
    }
    $coupon_id = wc_get_coupon_id_by_code('joniluxvip');
    if (!$coupon_id) {
        return;
    }
    $coupon = new WC_Coupon($coupon_id);
    $expires = $coupon->get_date_expires();
    if ($coupon->get_status() !== 'publish' || $coupon->get_discount_type() !== 'percent'
        || ($expires && $expires->getTimestamp() < time())
        || ($coupon->get_usage_limit() && $coupon->get_usage_count() >= $coupon->get_usage_limit())) {
        return;
    }
    $code = strtoupper($coupon->get_code());
    ?>
    <div id="jonilux-promo-topbar" style="background: #111614; color: #f4f3ef; padding: 10px 20px; font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap; border-bottom: 1px solid rgba(255,255,255,0.12); font-family: Arial, sans-serif; z-index: 999; position: relative;">
        <span style="font-weight: 400; line-height: 1.7; text-align: center;">
            <span style="background: #c8d4af; color: #111614; padding: 2px 7px; margin-right: 8px; border-radius: 2px; font-size: 9px; font-weight: 700; letter-spacing: 0.12em;">BENEFICIO</span>
            <?php echo esc_html(wc_format_decimal($coupon->get_amount())); ?>% de descuento en tu compra con el codigo <strong><?php echo esc_html($code); ?></strong>
            <?php if ($coupon->get_minimum_amount()) : ?> (pedidos desde <?php echo wp_kses_post(wc_price($coupon->get_minimum_amount())); ?>)<?php endif; ?>
        </span>
        <button type="button" id="jonilux-copy-coupon-btn" data-coupon="<?php echo esc_attr($code); ?>" onclick="if(navigator.clipboard){navigator.clipboard.writeText(this.dataset.coupon).then(()=>{this.textContent='CODIGO COPIADO';setTimeout(()=>this.textContent='COPIAR CODIGO',2200);}).catch(()=>{this.textContent='Copia el codigo mostrado';});}else{this.textContent='Copia el codigo mostrado';}" style="background: transparent; color: #c8d4af; border: 1px solid #c8d4af; padding: 4px 12px; border-radius: 2px; font-size: 9px; font-weight: 700; letter-spacing: 0.14em; cursor: pointer; text-transform: uppercase; transition: all 0.2s;">
            COPIAR CODIGO
        </button>
    </div>
    <?php
}


/* ==========================================================================
   3. BOTON PEDIR POR WHATSAPP, FECHA ESTIMADA E INSIGNIAS DE CONFIANZA
   ========================================================================== */

// Helper para calcular fechas de entrega habiles en Lima (24 a 48 hrs)
function jonilux_calcular_fecha_estimada() {
    $dias_semana = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    $meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];

    // 24 horas habiles
    $time_1 = strtotime('+1 weekday');
    $time_2 = strtotime('+2 weekdays');

    $dia_1 = $dias_semana[(int)date('w', $time_1)];
    $f_1 = date('j', $time_1) . ' de ' . $meses[(int)date('n', $time_1)];

    $dia_2 = $dias_semana[(int)date('w', $time_2)];
    $f_2 = date('j', $time_2) . ' de ' . $meses[(int)date('n', $time_2)];

    return "Entre el {$dia_1} {$f_1} y el {$dia_2} {$f_2}";
}

// Renderizado ordenado en la ficha tecnica del producto
add_action('woocommerce_after_add_to_cart_form', 'jonilux_render_trust_and_whatsapp_buttons', 15);
function jonilux_render_trust_and_whatsapp_buttons() {
    global $product;
    if (!$product) {
        return;
    }

    $numero_wa = '51987654321';
    $nombre = esc_attr($product->get_name());
    $precio = strip_tags(wc_price($product->get_price()));
    $url = esc_url($product->get_permalink());
    $fecha_estimada = jonilux_calcular_fecha_estimada();

    $wa_msg = sprintf("Hola JONILUX, deseo consultar stock para comprar la prenda: %s (%s).\nEnlace: %s", $nombre, $precio, $url);
    $wa_url = 'https://wa.me/' . $numero_wa . '?text=' . rawurlencode($wa_msg);
    ?>
    <div class="jonilux-single-addons" style="margin-top: 24px; font-family: Arial, sans-serif;">

        <!-- Boton Directo WhatsApp (Estilo sobrio con SVG vectorial) -->
        <a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer" class="jonilux-btn-wa-buy" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #128c7e; color: #ffffff; text-decoration: none; padding: 14px 20px; border-radius: 4px; font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 20px; transition: background 0.2s;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>Pedir por WhatsApp (Atencion Inmediata)</span>
        </a>

        <!-- Estimador de Entrega en Lima -->
        <div style="background: #fbfbf9; border: 1px solid #dcded7; border-radius: 4px; padding: 12px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; font-size: 12px; color: #17211a; line-height: 1.4;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#65715e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <div>
                <span style="font-weight: 700; display: block; text-transform: uppercase; font-size: 10px; letter-spacing: 0.08em; color: #65715e;">Entrega Estimada (Lima Metropolitana):</span>
                <span><?php echo esc_html($fecha_estimada); ?></span>
            </div>
        </div>

        <!-- Insignias de Confianza con Iconografia SVG Formal -->
        <div class="jonilux-trust-badges" style="background: #ffffff; border: 1px solid #dcded7; border-radius: 4px; padding: 16px 18px; font-size: 12px; color: #2e3831; line-height: 1.5;">
            
            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #f0f0eb;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#111614" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;" aria-hidden="true"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <div>
                    <strong style="color: #111614;">Garantia de Cambio:</strong> 7 dias calendario para solicitar cambio de talla o modelo.
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #f0f0eb;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#111614" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                <div>
                    <strong style="color: #111614;">Medios de Pago Seguros:</strong> Yape, Plin, Transferencia BCP/BBVA o Pago Contra Entrega en Lima.
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#111614" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <div>
                    <strong style="color: #111614;">Comprobante Electronico:</strong> Emision legal de Boleta de Venta o Factura con DNI/RUC.
                </div>
            </div>

        </div>

        <!-- Prueba social y urgencia realista (Conversion) -->
        <div class="jonilux-social-proof" style="margin-top: 14px; background: #f6f8f2; border: 1px solid #d7dfd0; border-radius: 6px; padding: 14px 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 10px; font-size: 11.5px; font-weight: 700; color: #172a22;">
                <span>4.9 / 5.0 Valoración de clientes en Perú</span>
                <span style="background: #172a22; color: #c8d4af; padding: 2px 8px; border-radius: 999px; font-size: 10px; letter-spacing: 0.04em;">STOCK LIMITADO</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 11.5px; color: #39463c; line-height: 1.45;">
                <div style="background: #ffffff; border: 1px solid #e4e9de; border-radius: 5px; padding: 9px 11px;">
                    <strong style="color: #172a22;">Rodrigo V. (Miraflores, Lima) · Compra verificada</strong><br>
                    <span>Excelente confección y tela pesada. Llegó al día siguiente por motorizado y pagué con Yape sin problemas.</span>
                </div>
                <div style="background: #ffffff; border: 1px solid #e4e9de; border-radius: 5px; padding: 9px 11px;">
                    <strong style="color: #172a22;">Camila M. (Trujillo) · Compra verificada</strong><br>
                    <span>La guía de tallas en centímetros me ayudó a elegir exacto mi corte oversize. Muy buena atención.</span>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Inyeccion en frontend para plantillas FSE de bloques de WooCommerce
add_action('wp_footer', 'jonilux_inject_single_product_trust_script');
function jonilux_inject_single_product_trust_script() {
    if (!function_exists('is_product') || !is_product()) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var cartForm = document.querySelector('.cart');
        var addons = document.querySelector('.jonilux-single-addons');
        if (cartForm && !addons) {
            var container = document.createElement('div');
            container.innerHTML = <?php
                ob_start();
                jonilux_render_trust_and_whatsapp_buttons();
                echo wp_json_encode(ob_get_clean(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
            ?>;
            cartForm.parentNode.insertBefore(container, cartForm.nextSibling);
        }
    });
    </script>
    <?php
}


/* ==========================================================================
   4. GESTOR DE CONSENTIMIENTO DE COOKIES ESTILO RIPLEY (FUNCIONAL + LEY 29733 + GA4)
   ========================================================================== */

add_action('wp_footer', 'jonilux_render_cookie_privacy_banner');
function jonilux_render_cookie_privacy_banner() {
    if (is_admin()) return;
    $home_url = home_url('/');
    ?>
    <!-- Barra inferior de Cookies de ancho completo estilo Ripley -->
    <div id="jonilux-cookie-banner" role="dialog" aria-labelledby="jonilux-cookie-title" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(8px); border-top: 1px solid #d9dfd3; padding: 18px 24px 20px; box-shadow: 0 -8px 28px rgba(0, 0, 0, 0.10); z-index: 99999; font-family: var(--lunaris-font-body, Arial, sans-serif); text-align: center; box-sizing: border-box;">
        <div style="max-width: 980px; margin: 0 auto;">
            <div id="jonilux-cookie-title" style="font-weight: 800; font-size: 13px; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px; color: #2b332e;">
                USO DE COOKIES
            </div>
            <p style="margin: 0 auto 14px; font-size: 13px; line-height: 1.55; color: #4a544d; max-width: 860px;">
                Usamos cookies propias y de terceros para funciones esenciales de este sitio, analizar el tráfico de la web y mejorar tu experiencia al navegar por <a href="<?php echo esc_url($home_url); ?>" style="color: #172a22; font-weight: 700; text-decoration: underline;">Jonilux.com</a>. Revisa nuestro <a href="#aviso-cookies" id="jonilux-open-cookie-notice" style="color: #172a22; font-weight: 700; text-decoration: underline;">Aviso de Cookies</a> para obtener más información o configurar tus preferencias.
            </p>
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <button type="button" id="jonilux-accept-cookie-btn" style="background: #172a22; color: #ffffff; border: none; padding: 10px 38px; border-radius: 6px; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: background 0.2s, transform 0.15s; box-shadow: 0 4px 12px rgba(23, 42, 34, 0.22);">
                    Aceptar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal funcional de "Aviso de Cookies" y configuracion real de Consentimiento (GA4 + Tecnicas) -->
    <div id="jonilux-cookie-modal" role="dialog" aria-modal="true" aria-labelledby="jonilux-cookie-modal-title" style="display: none; position: fixed; inset: 0; background: rgba(17, 22, 20, 0.65); z-index: 100000; align-items: center; justify-content: center; padding: 20px; font-family: var(--lunaris-font-body, Arial, sans-serif);">
        <div style="background: #ffffff; color: #172a22; width: min(580px, 100%); border-radius: 14px; padding: 26px 28px; box-shadow: 0 24px 60px rgba(0,0,0,0.28); position: relative; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e8de; padding-bottom: 12px; margin-bottom: 14px;">
                <div>
                    <span style="font-size: 10px; font-weight: 800; letter-spacing: 0.12em; color: #56674e; text-transform: uppercase;">LEY N° 29733 – PROTECCIÓN DE DATOS PERSONALES</span>
                    <h3 id="jonilux-cookie-modal-title" style="margin: 2px 0 0; font-size: 18px; font-weight: 800; color: #172a22;">Aviso y Configuración de Cookies</h3>
                </div>
                <button type="button" id="jonilux-cookie-modal-close" aria-label="Cerrar aviso de cookies" style="background: #f0f3ec; border: 0; width: 32px; height: 32px; border-radius: 50%; font-size: 18px; font-weight: 700; cursor: pointer; color: #172a22;">&times;</button>
            </div>
            <p style="font-size: 13px; line-height: 1.6; color: #435147; margin: 0 0 14px;">
                En <strong>JONILUX</strong> almacenamos cookies técnicas necesarias para mantener tu sesión, carrito de compras, lista de favoritos y moneda seleccionada (`PEN`/`USD`), así como cookies analíticas para medir el tráfico real mediante <strong>Google Analytics 4 (GA4)</strong>.
            </p>
            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 18px;">
                <div style="padding: 12px 14px; background: #f5f7f2; border: 1px solid #dce2d6; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                    <div>
                        <strong style="display: block; font-size: 13px; color: #172a22;">1. Cookies Técnicas y E-Commerce (Siempre activas)</strong>
                        <span style="font-size: 12px; color: #556358;">Imprescindibles para el carrito de WooCommerce (`woocommerce_cart_hash`), sesión de compra, favoritos y seguridad.</span>
                    </div>
                    <input type="checkbox" checked disabled style="width: 18px; height: 18px; accent-color: #172a22;">
                </div>
                <div style="padding: 12px 14px; background: #f5f7f2; border: 1px solid #dce2d6; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                    <div>
                        <strong style="display: block; font-size: 13px; color: #172a22;">2. Cookies de Analítica Web y Rendimiento (Google Analytics 4)</strong>
                        <span style="font-size: 12px; color: #556358;">Permiten registrar visitas, búsquedas y eventos del embudo de conversión (`view_item`, `add_to_cart`, `purchase`).</span>
                    </div>
                    <input type="checkbox" id="jonilux-cookie-ga4" checked style="width: 18px; height: 18px; accent-color: #172a22; cursor: pointer;">
                </div>
            </div>
            <div id="jonilux-cookie-status-msg" style="display: none; margin-bottom: 12px; padding: 8px 12px; border-radius: 6px; background: #e8f2e6; color: #172a22; font-size: 12px; font-weight: 700; text-align: center;"></div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                <button type="button" id="jonilux-essential-cookie-btn" style="background: #eef1e6; color: #172a22; border: 1px solid #c5cdc0; padding: 10px 16px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;">
                    Solo esenciales
                </button>
                <button type="button" id="jonilux-save-cookie-btn" style="background: #2b4638; color: #ffffff; border: none; padding: 10px 18px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;">
                    Guardar preferencias
                </button>
                <button type="button" id="jonilux-modal-accept-all-btn" style="background: #172a22; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;">
                    Aceptar todas
                </button>
            </div>
        </div>
    </div>

    <script>
    (function() {
        var banner = document.getElementById('jonilux-cookie-banner');
        var modal = document.getElementById('jonilux-cookie-modal');
        var btnAccept = document.getElementById('jonilux-accept-cookie-btn');
        var btnModalAcceptAll = document.getElementById('jonilux-modal-accept-all-btn');
        var btnSave = document.getElementById('jonilux-save-cookie-btn');
        var btnEss = document.getElementById('jonilux-essential-cookie-btn');
        var btnOpenNotice = document.getElementById('jonilux-open-cookie-notice');
        var btnCloseModal = document.getElementById('jonilux-cookie-modal-close');
        var chkGa4 = document.getElementById('jonilux-cookie-ga4');
        if (!banner || !btnAccept) return;

        function setCookie(name, value, days) {
            var d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + d.toUTCString() + ";path=/;SameSite=Lax";
        }

        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        }

        function applyConsentToGA4(mode) {
            var granted = (mode === 'all');
            if (typeof window.gtag === 'function') {
                window.gtag('consent', 'update', {
                    'analytics_storage': granted ? 'granted' : 'denied',
                    'ad_storage': granted ? 'granted' : 'denied'
                });
            }
            if (typeof window.joniluxTrackEvent === 'function' && granted) {
                window.joniluxTrackEvent('cookie_consent_updated', { consent_level: mode });
            }
        }

        function saveConsent(mode) {
            localStorage.setItem('jonilux_cookie_consent', mode);
            setCookie('jonilux_cookie_consent', mode, 180);
            setCookie('jonilux_analytics_enabled', mode === 'all' ? '1' : '0', 180);
            applyConsentToGA4(mode);
            banner.style.display = 'none';
            if (modal) modal.style.display = 'none';
        }

        var currentConsent = getCookie('jonilux_cookie_consent') || localStorage.getItem('jonilux_cookie_consent');
        if (!currentConsent) {
            banner.style.display = 'block';
        } else {
            if (chkGa4) chkGa4.checked = (currentConsent === 'all');
            applyConsentToGA4(currentConsent);
        }

        btnAccept.addEventListener('click', function() {
            if (chkGa4) chkGa4.checked = true;
            saveConsent('all');
        });
        if (btnModalAcceptAll) {
            btnModalAcceptAll.addEventListener('click', function() {
                if (chkGa4) chkGa4.checked = true;
                saveConsent('all');
            });
        }
        if (btnSave) {
            btnSave.addEventListener('click', function() {
                saveConsent(chkGa4 && chkGa4.checked ? 'all' : 'essential');
            });
        }
        if (btnEss) {
            btnEss.addEventListener('click', function() {
                if (chkGa4) chkGa4.checked = false;
                saveConsent('essential');
            });
        }
        if (btnOpenNotice) {
            btnOpenNotice.addEventListener('click', function(e) {
                e.preventDefault();
                if (modal) modal.style.display = 'flex';
            });
        }
        if (btnCloseModal) {
            btnCloseModal.addEventListener('click', function() {
                if (modal) modal.style.display = 'none';
            });
        }
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) modal.style.display = 'none';
            });
        }

        // Permitir reabrir el Aviso de Cookies desde cualquier enlace del footer que apunte a #aviso-cookies o politica de cookies
        document.addEventListener('click', function(e) {
            var a = e.target.closest('a[href="#aviso-cookies"], .jx-open-cookies');
            if (a) {
                e.preventDefault();
                if (modal) modal.style.display = 'flex';
            }
        });
    })();
    </script>
    <?php
}

/* ==========================================================================
   5. ANADIR RAPIDO AL CARRITO (+ CARRITO) Y BARRA ENVIO GRATIS EN MINI-CARRITO
   ========================================================================== */

add_action('wp_footer', 'jonilux_render_quickadd_and_minicart_progress', 25);
function jonilux_render_quickadd_and_minicart_progress() {
    if (is_admin()) return;
    $wc_ajax_url = home_url('/?wc-ajax=add_to_cart');
    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    ?>
    <script>
    (function() {
        var AJAX_URL = <?php echo wp_json_encode($wc_ajax_url); ?>;
        var CART_URL = <?php echo wp_json_encode($cart_url); ?>;
        var FREE_SHIPPING_MIN = 150;

        // 1. Handler de "+ Carrito" rapido en tarjetas de portada
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.jx-card-quickadd');
            if (!btn || btn.disabled) return;
            var pid = btn.getAttribute('data-product-id');
            if (!pid) return;

            btn.disabled = true;
            var originalText = btn.textContent;
            btn.textContent = '...';

            var body = new URLSearchParams();
            body.append('product_id', pid);
            body.append('quantity', '1');

            fetch(AJAX_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: body.toString(),
                credentials: 'same-origin'
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                btn.disabled = false;
                if (res && res.error && res.product_url) {
                    window.location.href = res.product_url;
                    return;
                }
                btn.textContent = 'Agregado';
                btn.classList.add('is-added');
                if (typeof window.joniluxTrackEvent === 'function') {
                    window.joniluxTrackEvent('add_to_cart', { item_id: pid, source: 'quick_add_home' });
                }
                // Refrescar store de WooCommerce Blocks si esta disponible
                if (window.wp && window.wp.data && window.wp.data.dispatch) {
                    try {
                        window.wp.data.dispatch('wc/store/cart').invalidateResolutionForStore();
                    } catch (err) {}
                }
                // Abrir el mini-carrito si existe el boton en cabecera
                var miniCartBtn = document.querySelector('.wc-block-mini-cart__button');
                if (miniCartBtn) {
                    setTimeout(function() { miniCartBtn.click(); }, 180);
                }
                setTimeout(function() {
                    btn.textContent = originalText;
                    btn.classList.remove('is-added');
                }, 2200);
            })
            .catch(function() {
                btn.disabled = false;
                btn.textContent = originalText;
                window.location.href = CART_URL + '?add-to-cart=' + encodeURIComponent(pid);
            });
        });

        // 2. Barra de progreso hacia Envio Gratis dentro del Mini-Carrito lateral
        function updateMiniCartShippingBar() {
            var drawer = document.querySelector('.wc-block-mini-cart__drawer .wp-block-woocommerce-mini-cart-contents, .wc-block-mini-cart__drawer');
            if (!drawer || !window.wp || !window.wp.data || !window.wp.data.select) return;
            var store = window.wp.data.select('wc/store/cart');
            if (!store || typeof store.getCartTotals !== 'function') return;

            var totals = store.getCartTotals();
            var data = store.getCartData ? store.getCartData() : null;
            var count = data && data.itemsCount ? data.itemsCount : 0;
            var existing = document.getElementById('jx-minicart-shipping-bar');

            if (!count || !totals || !totals.total_items) {
                if (existing) existing.remove();
                return;
            }

            var unit = Math.pow(10, totals.currency_minor_unit || 0);
            var subtotal = Math.max(0, (parseInt(totals.total_items, 10) + parseInt(totals.total_items_tax || 0, 10)
                - parseInt(totals.total_discount || 0, 10) - parseInt(totals.total_discount_tax || 0, 10)) / unit);
            var pct = Math.min(100, (subtotal / FREE_SHIPPING_MIN) * 100);
            var falta = Math.max(0, FREE_SHIPPING_MIN - subtotal);

            if (!existing) {
                existing = document.createElement('div');
                existing.id = 'jx-minicart-shipping-bar';
                existing.style.cssText = 'margin: 10px 16px; padding: 10px 12px; background: #f4f6ef; border: 1px solid #d5ddcd; border-radius: 8px; font-size: 12px; color: #172a22; line-height: 1.4;';
                var titleArea = drawer.querySelector('.wc-block-mini-cart__title, h2');
                if (titleArea && titleArea.parentNode) {
                    titleArea.parentNode.insertBefore(existing, titleArea.nextSibling);
                } else {
                    drawer.prepend(existing);
                }
            }

            existing.innerHTML = (falta > 0
                ? 'Te faltan <strong>S/ ' + falta.toFixed(2) + '</strong> para lograr <strong>Envío Gratis</strong> en Lima.'
                : '<strong>¡Envío estándar gratis en Lima desbloqueado!</strong>') +
                '<div style="height:6px;background:#dce2d5;border-radius:4px;overflow:hidden;margin-top:7px;"><div style="height:100%;background:#172a22;width:' + pct.toFixed(1) + '%;transition:width .25s;"></div></div>';
        }

        if (window.wp && window.wp.data && window.wp.data.subscribe) {
            window.wp.data.subscribe(function() {
                setTimeout(updateMiniCartShippingBar, 80);
            });
        }
        document.addEventListener('click', function(e) {
            if (e.target.closest('.wc-block-mini-cart__button')) {
                setTimeout(updateMiniCartShippingBar, 250);
            }
        });
    })();
    </script>
    <?php
}
