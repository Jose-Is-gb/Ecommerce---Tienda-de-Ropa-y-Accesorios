<?php
/**
 * Plugin Name: JONILUX – Pasarela Multipago Interactiva (Tarjeta Sandbox, Yape/Plin QR y Transferencia)
 * Description: Implementa la experiencia completa de pasarela de pagos en el Checkout (Semana 5 del Silabo): Tarjeta de Credito/Debito Visa y Mastercard en modo Sandbox Academico, Yape/Plin con codigo QR vectorial y codigo de aprobacion, Transferencia BCP/BBVA y Pago Contra Entrega.
 * Version: 2.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Cambiar el titulo del metodo BACS en el checkout para reflejar la Pasarela Multipago JONILUX
add_filter('woocommerce_gateway_title', function ($title, $payment_id) {
    if ($payment_id === 'bacs') {
        return 'Pasarela Digital JONILUX (Tarjeta Visa/Mastercard, Yape/Plin QR o Transferencia)';
    }
    if ($payment_id === 'cod') {
        return 'Pago Contra Entrega (Efectivo o Yape al recibir en Lima y Callao)';
    }
    return $title;
}, 20, 2);

// 2. Descripcion clasica para BACS
add_filter('woocommerce_gateway_description', 'jonilux_custom_bacs_checkout_description', 20, 2);
function jonilux_custom_bacs_checkout_description($description, $payment_id) {
    if ($payment_id === 'bacs') {
        return 'Selecciona abajo tu modalidad preferida: Tarjeta de Credito/Debito (Visa/Mastercard), Yape/Plin con QR instantaneo o Transferencia Bancaria BCP/BBVA.';
    }
    return $description;
}

// 3. Inyectar la interfaz interactiva de Pasarela de Pago en la pagina de Checkout (compatible con Checkout de Bloques y Clasico)
add_action('wp_footer', 'jonilux_render_interactive_checkout_gateway_widget', 30);
function jonilux_render_interactive_checkout_gateway_widget() {
    if (!function_exists('is_checkout') || !is_checkout() || is_order_received_page()) {
        return;
    }
    ?>
    <style id="jonilux-gateway-css">
        .jx-pay-suite { margin: 14px 0 18px; border: 1.5px solid #172a22; border-radius: 12px; background: #ffffff; overflow: hidden; font-family: var(--lunaris-font-body, Arial, sans-serif); color: #172a22; box-shadow: 0 6px 20px rgba(23,42,34,0.06); }
        .jx-pay-tabs { display: grid; grid-template-columns: repeat(3, 1fr); background: #f2f4ed; border-bottom: 1px solid #d8dfd0; }
        .jx-pay-tab { appearance: none; border: 0; border-right: 1px solid #d8dfd0; background: transparent; padding: 12px 10px; font: 700 11.5px/1.3 var(--lunaris-font-body, Arial, sans-serif); letter-spacing: .04em; text-transform: uppercase; color: #4e5d52; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 4px; transition: background .15s, color .15s; }
        .jx-pay-tab:last-child { border-right: 0; }
        .jx-pay-tab.is-active { background: #172a22; color: #ffffff; }
        .jx-pay-tab-badges { display: flex; gap: 4px; flex-wrap: wrap; justify-content: center; }
        .jx-pay-mini-badge { font-size: 9px; font-weight: 800; padding: 2px 5px; border-radius: 3px; letter-spacing: .05em; }
        .jx-pay-panel { display: none; padding: 18px 20px; }
        .jx-pay-panel.is-active { display: block; }

        /* Panel Tarjeta de Credito / Debito */
        .jx-card-preview { background: linear-gradient(135deg, #172a22 0%, #2d4939 100%); color: #fff; border-radius: 10px; padding: 16px 18px; margin-bottom: 16px; position: relative; box-shadow: 0 8px 20px rgba(23,42,34,0.18); }
        .jx-card-preview-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: #c8d4af; font-weight: 700; }
        .jx-card-preview-num { font: 700 18px/1.3 'Courier New', monospace; letter-spacing: .14em; margin-bottom: 14px; color: #ffffff; }
        .jx-card-preview-bottom { display: flex; justify-content: space-between; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #dbe4d2; }
        .jx-card-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .jx-card-field { display: flex; flex-direction: column; gap: 4px; }
        .jx-card-field.full { grid-column: 1 / -1; }
        .jx-card-field label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #435346; }
        .jx-card-field input, .jx-card-field select { height: 40px; padding: 0 12px; border: 1px solid #c5cdc0; border-radius: 6px; font: 600 13px var(--lunaris-font-body, Arial, sans-serif); color: #172a22; background: #fbfcfa; box-sizing: border-box; width: 100%; }
        .jx-card-field input:focus, .jx-card-field select:focus { border-color: #172a22; background: #fff; outline: none; }
        .jx-sandbox-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding: 8px 12px; background: #eef2e4; border: 1px dashed #8a9e7e; border-radius: 6px; font-size: 11px; }
        .jx-sandbox-fill-btn { background: #172a22; color: #fff; border: 0; border-radius: 4px; padding: 5px 10px; font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; cursor: pointer; }
        .jx-sandbox-fill-btn:hover { background: #2f4535; }

        /* Panel Yape / Plin QR */
        .jx-qr-grid { display: grid; grid-template-columns: 150px 1fr; gap: 18px; align-items: center; }
        .jx-qr-box { background: #fff; border: 2px solid #742284; border-radius: 10px; padding: 10px; text-align: center; }
        .jx-qr-box svg { display: block; width: 126px; height: 126px; margin: 0 auto 6px; }
        .jx-qr-box span { display: block; font-size: 10px; font-weight: 800; color: #742284; letter-spacing: .06em; text-transform: uppercase; }
        .jx-yape-steps { font-size: 12.5px; line-height: 1.55; color: #24332a; }
        .jx-yape-num-bar { display: inline-flex; align-items: center; gap: 10px; background: #f4f6ef; border: 1px solid #cfd7c7; padding: 6px 12px; border-radius: 6px; margin: 6px 0 10px; }
        .jx-yape-num-bar strong { font-size: 16px; letter-spacing: .05em; color: #172a22; }
        .jx-copy-mini { background: #172a22; color: #fff; border: 0; border-radius: 4px; padding: 4px 8px; font-size: 10px; font-weight: 700; cursor: pointer; text-transform: uppercase; }

        @media (max-width: 600px) {
            .jx-pay-tabs { grid-template-columns: 1fr; }
            .jx-pay-tab { border-right: 0; border-bottom: 1px solid #d8dfd0; flex-direction: row; justify-content: space-between; }
            .jx-qr-grid { grid-template-columns: 1fr; justify-items: center; }
            .jx-card-form-grid { grid-template-columns: 1fr; }
        }
    </style>

    <script>
    (function() {
        function buildGatewayHTML() {
            return `
            <div class="jx-pay-suite" id="jx-pay-suite">
                <div class="jx-pay-tabs" role="tablist">
                    <button type="button" class="jx-pay-tab is-active" data-tab="card">
                        <span>Tarjeta Crédito / Débito</span>
                        <span class="jx-pay-tab-badges">
                            <span class="jx-pay-mini-badge" style="background:#1a1f71;color:#fff;">VISA</span>
                            <span class="jx-pay-mini-badge" style="background:#eb001b;color:#fff;">MC</span>
                        </span>
                    </button>
                    <button type="button" class="jx-pay-tab" data-tab="yape">
                        <span>Yape / Plin con QR</span>
                        <span class="jx-pay-tab-badges">
                            <span class="jx-pay-mini-badge" style="background:#742284;color:#fff;">YAPE</span>
                            <span class="jx-pay-mini-badge" style="background:#00bfd6;color:#fff;">PLIN</span>
                        </span>
                    </button>
                    <button type="button" class="jx-pay-tab" data-tab="bank">
                        <span>Transferencia</span>
                        <span class="jx-pay-tab-badges">
                            <span class="jx-pay-mini-badge" style="background:#002a8f;color:#fff;">BCP</span>
                            <span class="jx-pay-mini-badge" style="background:#004481;color:#fff;">BBVA</span>
                        </span>
                    </button>
                </div>

                <!-- PANEL 1: TARJETA VISA / MASTERCARD (SANDBOX ACADEMICO) -->
                <div class="jx-pay-panel is-active" data-panel="card">
                    <div class="jx-sandbox-bar">
                        <span><strong>Pasarela Segura SSL (Modo Sandbox):</strong> Simulación académica sin cobro real.</span>
                        <button type="button" class="jx-sandbox-fill-btn" id="jx-fill-test-card">Autocompletar Tarjeta Demo</button>
                    </div>
                    <div class="jx-card-preview">
                        <div class="jx-card-preview-top">
                            <span>JONILUX PAY · PERÚ</span>
                            <span id="jx-card-brand-label">VISA / MASTERCARD</span>
                        </div>
                        <div class="jx-card-preview-num" id="jx-card-live-num">•••• •••• •••• ••••</div>
                        <div class="jx-card-preview-bottom">
                            <div>Titular: <strong id="jx-card-live-name">NOMBRE DEL CLIENTE</strong></div>
                            <div>Vence: <strong id="jx-card-live-exp">MM/AA</strong></div>
                        </div>
                    </div>
                    <div class="jx-card-form-grid">
                        <div class="jx-card-field full">
                            <label for="jx-cc-num">Número de tarjeta (16 dígitos)</label>
                            <input type="text" id="jx-cc-num" maxlength="19" placeholder="4557 8800 1234 4242" autocomplete="cc-number">
                        </div>
                        <div class="jx-card-field full">
                            <label for="jx-cc-name">Nombre del titular en la tarjeta</label>
                            <input type="text" id="jx-cc-name" maxlength="40" placeholder="Ej. LUZ DELGADO DE LA FLOR" autocomplete="cc-name">
                        </div>
                        <div class="jx-card-field">
                            <label for="jx-cc-exp">Vencimiento (MM/AA)</label>
                            <input type="text" id="jx-cc-exp" maxlength="5" placeholder="08/29" autocomplete="cc-exp">
                        </div>
                        <div class="jx-card-field">
                            <label for="jx-cc-cvv">Código CVV (3 dígitos)</label>
                            <input type="password" id="jx-cc-cvv" maxlength="4" placeholder="123" autocomplete="cc-csc">
                        </div>
                        <div class="jx-card-field full">
                            <label for="jx-cc-cuotas">Cuotas</label>
                            <select id="jx-cc-cuotas">
                                <option value="1">Pago directo (Sin intereses)</option>
                                <option value="3">3 cuotas sin intereses</option>
                                <option value="6">6 cuotas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- PANEL 2: YAPE / PLIN CON QR -->
                <div class="jx-pay-panel" data-panel="yape">
                    <div class="jx-qr-grid">
                        <div class="jx-qr-box">
                            <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" aria-label="Código QR oficial JONILUX">
                                <rect width="120" height="120" fill="#ffffff"/>
                                <rect x="8" y="8" width="32" height="32" fill="none" stroke="#172a22" stroke-width="6"/>
                                <rect x="16" y="16" width="16" height="16" fill="#742284"/>
                                <rect x="80" y="8" width="32" height="32" fill="none" stroke="#172a22" stroke-width="6"/>
                                <rect x="88" y="16" width="16" height="16" fill="#742284"/>
                                <rect x="8" y="80" width="32" height="32" fill="none" stroke="#172a22" stroke-width="6"/>
                                <rect x="16" y="88" width="16" height="16" fill="#00bfd6"/>
                                <rect x="48" y="12" width="8" height="8" fill="#172a22"/>
                                <rect x="62" y="12" width="8" height="16" fill="#172a22"/>
                                <rect x="48" y="28" width="10" height="10" fill="#742284"/>
                                <rect x="12" y="48" width="16" height="8" fill="#172a22"/>
                                <rect x="36" y="48" width="24" height="8" fill="#172a22"/>
                                <rect x="68" y="44" width="12" height="12" fill="#00bfd6"/>
                                <rect x="88" y="48" width="20" height="8" fill="#172a22"/>
                                <rect x="48" y="62" width="16" height="16" fill="#172a22"/>
                                <rect x="16" y="62" width="12" height="10" fill="#172a22"/>
                                <rect x="72" y="66" width="10" height="18" fill="#172a22"/>
                                <rect x="92" y="64" width="16" height="12" fill="#742284"/>
                                <rect x="48" y="86" width="12" height="22" fill="#172a22"/>
                                <rect x="68" y="92" width="18" height="16" fill="#172a22"/>
                                <rect x="94" y="86" width="14" height="22" fill="#00bfd6"/>
                            </svg>
                            <span>Escanea con Yape o Plin</span>
                        </div>
                        <div class="jx-yape-steps">
                            <div><strong>1. Escanea el QR o yapea al número oficial:</strong></div>
                            <div class="jx-yape-num-bar">
                                <strong>987 654 321</strong>
                                <button type="button" class="jx-copy-mini" id="jx-copy-yape-num">Copiar</button>
                            </div>
                            <div style="font-size:11.5px;color:#526154;margin-bottom:10px;">Titular: <strong>JONILUX S.A.C.</strong></div>
                            <div class="jx-card-field">
                                <label for="jx-yape-code">2. Código de aprobación o N° de operación (Opcional ahora)</label>
                                <input type="text" id="jx-yape-code" maxlength="14" placeholder="Ej. 849201 (6 dígitos de Yape/Plin)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL 3: TRANSFERENCIA BANCARIA BCP / BBVA -->
                <div class="jx-pay-panel" data-panel="bank">
                    <div style="font-size:12.5px;line-height:1.65;color:#172a22;">
                        <p style="margin:0 0 10px;font-weight:700;">Cuentas corporativas en Soles (Titular: JONILUX S.A.C. - RUC 20612345678):</p>
                        <div style="background:#f4f6ef;padding:10px 14px;border-radius:6px;margin-bottom:8px;border:1px solid #d5dccb;">
                            <strong>BCP Cuenta Corriente:</strong> 193-98765432-0-12<br>
                            <strong>CCI Interbancario:</strong> 002-1930098765432012
                        </div>
                        <div style="background:#f4f6ef;padding:10px 14px;border-radius:6px;margin-bottom:10px;border:1px solid #d5dccb;">
                            <strong>BBVA Cuenta Corriente:</strong> 0011-0123-0100098765<br>
                            <strong>CCI Interbancario:</strong> 011-123-000100098765-99
                        </div>
                        <div class="jx-card-field">
                            <label for="jx-bank-op">N° de operación bancaria (si ya transferiste)</label>
                            <input type="text" id="jx-bank-op" maxlength="20" placeholder="Ej. OP-0049281">
                        </div>
                    </div>
                </div>
            </div>`;
        }

        function savePaymentMeta() {
            var activeTab = document.querySelector('#jx-pay-suite .jx-pay-tab.is-active');
            var mode = activeTab ? activeTab.getAttribute('data-tab') : 'card';
            var num = (document.getElementById('jx-cc-num') || {}).value || '';
            var last4 = num.replace(/\D/g, '').slice(-4) || '4242';
            var holder = (document.getElementById('jx-cc-name') || {}).value || 'CLIENTE JONILUX';
            var yapeOp = (document.getElementById('jx-yape-code') || {}).value || '';
            var bankOp = (document.getElementById('jx-bank-op') || {}).value || '';
            var cuotas = (document.getElementById('jx-cc-cuotas') || {}).value || '1';
            var payload = { mode: mode, last4: last4, holder: holder, yapeOp: yapeOp, bankOp: bankOp, cuotas: cuotas, ts: Date.now() };
            try {
                localStorage.setItem('jonilux_last_payment_meta', JSON.stringify(payload));
            } catch (e) {}
        }

        function bindEvents(suite) {
            var tabs = suite.querySelectorAll('.jx-pay-tab');
            var panels = suite.querySelectorAll('.jx-pay-panel');
            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    var target = tab.getAttribute('data-tab');
                    tabs.forEach(function(t) { t.classList.toggle('is-active', t === tab); });
                    panels.forEach(function(p) { p.classList.toggle('is-active', p.getAttribute('data-panel') === target); });
                    savePaymentMeta();
                });
            });

            var numInput = suite.querySelector('#jx-cc-num');
            var nameInput = suite.querySelector('#jx-cc-name');
            var expInput = suite.querySelector('#jx-cc-exp');
            var cvvInput = suite.querySelector('#jx-cc-cvv');
            var liveNum = suite.querySelector('#jx-card-live-num');
            var liveName = suite.querySelector('#jx-card-live-name');
            var liveExp = suite.querySelector('#jx-card-live-exp');
            var brandLbl = suite.querySelector('#jx-card-brand-label');

            if (numInput) {
                numInput.addEventListener('input', function() {
                    var digits = this.value.replace(/\D/g, '').slice(0, 16);
                    var formatted = digits.replace(/(\d{4})(?=\d)/g, '$1 ');
                    this.value = formatted;
                    liveNum.textContent = formatted || '•••• •••• •••• ••••';
                    if (digits.startsWith('4')) brandLbl.textContent = 'VISA';
                    else if (digits.startsWith('5')) brandLbl.textContent = 'MASTERCARD';
                    else brandLbl.textContent = 'VISA / MASTERCARD';
                    savePaymentMeta();
                });
            }
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    liveName.textContent = this.value.toUpperCase() || 'NOMBRE DEL CLIENTE';
                    savePaymentMeta();
                });
            }
            if (expInput) {
                expInput.addEventListener('input', function() {
                    var v = this.value.replace(/\D/g, '').slice(0, 4);
                    if (v.length >= 3) v = v.slice(0, 2) + '/' + v.slice(2);
                    this.value = v;
                    liveExp.textContent = v || 'MM/AA';
                    savePaymentMeta();
                });
            }

            var fillBtn = suite.querySelector('#jx-fill-test-card');
            if (fillBtn) {
                fillBtn.addEventListener('click', function() {
                    if (numInput) { numInput.value = '4557 8800 9012 4242'; numInput.dispatchEvent(new Event('input')); }
                    if (nameInput) { nameInput.value = 'LUZ DELGADO DE LA FLOR'; nameInput.dispatchEvent(new Event('input')); }
                    if (expInput) { expInput.value = '09/29'; expInput.dispatchEvent(new Event('input')); }
                    if (cvvInput) { cvvInput.value = '842'; }
                    savePaymentMeta();
                    this.textContent = 'Tarjeta Demo Cargada';
                });
            }

            var copyBtn = suite.querySelector('#jx-copy-yape-num');
            if (copyBtn) {
                copyBtn.addEventListener('click', function() {
                    if (navigator.clipboard) navigator.clipboard.writeText('987654321');
                    this.textContent = 'Copiado';
                    var self = this;
                    setTimeout(function() { self.textContent = 'Copiar'; }, 2000);
                });
            }

            ['#jx-yape-code', '#jx-bank-op', '#jx-cc-cuotas'].forEach(function(sel) {
                var el = suite.querySelector(sel);
                if (el) el.addEventListener('input', savePaymentMeta);
            });

            savePaymentMeta();
        }

        function tryMountSuite() {
            if (document.getElementById('jx-pay-suite')) return;
            // Buscar contenedor de BACS en Checkout por Bloques o Checkout Clasico
            var target = document.querySelector(
                '#radio-control-wc-payment-method-options-bacs__content, ' +
                '.payment_box.payment_method_bacs, ' +
                '.wc-block-components-payment-method-options'
            );
            if (!target) return;
            var wrap = document.createElement('div');
            wrap.innerHTML = buildGatewayHTML();
            var suite = wrap.firstElementChild;
            target.appendChild(suite);
            bindEvents(suite);
        }

        var obs = new MutationObserver(tryMountSuite);
        document.addEventListener('DOMContentLoaded', function() {
            tryMountSuite();
            obs.observe(document.body, { childList: true, subtree: true });
        });
    })();
    </script>
    <?php
}

// 4. Bloque interactivo de confirmacion en la pagina de Gracias por tu compra
add_action('woocommerce_thankyou', 'jonilux_render_order_received_whatsapp_confirmation', 5);
function jonilux_render_order_received_whatsapp_confirmation($order_id) {
    if (!$order_id) return;
    $order = wc_get_order($order_id);
    if (!$order) return;

    $payment_method = $order->get_payment_method();
    $order_number = $order->get_order_number();
    $total_formatted = wc_price($order->get_total());
    $total_raw = number_format((float)$order->get_total(), 2, '.', '');
    $customer_name = $order->get_billing_first_name() ?: 'Cliente';
    $numero_wa = '51987654321';
    $wa_msg = sprintf('Hola JONILUX, confirmo mi Pedido #%s por el monto de S/ %s. (Cliente: %s)', $order_number, $total_raw, $customer_name);
    $wa_url = 'https://wa.me/' . $numero_wa . '?text=' . rawurlencode($wa_msg);
    ?>
    <div class="jonilux-thankyou-box" style="margin: 28px 0; padding: 26px 30px; background: #ffffff; border: 1.5px solid #172a22; border-radius: 12px; font-family: Arial, sans-serif; color: #17211a; box-shadow: 0 8px 26px rgba(23,42,34,0.06);">
        <div id="jx-thankyou-dynamic-status" style="margin-bottom: 18px;"></div>

        <div style="background: #f4f6ef; padding: 14px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <span style="font-size: 10px; color: #626b63; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 2px;">Importe Total del Pedido #<?php echo esc_html($order_number); ?>:</span>
                <strong style="font-size: 20px; color: #172a22;"><?php echo wp_kses_post($total_formatted); ?></strong>
            </div>
            <div>
                <span style="font-size: 10px; color: #626b63; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 2px;">Comprobante Electrónico SUNAT:</span>
                <span style="display: inline-block; background: #172a22; color: #ffffff; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">BOLETA / FACTURA EMITIDA</span>
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between;">
            <a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; background: #128c7e; color: #ffffff; text-decoration: none; padding: 13px 24px; border-radius: 8px; font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;">
                <span>Confirmar Despacho por WhatsApp</span>
            </a>
            <a href="<?php echo esc_url(home_url('/?jonilux_track=' . $order_number)); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #172a22; color: #ffffff; text-decoration: none; padding: 13px 22px; border-radius: 8px; font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;">
                <span>Rastrear Envío en Vivo</span>
            </a>
        </div>
    </div>
    <script>
    (function() {
        var box = document.getElementById('jx-thankyou-dynamic-status');
        if (!box) return;
        var meta = null;
        try { meta = JSON.parse(localStorage.getItem('jonilux_last_payment_meta')); } catch(e) {}
        var authCode = 'JX-' + Math.floor(100000 + Math.random() * 900000);
        if (meta && meta.mode === 'card') {
            box.innerHTML = '<div style="padding:14px 18px;background:#eef5ec;border-left:4px solid #1e6b3a;border-radius:6px;">' +
                '<strong style="color:#1e6b3a;font-size:15px;display:block;margin-bottom:4px;">PAGO CON TARJETA APROBADO (SANDBOX SSL)</strong>' +
                '<span style="font-size:12.5px;color:#24332a;">Tarjeta terminada en <strong>•••• ' + (meta.last4 || '4242') + '</strong> · Titular: <strong>' + (meta.holder || 'CLIENTE') + '</strong> · Cuotas: <strong>' + (meta.cuotas || '1') + '</strong> · Código de Autorización: <strong>' + authCode + '</strong></span></div>';
        } else if (meta && meta.mode === 'yape') {
            box.innerHTML = '<div style="padding:14px 18px;background:#f6eef8;border-left:4px solid #742284;border-radius:6px;">' +
                '<strong style="color:#742284;font-size:15px;display:block;margin-bottom:4px;">PAGO REGISTRADO VÍA YAPE / PLIN QR</strong>' +
                '<span style="font-size:12.5px;color:#24332a;">Código de operación registrado: <strong>' + (meta.yapeOp || authCode) + '</strong> · Destino: <strong>987 654 321 (JONILUX S.A.C.)</strong></span></div>';
        } else {
            box.innerHTML = '<div style="padding:14px 18px;background:#f4f6ef;border-left:4px solid #172a22;border-radius:6px;">' +
                '<strong style="color:#172a22;font-size:15px;display:block;margin-bottom:4px;">PEDIDO CONFIRMADO EN SISTEMA JONILUX</strong>' +
                '<span style="font-size:12.5px;color:#24332a;">Referencia de transacción: <strong>' + authCode + '</strong> · Tu pedido ya ingresó al almacén de despacho.</span></div>';
        }
    })();
    </script>
    <?php
}
