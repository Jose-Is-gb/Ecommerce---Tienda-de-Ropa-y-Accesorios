<?php
/**
 * Plugin Name: JONILUX – Filtros Avanzados de Catalogo y Cookies
 * Description: Procesa los filtros combinados de categoria, rango de precio, busqueda y orden en el catalogo (/shop/ y bloques de coleccion) y expone cookies HTTP reales para auditoria academica.
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Normalizar parametro jx_price (0-60, 60-120, 120-180, 180+) a min_price y max_price
add_action('init', function () {
    if (!empty($_GET['jx_price'])) {
        $range = sanitize_text_field(wp_unslash($_GET['jx_price']));
        if ($range === '0-60') {
            $_GET['max_price'] = '60';
        } elseif ($range === '60-120') {
            $_GET['min_price'] = '60';
            $_GET['max_price'] = '120';
        } elseif ($range === '120-180') {
            $_GET['min_price'] = '120';
            $_GET['max_price'] = '180';
        } elseif ($range === '180+') {
            $_GET['min_price'] = '180';
        }
    }
}, 1);

// 2. Aplicar filtros de categoria (jx_cat), busqueda (jx_q), precio (min_price/max_price) y orden a consultas de productos
function jonilux_apply_catalog_filters_to_query_args($args) {
    // Categoria
    if (!empty($_GET['jx_cat'])) {
        $cat_slug = sanitize_title(wp_unslash($_GET['jx_cat']));
        if ($cat_slug !== '') {
            if (!isset($args['tax_query']) || !is_array($args['tax_query'])) {
                $args['tax_query'] = [];
            }
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => [$cat_slug],
            ];
        }
    }

    // Busqueda por texto
    if (!empty($_GET['jx_q'])) {
        $args['s'] = sanitize_text_field(wp_unslash($_GET['jx_q']));
    }

    // Rango de precios en Soles
    $min = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float) $_GET['min_price'] : null;
    $max = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float) $_GET['max_price'] : null;
    if ($min !== null || $max !== null) {
        if (!isset($args['meta_query']) || !is_array($args['meta_query'])) {
            $args['meta_query'] = [];
        }
        if ($min !== null && $max !== null) {
            $args['meta_query'][] = [
                'key'     => '_price',
                'value'   => [$min, $max],
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL(10,2)',
            ];
        } elseif ($min !== null) {
            $args['meta_query'][] = [
                'key'     => '_price',
                'value'   => $min,
                'compare' => '>=',
                'type'    => 'DECIMAL(10,2)',
            ];
        } elseif ($max !== null) {
            $args['meta_query'][] = [
                'key'     => '_price',
                'value'   => $max,
                'compare' => '<=',
                'type'    => 'DECIMAL(10,2)',
            ];
        }
    }

    // Ordenamiento
    $orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : '';
    if ($orderby === 'price') {
        $args['meta_key'] = '_price';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
    } elseif ($orderby === 'price-desc') {
        $args['meta_key'] = '_price';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } elseif ($orderby === 'popularity') {
        $args['meta_key'] = 'total_sales';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } elseif ($orderby === 'date' || $orderby === '') {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    return $args;
}

add_filter('query_loop_block_query_vars', function ($query, $block) {
    if (isset($query['post_type']) && $query['post_type'] === 'product') {
        $query = jonilux_apply_catalog_filters_to_query_args($query);
    }
    return $query;
}, 99, 2);

add_action('woocommerce_product_query', function ($q) {
    $args = jonilux_apply_catalog_filters_to_query_args([
        'tax_query'  => $q->get('tax_query'),
        'meta_query' => $q->get('meta_query'),
    ]);
    if (!empty($args['tax_query'])) {
        $q->set('tax_query', $args['tax_query']);
    }
    if (!empty($args['meta_query'])) {
        $q->set('meta_query', $args['meta_query']);
    }
    if (!empty($args['s'])) {
        $q->set('s', $args['s']);
    }
    if (!empty($args['orderby'])) {
        $q->set('orderby', $args['orderby']);
        $q->set('order', $args['order']);
    }
    if (!empty($args['meta_key'])) {
        $q->set('meta_key', $args['meta_key']);
    }
}, 99);

// 3. Estilos de la barra de filtros del catalogo
add_action('wp_head', function () {
    ?>
    <style id="jonilux-filtros-css">
        .jx-cat-header { background: #e9ecdf; border-bottom: 1px solid #d9dfce; padding: 28px clamp(20px, 4vw, 60px) 32px !important; }
        .jx-cat-header-top { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 18px; }
        .jx-cat-header h1 { font-size: clamp(28px, 3.5vw, 44px) !important; margin: 4px 0 0 !important; }
        .jx-cat-header-sub { margin: 0 !important; font-size: 13px !important; color: #526154; max-width: 420px; }
        .jx-filter-pills { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 18px 0 !important; }
        .jx-filter-pills a { border-radius: 999px !important; padding: 7px 16px !important; min-height: 36px !important; font-size: 12px !important; font-weight: 600 !important; background: #fff !important; border: 1px solid #cad2c0 !important; color: #172a22 !important; transition: all .15s !important; }
        .jx-filter-pills a.is-active, .jx-filter-pills a[aria-current="page"], .jx-filter-pills a:hover { background: #172a22 !important; color: #fff !important; border-color: #172a22 !important; }
        .jx-filter-bar { display: grid; grid-template-columns: 1.3fr 1fr 1fr 1fr auto; gap: 12px; align-items: end; background: #fff; padding: 16px 18px; border-radius: 12px; border: 1px solid #d5dccb; box-shadow: 0 4px 16px rgba(23, 42, 34, .05); }
        .jx-filter-field { display: flex; flex-direction: column; gap: 5px; }
        .jx-filter-field label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #4b5b4e; }
        .jx-filter-field input, .jx-filter-field select { height: 42px; padding: 0 12px; border: 1px solid #cad2c0; border-radius: 8px; background: #f8f9f5; font: 500 13px var(--lunaris-font-body, Arial, sans-serif); color: #172a22; width: 100%; box-sizing: border-box; }
        .jx-filter-field input:focus, .jx-filter-field select:focus { border-color: #172a22; background: #fff; outline: none; }
        .jx-filter-actions { display: flex; gap: 8px; align-items: center; }
        .jx-filter-submit { height: 42px; padding: 0 22px; border: 0; border-radius: 8px; background: #172a22; color: #fff; font: 700 12px var(--lunaris-font-body, Arial, sans-serif); letter-spacing: .06em; text-transform: uppercase; cursor: pointer; transition: background .15s; }
        .jx-filter-submit:hover { background: #2f4535; }
        .jx-filter-reset { height: 42px; padding: 0 14px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #c5cdc0; border-radius: 8px; background: #f4f6ef; color: #172a22; font: 600 12px var(--lunaris-font-body, Arial, sans-serif); text-decoration: none; }
        .jx-filter-reset:hover { background: #e5eadc; }
        .lunaris-catalog-toolbar { display: none !important; }
        @media (max-width: 960px) {
            .jx-filter-bar { grid-template-columns: 1fr 1fr; }
            .jx-filter-actions { grid-column: 1 / -1; }
            .jx-filter-submit { flex: 1; }
        }
        @media (max-width: 560px) {
            .jx-filter-bar { grid-template-columns: 1fr; }
        }
    </style>
    <?php
}, 99);
