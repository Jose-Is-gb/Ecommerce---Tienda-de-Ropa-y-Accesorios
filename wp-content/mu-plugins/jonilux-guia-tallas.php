<?php
/**
 * Plugin Name: JONILUX – Guía de Tallas
 * Description: Botón, pestaña y modal interactivo de Guía de Tallas en la ficha de producto (exclusivo para prendas de vestir, sin emojis, tipografía normalizada).
 */

defined('ABSPATH') || exit;

/**
 * Determina si el producto actual pertenece a categorías de ropa/prendas de vestir.
 */
function jonilux_is_clothing_product($product_id = null) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    if (!$product_id) {
        return false;
    }
    $clothing_slugs = array(
        'ropa', 'polos-y-camisetas', 'camisas', 'pantalones-y-jeans', 
        'pantalones', 'chaquetas-y-abrigos', 'hoodies-y-sudaderas', 
        'ropa-mujer', 'vestidos', 'tops-y-camisetas', 'faldas', 
        'shorts', 'sweaters-y-cardigans'
    );
    return has_term($clothing_slugs, 'product_cat', $product_id);
}

// 1. Agregar pestaña "Guía de Tallas" en la descripción del producto (solo para prendas)
add_filter('woocommerce_product_tabs', function ($tabs) {
    if (!jonilux_is_clothing_product()) {
        return $tabs;
    }
    $tabs['jonilux_size_guide'] = array(
        'title'    => 'Guía de Tallas',
        'priority' => 20,
        'callback' => 'jonilux_render_size_guide_content'
    );
    return $tabs;
}, 50);

// 2. Agregar botón "Guía de Tallas y Medidas" junto a la selección de producto (solo para prendas)
add_action('woocommerce_single_product_summary', function () {
    if (!jonilux_is_clothing_product()) {
        return;
    }
    ?>
    <div class="jonilux-size-guide-trigger-wrap">
        <button type="button" class="jonilux-size-guide-btn" id="jonilux-open-sizes">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.3 15.3l-6.6 6.6c-.4.4-1 .4-1.4 0l-11-11c-.2-.2-.3-.4-.3-.7V3c0-.6.4-1 1-1h7.2c.3 0 .5.1.7.3l11 11c.4.4.4 1 0 1.4z"></path>
                <circle cx="7.5" cy="7.5" r="1.5"></circle>
            </svg>
            <span>Guía de Tallas y Medidas</span>
        </button>
    </div>
    <?php
}, 25);

// 3. Renderizar contenido de las tablas de medidas oficiales
function jonilux_render_size_guide_content() {
    ?>
    <div class="jonilux-size-tables-container">
        <h3>Tabla de Medidas Oficiales JONILUX</h3>
        <p style="color:#666;font-size:13px;margin-bottom:18px;line-height:1.5;">Todas las medidas están expresadas en centímetros (cm). Si estás entre dos tallas, te recomendamos elegir la mayor para un corte urbano cómodo.</p>

        <div class="jonilux-size-tabs-nav">
            <button class="jonilux-tab-btn active" data-tab="polos">Polos Urbanos & Oversize</button>
            <button class="jonilux-tab-btn" data-tab="hoodies">Hoodies & Polerones</button>
            <button class="jonilux-tab-btn" data-tab="pantalones">Joggers & Pantalones Cargo</button>
        </div>

        <!-- Tabla Polos -->
        <div class="jonilux-tab-panel active" id="tab-polos">
            <table class="jonilux-size-table">
                <thead>
                    <tr>
                        <th>Talla</th>
                        <th>Pecho / Busto (cm)</th>
                        <th>Largo Total (cm)</th>
                        <th>Ancho de Espalda (cm)</th>
                        <th>Corte Recomendado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>S</strong></td><td>96 - 102</td><td>70</td><td>44</td><td>Regular / Semi-Oversize</td></tr>
                    <tr><td><strong>M</strong></td><td>104 - 110</td><td>73</td><td>47</td><td>Urban Fit Cómodo</td></tr>
                    <tr><td><strong>L</strong></td><td>112 - 118</td><td>76</td><td>50</td><td>Oversize Moderno</td></tr>
                    <tr><td><strong>XL</strong></td><td>120 - 128</td><td>79</td><td>53</td><td>Heavyweight Oversize</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Tabla Hoodies -->
        <div class="jonilux-tab-panel" id="tab-hoodies">
            <table class="jonilux-size-table">
                <thead>
                    <tr>
                        <th>Talla</th>
                        <th>Contorno de Pecho (cm)</th>
                        <th>Largo Total (cm)</th>
                        <th>Largo de Manga (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>S</strong></td><td>106 - 112</td><td>68</td><td>62</td></tr>
                    <tr><td><strong>M</strong></td><td>114 - 120</td><td>71</td><td>64</td></tr>
                    <tr><td><strong>L</strong></td><td>122 - 128</td><td>74</td><td>66</td></tr>
                    <tr><td><strong>XL</strong></td><td>130 - 138</td><td>77</td><td>68</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Tabla Pantalones -->
        <div class="jonilux-tab-panel" id="tab-pantalones">
            <table class="jonilux-size-table">
                <thead>
                    <tr>
                        <th>Talla</th>
                        <th>Equivalencia Perú</th>
                        <th>Cintura (cm)</th>
                        <th>Cadera (cm)</th>
                        <th>Largo (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>S</strong></td><td>28 - 30</td><td>72 - 78</td><td>96 - 100</td><td>98</td></tr>
                    <tr><td><strong>M</strong></td><td>30 - 32</td><td>80 - 86</td><td>102 - 106</td><td>101</td></tr>
                    <tr><td><strong>L</strong></td><td>32 - 34</td><td>88 - 94</td><td>108 - 112</td><td>104</td></tr>
                    <tr><td><strong>XL</strong></td><td>34 - 36</td><td>96 - 102</td><td>114 - 118</td><td>106</td></tr>
                </tbody>
            </table>
        </div>

        <div style="background:#f7f8f6;padding:14px 16px;border-radius:8px;margin-top:20px;border-left:4px solid #111a16;">
            <p style="margin:0;font-size:13px;line-height:1.5;color:#333;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px;margin-right:5px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <strong>Asesoría de tallas personalizada:</strong> Escríbenos por WhatsApp indicando tu estatura y peso aproximado, y nuestro asesor te indicará la talla perfecta.
            </p>
        </div>
    </div>
    <?php
}

// 4. Modal flotante de Guía de Tallas en wp_footer (solo para prendas)
add_action('wp_footer', function () {
    if (!is_product() || !jonilux_is_clothing_product()) return;
    ?>
    <div id="jonilux-size-modal" class="jonilux-size-modal-overlay" style="display:none;">
        <div class="jonilux-size-modal-box">
            <button class="jonilux-size-modal-close" id="jonilux-close-sizes" aria-label="Cerrar modal">&times;</button>
            <?php jonilux_render_size_guide_content(); ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var openBtn = document.getElementById('jonilux-open-sizes');
        var modal = document.getElementById('jonilux-size-modal');
        var closeBtn = document.getElementById('jonilux-close-sizes');

        if (openBtn && modal) {
            openBtn.addEventListener('click', function () {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        }
        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
        }
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }

        // Cambio de pestañas en la guía
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('jonilux-tab-btn')) {
                var targetId = e.target.getAttribute('data-tab');
                var container = e.target.closest('.jonilux-size-tables-container');
                if (!container) return;
                container.querySelectorAll('.jonilux-tab-btn').forEach(function (btn) { btn.classList.remove('active'); });
                container.querySelectorAll('.jonilux-tab-panel').forEach(function (panel) { panel.classList.remove('active'); });
                e.target.classList.add('active');
                var activePanel = container.querySelector('#tab-' + targetId);
                if (activePanel) activePanel.classList.add('active');
            }
        });
    });
    </script>
    <?php
});

// 5. Estilos de normalización tipográfica y acordeón
add_action('wp_head', function () {
    if (!is_product()) return;
    ?>
    <style>
        /* NORMALIZACIÓN TIPOGRÁFICA Y ESPACIOS DEL ACORDEÓN DE PRODUCTO */
        .wp-block-woocommerce-accordion-header,
        .accordion-item__heading {
            font-size: 14px !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-weight: 500 !important;
            line-height: 1.4 !important;
            margin: 0 !important;
            letter-spacing: 0.01em !important;
        }
        .accordion-item__toggle {
            font-size: 14px !important;
            font-family: inherit !important;
            font-weight: 500 !important;
            color: #111a16 !important;
            padding: 14px 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            background: none !important;
            border: none !important;
            cursor: pointer !important;
            text-align: left !important;
        }
        .accordion-item__toggle span {
            font-size: 14px !important;
            font-weight: 500 !important;
            color: #111a16 !important;
        }
        .accordion-item__toggle-icon {
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #555 !important;
        }
        .wp-block-woocommerce-accordion-item {
            border-bottom: 1px solid #e8eae6 !important;
        }
        .accordion-content__wrapper {
            padding: 10px 0 16px !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            color: #444 !important;
        }

        /* BOTÓN DISPARADOR DE GUÍA DE TALLAS */
        .jonilux-size-guide-trigger-wrap {
            margin: 12px 0 18px !important;
        }
        .jonilux-size-guide-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f4f6f1;
            border: 1px solid #d5dcce;
            color: #18241f;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
            letter-spacing: 0.02em;
        }
        .jonilux-size-guide-btn:hover {
            background: #111a16;
            color: #ffffff;
            border-color: #111a16;
        }
        .jonilux-size-guide-btn svg {
            flex-shrink: 0;
        }

        /* MODAL DE MEDIDAS */
        .jonilux-size-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            backdrop-filter: blur(4px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .jonilux-size-modal-box {
            background: #ffffff;
            border-radius: 12px;
            max-width: 760px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 35px 30px;
            position: relative;
            box-shadow: 0 15px 45px rgba(0, 0, 0, .25);
        }
        .jonilux-size-modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 28px;
            color: #888;
            cursor: pointer;
            line-height: 1;
            transition: color .2s;
        }
        .jonilux-size-modal-close:hover {
            color: #000;
        }
        .jonilux-size-tabs-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            overflow-x: auto;
            padding-bottom: 5px;
        }
        .jonilux-tab-btn {
            background: none;
            border: none;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -7px;
            transition: all .2s;
            white-space: nowrap;
        }
        .jonilux-tab-btn.active {
            color: #111a16;
            border-bottom-color: #111a16;
        }
        .jonilux-tab-panel {
            display: none;
        }
        .jonilux-tab-panel.active {
            display: block;
            animation: fadeIn .25s ease;
        }
        .jonilux-size-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 13px;
        }
        .jonilux-size-table th {
            background: #f8f9f7;
            color: #18241f;
            text-align: left;
            padding: 11px 14px;
            border: 1px solid #e2e5df;
            font-size: 12px;
            letter-spacing: .04em;
        }
        .jonilux-size-table td {
            padding: 11px 14px;
            border: 1px solid #e8eae6;
            color: #444;
        }
        .jonilux-size-table tr:hover td {
            background: #fafbf9;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <?php
});
