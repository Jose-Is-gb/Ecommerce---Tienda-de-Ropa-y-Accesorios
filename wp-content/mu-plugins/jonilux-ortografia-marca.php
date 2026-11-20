<?php
/**
 * Plugin Name: JONILUX – Correccion de Ortografia, Acentos y Marca
 * Description: Corrige automaticamente palabras mal escritas con caracteres corruptos (mojibake) en descripciones de productos, fichas y categorias, asegurando acentuacion limpia en espanol y forzando la marca JONILUX en todo el sitio.
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

/**
 * Normaliza y repara caracteres corruptos y palabras en espanol
 */
function jonilux_reparar_texto($text) {
    if (empty($text) || !is_string($text)) {
        return $text;
    }

    // 1. Correccion estricta de la marca e idioma clave
    $text = str_replace('L U N A R I S', 'J O N I L U X', $text);
    $text = str_replace('LUNARIS', 'JONILUX', $text);
    $text = str_replace('Lunaris', 'Jonilux', $text);
    $text = str_replace(['My account', 'My Account'], 'Mi cuenta', $text);
    if (trim($text) === 'Cart' || trim($text) === 'CART') {
        $text = 'Carrito de compras';
    } elseif (trim($text) === 'Checkout' || trim($text) === 'CHECKOUT') {
        $text = 'Finalizar compra';
    }

    // 2. Mapeo de secuencias mojibake de codepages comunes (CP437, Latin1, UTF-8 mal interpretado)
    $mojibake = [
        '├┤' => 'ó',
        '├║' => 'ú',
        '├í' => 'á',
        '├®' => 'é',
        '├¡' => 'í',
        '├▒' => 'ñ',
        '├ó' => 'ó',
        '├│' => 'ó',
        '├ô' => 'Ó',
        '├Ü' => 'Ú',
        '├ü' => 'Á',
        '├ë' => 'É',
        '├CD' => 'Í',
        '├æ' => 'Ñ',
        'Ã¡' => 'á',
        'Ã©' => 'é',
        'Ã­' => 'í',
        'Ã³' => 'ó',
        'Ãº' => 'ú',
        'Ã±' => 'ñ',
        'Ã'  => 'í',
        'â€¢' => '•',
        'â€”' => '—',
        'â€“' => '–'
    ];
    $text = strtr($text, $mojibake);

    // 3. Diccionario ortografico para terminos de ecommerce y prendas.
    // Cada entrada: [inicio, variante sin tilde, final, palabra correcta]. Solo se corrige la palabra
    // completa sin tilde o con caracteres corruptos no ASCII en el lugar del acento. Las palabras
    // legitimas (diseñado, garantizada, Perfil, Envasado) no coinciden con estos patrones.
    static $reglas = null;
    if ($reglas === null) {
        $basura = '[^\sA-Za-z0-9<>,.;:!?()\/]{1,3}';
        $palabras = [
            ['algod', 'o', 'n', 'algodón'], ['ventilaci', 'o', 'n', 'ventilación'],
            ['confecci', 'o', 'n', 'confección'], ['colecci', 'o', 'n', 'colección'],
            ['dise', 'n', 'o', 'diseño'], ['garant', 'i', 'a', 'garantía'],
            ['pol', 'i', 'tica', 'política'], ['edici', 'o', 'n', 'edición'],
            ['atenci', 'o', 'n', 'atención'], ['direcci', 'o', 'n', 'dirección'],
            ['tel', 'e', 'fono', 'teléfono'], ['t', 'e', 'rminos', 'términos'],
            ['descripci', 'o', 'n', 'descripción'], ['devoluci', 'o', 'n', 'devolución'],
            ['env', 'i', 'o', 'envío'], ['env', 'i', 'os', 'envíos'],
            ['promoci', 'o', 'n', 'promoción'], ['est', 'a', 'ndar', 'estándar'],
            ['despu', 'e', 's', 'después'], ['h', 'a', 'biles', 'hábiles'],
            ['p', 'a', 'gina', 'página'], ['p', null, 'blica', 'pública'], ['p', null, 'blicas', 'públicas'],
            ['n', 'u', 'mero', 'número'], ['c', 'o', 'digo', 'código'],
            ['inal', 'a', 'mbrico', 'inalámbrico'], ['cat', 'a', 'logo', 'catálogo'],
            ['b', 'e', 'isbol', 'béisbol'], ['joyer', 'i', 'a', 'joyería'],
            ['reclamaci', 'o', 'n', 'reclamación'], ['aut', 'e', 'ntico', 'auténtico'],
            ['cl', 'a', 'sico', 'clásico'], ['c', 'o', 'modo', 'cómodo'],
            ['per', 'u', '', 'perú'],
        ];
        $reglas = [];
        foreach ($palabras as $p) {
            $medio = '(?:' . ($p[1] !== null ? preg_quote($p[1], '/') . '|' : '') . $basura . ')';
            $minuscula = '/\b' . $p[0] . $medio . $p[2] . '\b/u';
            $reglas[$minuscula] = $p[3];
            $inicial = mb_strtoupper(mb_substr($p[0], 0, 1)) . mb_substr($p[0], 1);
            $mayuscula = '/\b' . $inicial . $medio . $p[2] . '\b/u';
            $reglas[$mayuscula] = mb_strtoupper(mb_substr($p[3], 0, 1)) . mb_substr($p[3], 1);
        }
        $reglas['/\bTalla\s+(?:u|' . $basura . ')nica\b/u'] = 'Talla única';
        $reglas['/\btalla\s+(?:u|' . $basura . ')nica\b/u'] = 'talla única';
    }

    $resultado = preg_replace(array_keys($reglas), array_values($reglas), $text);
    return $resultado === null ? $text : $resultado;
}

/**
 * Limpia el HTML preservando etiquetas, atributos y enlaces
 */
function jonilux_limpiar_html_seguro($html) {
    if (empty($html) || !is_string($html)) {
        return $html;
    }

    // Dividir en fragmentos: etiquetas HTML vs texto visible
    $partes = preg_split('/(<[^>]+>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    if (!is_array($partes)) {
        return $html;
    }

    for ($i = 0; $i < count($partes); $i += 2) {
        $partes[$i] = jonilux_reparar_texto($partes[$i]);
    }

    return implode('', $partes);
}

// 1. Forzar nombre de la tienda en todo WordPress
add_filter('option_blogname', fn() => 'JONILUX');
add_filter('bloginfo', function($val, $show) {
    return ($show === 'name') ? 'JONILUX' : $val;
}, 99, 2);

// 2. Filtros de WooCommerce y WordPress para contenido
add_filter('the_content', 'jonilux_limpiar_html_seguro', 999);
add_filter('the_title', 'jonilux_reparar_texto', 999);
add_filter('the_excerpt', 'jonilux_limpiar_html_seguro', 999);
add_filter('woocommerce_product_get_description', 'jonilux_limpiar_html_seguro', 999);
add_filter('woocommerce_product_get_short_description', 'jonilux_limpiar_html_seguro', 999);
add_filter('woocommerce_product_get_name', 'jonilux_reparar_texto', 999);
add_filter('woocommerce_short_description', 'jonilux_limpiar_html_seguro', 999);
add_filter('term_name', 'jonilux_reparar_texto', 999);
add_filter('term_description', 'jonilux_limpiar_html_seguro', 999);
add_filter('render_block', 'jonilux_limpiar_html_seguro', 999);

// 3. Buffer de salida global en frontend para garantizar 100% de cobertura
add_action('template_redirect', function () {
    if (!is_admin()) {
        ob_start('jonilux_limpiar_html_seguro');
    }
}, 1);
