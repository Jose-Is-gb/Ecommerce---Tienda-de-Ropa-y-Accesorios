<?php
/**
 * Plugin Name: JONILUX – Registro y Cuentas de Compradores
 * Description: Habilita el registro publico de nuevos clientes en Mi Cuenta y en el Checkout, permite elegir contrasena propia al registrarse y estila la interfaz con diseno ordenado y limpio (sin emojis).
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Forzar opciones de registro de clientes en WooCommerce y WordPress
add_filter('option_users_can_register', '__return_true');
add_filter('option_woocommerce_enable_myaccount_registration', fn() => 'yes');
add_filter('option_woocommerce_enable_signup_and_login_from_checkout', fn() => 'yes');
add_filter('option_woocommerce_registration_generate_password', fn() => 'no');
add_filter('option_woocommerce_registration_generate_username', fn() => 'no');

// 2. Texto formal de privacidad en registro (espanol)
add_filter('option_woocommerce_registration_privacy_policy_text', function ($text) {
    return 'Sus datos personales seran procesados conforme a la Ley N 29733 para gestionar su cuenta y pedidos en JONILUX, segun lo detallado en nuestra [privacy_policy].';
});

// 3. Estilos tipograficos ordenados para el formulario de Acceso y Registro en Mi Cuenta
add_action('wp_head', 'jonilux_estilos_mi_cuenta');
function jonilux_estilos_mi_cuenta() {
    if (!function_exists('is_account_page') || !is_account_page()) {
        return;
    }
    ?>
    <style id="jonilux-cuentas-css">
        /* Contenedor principal de Mi Cuenta */
        .woocommerce-account .woocommerce {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: Arial, Helvetica, sans-serif;
            color: #17211a;
        }

        /* Cuadricula ordenada para Iniciar Sesion y Registro */
        .woocommerce-account .u-columns.col2-set {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 30px;
            background: #ffffff;
            padding: 35px 40px;
            border: 1px solid #dcded7;
            border-radius: 4px;
        }

        .woocommerce-account .u-column1,
        .woocommerce-account .u-column2 {
            float: none !important;
            width: 100% !important;
        }

        .woocommerce-account .u-column1 h2,
        .woocommerce-account .u-column2 h2 {
            font-size: 16px !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #111614;
            border-bottom: 1px solid #dcded7;
            padding-bottom: 12px;
            margin-bottom: 22px !important;
        }

        /* Formularios y campos */
        .woocommerce-account form.login,
        .woocommerce-account form.register {
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .woocommerce-account .woocommerce-form-row {
            margin-bottom: 18px !important;
        }

        .woocommerce-account .woocommerce-form-row label {
            display: block;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #17211a;
            margin-bottom: 6px !important;
        }

        .woocommerce-account .woocommerce-Input,
        .woocommerce-account input[type="text"],
        .woocommerce-account input[type="email"],
        .woocommerce-account input[type="password"] {
            width: 100% !important;
            padding: 12px 14px !important;
            font-size: 13px !important;
            border: 1px solid #c7cbc4 !important;
            border-radius: 4px !important;
            background: #ffffff !important;
            color: #111614 !important;
            box-sizing: border-box !important;
        }

        .woocommerce-account .woocommerce-Input:focus,
        .woocommerce-account input:focus {
            border-color: #111614 !important;
            outline: none !important;
        }

        /* Botones de formulario */
        .woocommerce-account .woocommerce-button,
        .woocommerce-account .woocommerce-form-login__submit,
        .woocommerce-account .woocommerce-form-register__submit {
            background: #111614 !important;
            color: #ffffff !important;
            border: none !important;
            padding: 13px 26px !important;
            border-radius: 4px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: 0.12em !important;
            text-transform: uppercase !important;
            cursor: pointer !important;
            transition: background 0.2s !important;
            display: inline-block !important;
            width: 100% !important;
            margin-top: 10px !important;
        }

        .woocommerce-account .woocommerce-button:hover {
            background: #2b3b33 !important;
        }

        /* Aviso de privacidad en registro */
        .woocommerce-account .woocommerce-privacy-policy-text {
            font-size: 11px !important;
            color: #626b63 !important;
            line-height: 1.5 !important;
            margin: 14px 0 !important;
        }

        /* Recordar sesion y olvide contrasena */
        .woocommerce-account .woocommerce-form-login__rememberme {
            font-size: 12px;
            color: #626b63;
            margin: 10px 0 0 0;
            display: block;
        }

        .woocommerce-account .woocommerce-LostPassword {
            margin-top: 14px;
            font-size: 12px;
        }

        .woocommerce-account .woocommerce-LostPassword a {
            color: #111614;
            text-decoration: underline;
        }

        /* Adaptabilidad movil */
        @media (max-width: 768px) {
            .woocommerce-account .u-columns.col2-set {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 25px 20px;
            }
        }
    </style>
    <?php
}

