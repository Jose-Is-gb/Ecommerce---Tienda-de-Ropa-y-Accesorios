<?php
/**
 * Plugin Name: JONILUX – Pasarelas y Confirmacion de Pagos (Yape / Plin / WhatsApp)
 * Description: Anade instrucciones de pago con Yape, Plin y BCP, boton de copiar numero y enlace directo a WhatsApp para enviar el comprobante tras finalizar el pedido en WooCommerce.
 * Version: 1.1.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Mejorar la descripcion de Transferencia Bancaria (BACS) en el Checkout
add_filter('woocommerce_gateway_description', 'jonilux_custom_bacs_checkout_description', 20, 2);
function jonilux_custom_bacs_checkout_description($description, $payment_id) {
    if ($payment_id === 'bacs') {
        ob_start();
        ?>
        <div class="jonilux-checkout-pay-info" style="background: #f4f3ef; padding: 16px 18px; border-radius: 4px; border-left: 3px solid #111614; margin: 12px 0; font-size: 12px; line-height: 1.6; color: #17211a; font-family: Arial, sans-serif;">
            <p style="margin: 0 0 8px 0; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.06em;">Medios de pago disponibles:</p>
            <ul style="margin: 0 0 10px 0; padding-left: 16px;">
                <li><strong>Yape y Plin:</strong> 987 654 321 (Titular: JONILUX S.A.C.)</li>
                <li><strong>BCP Cta Corriente:</strong> 193-98765432-0-12 (CCI: 002-1930098765432012)</li>
            </ul>
            <p style="margin: 0; font-size: 11px; color: #626b63;">Al confirmar tu pedido, veras el boton directo para <strong>enviar tu comprobante de pago por WhatsApp</strong> y programar tu entrega.</p>
        </div>
        <?php
        return ob_get_clean();
    }
    return $description;
}

// 2. Bloque interactivo de confirmacion en la pagina de Gracias por tu compra
add_action('woocommerce_thankyou', 'jonilux_render_order_received_whatsapp_confirmation', 5);
function jonilux_render_order_received_whatsapp_confirmation($order_id) {
    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    $payment_method = $order->get_payment_method();
    $order_number = $order->get_order_number();
    $total_formatted = wc_price($order->get_total());
    $total_raw = number_format((float)$order->get_total(), 2, '.', '');
    $customer_name = esc_attr($order->get_billing_first_name() ?: 'Cliente');
    $numero_wa = '51987654321';

    $wa_msg = sprintf(
        "Hola JONILUX, adjunto mi comprobante de pago para el Pedido #%s por el monto de S/ %s. (Cliente: %s)",
        $order_number,
        $total_raw,
        $customer_name
    );
    $wa_url = 'https://wa.me/' . $numero_wa . '?text=' . rawurlencode($wa_msg);
    ?>

    <div class="jonilux-thankyou-box" style="margin: 28px 0; padding: 26px 30px; background: #ffffff; border: 1px solid #111614; border-radius: 6px; font-family: Arial, sans-serif; color: #17211a;">
        
        <?php if ($payment_method === 'bacs') : ?>
            <!-- Pago por Transferencia / Yape / Plin -->
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px; border-bottom: 1px solid #e2e4de; padding-bottom: 16px;">
                <div style="background: #111614; color: #ffffff; width: 34px; height: 34px; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 18px; color: #111614; font-weight: 700; letter-spacing: -0.02em;">Paso final para despachar tu Pedido #<?php echo esc_html($order_number); ?></h3>
                    <p style="margin: 3px 0 0; font-size: 12px; color: #626b63;">Realiza tu transferencia o envio por Yape/Plin y confirmanos para programar el despacho.</p>
                </div>
            </div>

            <!-- Resumen del Pago -->
            <div style="background: #f4f3ef; padding: 14px 20px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <span style="font-size: 10px; color: #626b63; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 2px;">Monto total a pagar:</span>
                    <strong style="font-size: 20px; color: #111614;"><?php echo $total_formatted; ?></strong>
                </div>
                <div>
                    <span style="font-size: 10px; color: #626b63; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 2px;">Estado del pedido:</span>
                    <span style="display: inline-block; background: #e7e9e0; color: #17211a; padding: 3px 9px; border-radius: 2px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Pendiente de pago</span>
                </div>
            </div>

            <!-- Canales de Pago -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <!-- Tarjeta Yape y Plin -->
                <div style="border: 1px solid #dcded7; border-radius: 4px; padding: 16px; background: #fafaf8;">
                    <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                        <span style="background: #742284; color: #ffffff; font-weight: 700; font-size: 10px; padding: 2px 7px; border-radius: 2px; letter-spacing: 0.06em;">YAPE</span>
                        <span style="background: #00bfd6; color: #ffffff; font-weight: 700; font-size: 10px; padding: 2px 7px; border-radius: 2px; letter-spacing: 0.06em;">PLIN</span>
                    </div>
                    <div style="font-size: 12px; line-height: 1.5;">
                        <div style="color: #626b63;">Numero de celular:</div>
                        <div style="display: flex; align-items: center; gap: 10px; margin: 4px 0;">
                            <strong style="font-size: 17px; color: #111614; letter-spacing: 0.04em;">987654321</strong>
                            <button type="button" onclick="navigator.clipboard.writeText('987654321'); this.textContent='COPIADO'; setTimeout(()=>this.textContent='COPIAR', 2000);" style="background: #e2e4de; color: #111614; border: none; padding: 3px 8px; border-radius: 2px; font-size: 10px; font-weight: 700; cursor: pointer; letter-spacing: 0.06em;">COPIAR</button>
                        </div>
                        <div style="color: #626b63; font-size: 11px;">Titular: <strong>JONILUX S.A.C.</strong></div>
                    </div>
                </div>

                <!-- Tarjeta BCP / BBVA -->
                <div style="border: 1px solid #dcded7; border-radius: 4px; padding: 16px; background: #fafaf8;">
                    <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                        <span style="background: #002a8f; color: #ffffff; font-weight: 700; font-size: 10px; padding: 2px 7px; border-radius: 2px; letter-spacing: 0.06em;">BCP</span>
                        <span style="background: #004481; color: #ffffff; font-weight: 700; font-size: 10px; padding: 2px 7px; border-radius: 2px; letter-spacing: 0.06em;">BBVA</span>
                    </div>
                    <div style="font-size: 12px; line-height: 1.5; color: #17211a;">
                        <div>Cta Corriente BCP: <strong>193-98765432-0-12</strong></div>
                        <div>Codigo CCI: <strong>002-1930098765432012</strong></div>
                        <div style="margin-top: 3px; color: #626b63; font-size: 11px;">Titular: <strong>JONILUX S.A.C.</strong></div>
                    </div>
                </div>
            </div>

            <!-- Boton a WhatsApp -->
            <div style="text-align: center; padding-top: 6px;">
                <a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; background: #128c7e; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 4px; font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; transition: background 0.2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Enviar Comprobante por WhatsApp</span>
                </a>
                <p style="margin: 8px 0 0; font-size: 11px; color: #626b63;">Confirmaremos la preparacion y el numero de seguimiento de tu envio.</p>
            </div>

        <?php elseif ($payment_method === 'cod') : ?>
            <!-- Pago Contra Entrega -->
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
                <div style="background: #111614; color: #ffffff; width: 34px; height: 34px; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 18px; color: #111614; font-weight: 700; letter-spacing: -0.02em;">Pedido registrado con Pago Contra Entrega</h3>
                    <p style="margin: 3px 0 0; font-size: 12px; color: #626b63;">Abonaras S/ <?php echo esc_html($total_raw); ?> al recibir tus prendas en Lima o Callao.</p>
                </div>
            </div>
            <div style="background: #f4f3ef; padding: 14px 18px; border-radius: 4px; font-size: 12px; color: #17211a; line-height: 1.6;">
                El courier se comunicara al numero <strong><?php echo esc_html($order->get_billing_phone() ?: 'registrado'); ?></strong> antes de acudir a tu domicilio.<br>
                Recuerda tener disponible el efectivo exacto o tu aplicacion de Yape/Plin activa.
            </div>
            <div style="text-align: center; margin-top: 18px;">
                <a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; background: #111614; color: #ffffff; padding: 11px 22px; border-radius: 4px; text-decoration: none; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;">
                    Consultar estado del envio
                </a>
            </div>

        <?php endif; ?>

    </div>
    <?php
}
