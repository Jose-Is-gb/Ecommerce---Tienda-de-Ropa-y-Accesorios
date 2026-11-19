<?php
/**
 * Plugin Name: JONILUX – Experiencia Streetwear y Moda Urbana
 * Description: Potencia la experiencia de comercio electronico de ropa urbana: incorpora lista de deseos (Wishlist), modulo "Completa tu outfit", badges de coleccion (Oversize / Nuevo Drop) y alertas de edicion limitada (sin emojis).
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Inyectar boton de Favoritos (Wishlist) y Badges en la cabecera, ficha y catalogo
add_action('wp_footer', 'jonilux_streetwear_drawer_html');
add_action('wp_head', 'jonilux_streetwear_styles');
add_action('wp_footer', 'jonilux_streetwear_scripts');

// 2. Anadir modulo "COMPLETA TU OUTFIT" y alertas de stock en la ficha de producto
add_action('woocommerce_single_product_summary', 'jonilux_streetwear_product_perks', 25);
add_action('woocommerce_after_single_product_summary', 'jonilux_streetwear_completa_outfit', 15);

// 3. Boton de wishlist en la ficha de producto junto al boton de comprar
add_action('woocommerce_after_add_to_cart_button', 'jonilux_streetwear_single_wishlist_btn');

function jonilux_streetwear_single_wishlist_btn() {
    global $product;
    if (!$product) return;
    $id    = $product->get_id();
    $title = esc_attr($product->get_name());
    $price = wc_price($product->get_price());
    $img   = wp_get_attachment_image_url($product->get_image_id(), 'medium');
    $url   = esc_url(get_permalink($id));
    ?>
    <button type="button" class="jonilux-btn-wishlist-single" 
            data-id="<?php echo $id; ?>" 
            data-title="<?php echo $title; ?>" 
            data-price="<?php echo esc_attr($price); ?>" 
            data-image="<?php echo esc_url($img); ?>" 
            data-url="<?php echo $url; ?>"
            aria-label="Guardar en Favoritos">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        <span>Guardar en Favoritos</span>
    </button>
    <?php
}

// Alertas de Stock de Coleccion y Envio Express
function jonilux_streetwear_product_perks() {
    ?>
    <div class="jonilux-streetwear-perks">
        <div class="jonilux-perk-badge stock">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>Edición urbana limitada: Stock reducido por talla</span>
        </div>
        <div class="jonilux-perk-badge shipping">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <span>Envío Express Lima: 24 a 48 hrs hábiles</span>
        </div>
    </div>
    <?php
}

// Modulo "COMPLETA TU OUTFIT" (Cross-selling de moda urbana)
function jonilux_streetwear_completa_outfit() {
    global $product;
    if (!$product) return;

    $current_id = $product->get_id();
    $cats = wp_get_post_terms($current_id, 'product_cat', ['fields' => 'slugs']);

    // Sugerir prendas complementarias segun categoria
    $target_cat = 'accesorios';
    if (in_array('accesorios', $cats)) {
        $target_cat = 'hombre';
    } elseif (in_array('hombre', $cats)) {
        $target_cat = 'accesorios';
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 2,
        'post__not_in'   => [$current_id],
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => [$target_cat],
            ]
        ]
    ];
    $query = new WP_Query($args);
    if (!$query->have_posts()) {
        $query = new WP_Query(['post_type' => 'product', 'posts_per_page' => 2, 'post__not_in' => [$current_id]]);
    }

    if ($query->have_posts()) :
        ?>
        <div class="jonilux-outfit-container">
            <div class="jonilux-outfit-heading">
                <span class="jonilux-outfit-eyebrow">STREETWEAR LOOKBOOK</span>
                <h3 class="jonilux-outfit-title">COMPLETA TU OUTFIT</h3>
                <p class="jonilux-outfit-subtitle">Combina esta prenda con estos esenciales urbanos seleccionados:</p>
            </div>
            <div class="jonilux-outfit-grid">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    global $product;
                    $item_id = $product->get_id();
                    $img = wp_get_attachment_image_url($product->get_image_id(), 'medium');
                ?>
                    <div class="jonilux-outfit-card">
                        <a href="<?php the_permalink(); ?>" class="jonilux-outfit-thumb">
                            <img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>">
                            <span class="jonilux-outfit-tag">RECOMENDADO</span>
                        </a>
                        <div class="jonilux-outfit-info">
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="jonilux-outfit-price"><?php echo $product->get_price_html(); ?></span>
                            <a href="<?php the_permalink(); ?>" class="jonilux-outfit-btn">Ver prenda</a>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
    endif;
}

// Drawer lateral de Lista de Deseos
function jonilux_streetwear_drawer_html() {
    ?>
    <div id="jonilux-wishlist-overlay" class="jonilux-wishlist-overlay" aria-hidden="true"></div>
    <aside id="jonilux-wishlist-drawer" class="jonilux-wishlist-drawer" aria-labelledby="jonilux-wishlist-title" role="dialog" aria-modal="true">
        <div class="jonilux-wishlist-header">
            <div class="jonilux-wishlist-title-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <h3 id="jonilux-wishlist-title">MIS FAVORITOS</h3>
            </div>
            <button type="button" id="jonilux-wishlist-close" class="jonilux-wishlist-close-btn" aria-label="Cerrar favoritos">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div id="jonilux-wishlist-items" class="jonilux-wishlist-body">
            <!-- Renderizado dinamicamente por JavaScript -->
        </div>

        <div class="jonilux-wishlist-footer">
            <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/')); ?>" class="jonilux-wishlist-shop-btn">
                Explorar Catálogo Completo
            </a>
        </div>
    </aside>
    <?php
}

// Estilos Streetwear
function jonilux_streetwear_styles() {
    ?>
    <style id="jonilux-streetwear-css">
        /* Tipografia y estetica urbana en titulos */
        h1.wp-block-post-title,
        .entry-title,
        .wp-block-heading,
        .woocommerce-loop-product__title {
            font-family: var(--lunaris-font-display, "Lunaris Inter", Arial, sans-serif) !important;
            font-weight: 700 !important;
            letter-spacing: -0.025em !important;
            text-transform: uppercase !important;
        }

        /* Alertas de stock y envio en ficha de producto */
        .jonilux-streetwear-perks {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 16px 0 20px 0;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid #dcded3;
            border-radius: 6px;
        }
        .jonilux-perk-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }
        .jonilux-perk-badge.stock { color: #8b2b2b; }
        .jonilux-perk-badge.shipping { color: #172a22; }

        /* Boton de favoritos en ficha de producto */
        .jonilux-btn-wishlist-single {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #ffffff;
            color: #172a22;
            border: 1px solid #172a22;
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
            width: 100%;
        }
        .jonilux-btn-wishlist-single:hover,
        .jonilux-btn-wishlist-single.is-active {
            background: #172a22;
            color: #ffffff;
        }
        .jonilux-btn-wishlist-single.is-active svg {
            fill: #e74c3c;
            stroke: #e74c3c;
        }

        /* Modulo "COMPLETA TU OUTFIT" */
        .jonilux-outfit-container {
            margin: 40px 0;
            padding: 28px;
            background: #ffffff;
            border: 1px solid #dcded3;
            border-radius: 8px;
        }
        .jonilux-outfit-heading {
            margin-bottom: 20px;
            border-bottom: 1px solid #edf0e6;
            padding-bottom: 14px;
        }
        .jonilux-outfit-eyebrow {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.15em;
            color: #59645b;
            text-transform: uppercase;
            display: block;
            margin-bottom: 4px;
        }
        .jonilux-outfit-title {
            font-size: 18px !important;
            font-weight: 800 !important;
            letter-spacing: -0.02em !important;
            color: #172a22 !important;
            margin: 0 0 6px 0 !important;
            text-transform: uppercase;
        }
        .jonilux-outfit-subtitle {
            font-size: 13px;
            color: #59645b;
            margin: 0;
        }
        .jonilux-outfit-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        @media (max-width: 600px) {
            .jonilux-outfit-grid { grid-template-columns: 1fr; }
        }
        .jonilux-outfit-card {
            display: flex;
            gap: 16px;
            align-items: center;
            border: 1px solid #e8eae5;
            padding: 14px;
            border-radius: 6px;
            background: #fafbfa;
            transition: all 0.2s ease;
        }
        .jonilux-outfit-card:hover {
            border-color: #172a22;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
        .jonilux-outfit-thumb {
            position: relative;
            width: 80px;
            height: 96px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 4px;
            background: #eee;
        }
        .jonilux-outfit-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .jonilux-outfit-tag {
            position: absolute;
            bottom: 4px;
            left: 4px;
            background: #111614;
            color: #fff;
            font-size: 7px;
            font-weight: 800;
            padding: 2px 4px;
            border-radius: 2px;
            letter-spacing: 0.05em;
        }
        .jonilux-outfit-info h4 {
            font-size: 13px !important;
            font-weight: 700 !important;
            margin: 0 0 6px 0 !important;
            line-height: 1.3;
        }
        .jonilux-outfit-info h4 a {
            color: #172a22;
            text-decoration: none;
        }
        .jonilux-outfit-price {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #172a22;
            margin-bottom: 8px;
        }
        .jonilux-outfit-btn {
            display: inline-block;
            background: #172a22;
            color: #ffffff !important;
            padding: 6px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-decoration: none;
            border-radius: 3px;
            transition: background 0.18s ease;
        }
        .jonilux-outfit-btn:hover { background: #2f4535; }

        /* Drawer de Favoritos (Wishlist) */
        .jonilux-wishlist-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 99998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .jonilux-wishlist-overlay.is-active {
            opacity: 1;
            visibility: visible;
        }
        .jonilux-wishlist-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: min(380px, 90vw);
            height: 100%;
            background: #ffffff;
            box-shadow: -4px 0 25px rgba(0,0,0,0.15);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .jonilux-wishlist-drawer.is-active {
            transform: translateX(0);
        }
        .jonilux-wishlist-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid #edf0e6;
            background: #f7f9f3;
        }
        .jonilux-wishlist-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #172a22;
        }
        .jonilux-wishlist-title-wrap h3 {
            margin: 0;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: 0.08em;
        }
        .jonilux-wishlist-close-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #59645b;
            border-radius: 4px;
            transition: all 0.18s ease;
        }
        .jonilux-wishlist-close-btn:hover {
            color: #111614;
            background: #e9ece0;
        }
        .jonilux-wishlist-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .jonilux-wishlist-empty {
            text-align: center;
            padding: 40px 20px;
            color: #59645b;
        }
        .jonilux-wishlist-empty p {
            font-size: 13px;
            line-height: 1.5;
            margin: 10px 0 0 0;
        }
        .jonilux-wishlist-item {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 12px;
            border: 1px solid #edf0e6;
            border-radius: 6px;
            position: relative;
            background: #fafbfa;
        }
        .jonilux-wishlist-item img {
            width: 60px;
            height: 72px;
            object-fit: cover;
            border-radius: 4px;
        }
        .jonilux-wishlist-item-info {
            flex: 1;
        }
        .jonilux-wishlist-item-info h4 {
            margin: 0 0 4px 0;
            font-size: 12px !important;
            font-weight: 700 !important;
        }
        .jonilux-wishlist-item-info h4 a {
            color: #172a22;
            text-decoration: none;
        }
        .jonilux-wishlist-item-price {
            font-size: 12px;
            font-weight: 700;
            color: #172a22;
            display: block;
            margin-bottom: 6px;
        }
        .jonilux-wishlist-item-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .jonilux-wishlist-btn-buy {
            background: #172a22;
            color: #ffffff !important;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
        }
        .jonilux-wishlist-btn-remove {
            background: transparent;
            border: none;
            color: #8b2b2b;
            font-size: 10px;
            cursor: pointer;
            padding: 4px;
            text-decoration: underline;
        }
        .jonilux-wishlist-footer {
            padding: 18px 24px;
            border-top: 1px solid #edf0e6;
            background: #f7f9f3;
        }
        .jonilux-wishlist-shop-btn {
            display: block;
            text-align: center;
            background: #172a22;
            color: #ffffff !important;
            padding: 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.18s ease;
        }
        .jonilux-wishlist-shop-btn:hover { background: #2f4535; }

        /* Badge de coleccion urbana en tarjetas */
        .jonilux-streetwear-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #111614;
            color: #ffffff;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 4px 8px;
            border-radius: 3px;
            z-index: 4;
        }

        /* Boton de corazon en tarjetas del catalogo */
        .jonilux-card-wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #172a22;
            z-index: 5;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .jonilux-card-wishlist-btn:hover {
            transform: scale(1.1);
            background: #ffffff;
        }
        .jonilux-card-wishlist-btn.is-active svg {
            fill: #e74c3c;
            stroke: #e74c3c;
        }
    </style>
    <?php
}

// Scripts JavaScript vanilla para Lista de Deseos (sin jQuery ni dependencias pesadas)
function jonilux_streetwear_scripts() {
    ?>
    <script>
    (function() {
        const STORAGE_KEY = 'jonilux_wishlist_items';

        function getWishlist() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            } catch(e) {
                return [];
            }
        }

        function saveWishlist(items) {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
            } catch(e) {}
            updateCounter();
            renderDrawer();
            syncButtons();
        }

        function updateCounter() {
            const count = getWishlist().length;
            const badge = document.getElementById('jonilux-wishlist-counter');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        function renderDrawer() {
            const container = document.getElementById('jonilux-wishlist-items');
            if (!container) return;
            const items = getWishlist();

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="jonilux-wishlist-empty">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#8c968e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <p>Aún no tienes prendas en tus favoritos.<br>Haz clic en el corazón de cualquier prenda para guardarla.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            items.forEach(item => {
                html += `
                    <div class="jonilux-wishlist-item" data-id="${item.id}">
                        <img src="${item.image || ''}" alt="${item.title}">
                        <div class="jonilux-wishlist-item-info">
                            <h4><a href="${item.url}">${item.title}</a></h4>
                            <span class="jonilux-wishlist-item-price">${item.price}</span>
                            <div class="jonilux-wishlist-item-actions">
                                <a href="${item.url}" class="jonilux-wishlist-btn-buy">Ver prenda</a>
                                <button type="button" class="jonilux-wishlist-btn-remove" data-remove="${item.id}">Quitar</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;

            container.querySelectorAll('[data-remove]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const removeId = this.getAttribute('data-remove');
                    const updated = getWishlist().filter(i => String(i.id) !== String(removeId));
                    saveWishlist(updated);
                });
            });
        }

        function toggleItem(item) {
            let list = getWishlist();
            const existsIndex = list.findIndex(i => String(i.id) === String(item.id));
            if (existsIndex > -1) {
                list.splice(existsIndex, 1);
            } else {
                list.push(item);
                openDrawer();
            }
            saveWishlist(list);
        }

        function syncButtons() {
            const list = getWishlist();
            const ids = list.map(i => String(i.id));

            document.querySelectorAll('.jonilux-btn-wishlist-single, .jonilux-card-wishlist-btn').forEach(btn => {
                const id = btn.getAttribute('data-id');
                if (ids.includes(String(id))) {
                    btn.classList.add('is-active');
                    const text = btn.querySelector('span');
                    if (text && btn.classList.contains('jonilux-btn-wishlist-single')) {
                        text.textContent = 'En Favoritos';
                    }
                } else {
                    btn.classList.remove('is-active');
                    const text = btn.querySelector('span');
                    if (text && btn.classList.contains('jonilux-btn-wishlist-single')) {
                        text.textContent = 'Guardar en Favoritos';
                    }
                }
            });
        }

        function openDrawer() {
            const drawer = document.getElementById('jonilux-wishlist-drawer');
            const overlay = document.getElementById('jonilux-wishlist-overlay');
            if (drawer && overlay) {
                drawer.classList.add('is-active');
                overlay.classList.add('is-active');
                renderDrawer();
            }
        }

        function closeDrawer() {
            const drawer = document.getElementById('jonilux-wishlist-drawer');
            const overlay = document.getElementById('jonilux-wishlist-overlay');
            if (drawer && overlay) {
                drawer.classList.remove('is-active');
                overlay.classList.remove('is-active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateCounter();
            syncButtons();

            // Abrir y cerrar drawer
            const openBtn = document.getElementById('jonilux-wishlist-open-btn');
            if (openBtn) openBtn.addEventListener('click', openDrawer);

            const closeBtn = document.getElementById('jonilux-wishlist-close');
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);

            const overlay = document.getElementById('jonilux-wishlist-overlay');
            if (overlay) overlay.addEventListener('click', closeDrawer);

            // Boton individual en ficha de producto
            document.querySelectorAll('.jonilux-btn-wishlist-single').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleItem({
                        id: this.getAttribute('data-id'),
                        title: this.getAttribute('data-title'),
                        price: this.getAttribute('data-price'),
                        image: this.getAttribute('data-image'),
                        url: this.getAttribute('data-url')
                    });
                });
            });

            // Insertar corazones en tarjetas de catalogo si existen
            document.querySelectorAll('.lunaris-product-card, .type-product').forEach(card => {
                const link = card.querySelector('a[href*="/product/"], a.woocommerce-LoopProduct-link');
                const titleEl = card.querySelector('.lunaris-card-title, .woocommerce-loop-product__title');
                const imgEl = card.querySelector('img');
                const priceEl = card.querySelector('.price, .lunaris-product-price');
                if (!link || !titleEl || card.querySelector('.jonilux-card-wishlist-btn')) return;

                const url = link.href;
                const match = card.className.match(/post-(\d+)/);
                const id = match ? match[1] : (card.getAttribute('data-product-id') || 'p-' + Math.random().toString(36).substr(2, 6));

                const heartBtn = document.createElement('button');
                heartBtn.type = 'button';
                heartBtn.className = 'jonilux-card-wishlist-btn';
                heartBtn.setAttribute('data-id', id);
                heartBtn.setAttribute('aria-label', 'Guardar en Favoritos');
                heartBtn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';

                heartBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleItem({
                        id: id,
                        title: titleEl.innerText.trim(),
                        price: priceEl ? priceEl.innerText.trim() : '',
                        image: imgEl ? imgEl.src : '',
                        url: url
                    });
                });

                const thumb = card.querySelector('.lunaris-product-thumb, .woocommerce-loop-product__link');
                if (thumb) {
                    thumb.style.position = 'relative';
                    thumb.appendChild(heartBtn);

                    // Insignia Streetwear urbana
                    if (!card.querySelector('.jonilux-streetwear-badge')) {
                        const txt = (titleEl ? titleEl.innerText : '').toLowerCase();
                        let badgeLabel = 'NUEVO DROP';
                        if (txt.includes('polo') || txt.includes('oversize')) {
                            badgeLabel = 'OVERSIZE FIT';
                        } else if (txt.includes('gorra') || txt.includes('lazo') || txt.includes('accesorio')) {
                            badgeLabel = 'EDICIÓN LIMITADA';
                        } else if (txt.includes('hoodie') || txt.includes('poleron') || txt.includes('casaca')) {
                            badgeLabel = 'STREETWEAR DROP';
                        }
                        const badge = document.createElement('span');
                        badge.className = 'jonilux-streetwear-badge';
                        badge.textContent = badgeLabel;
                        thumb.appendChild(badge);
                    }
                }
            });

            syncButtons();
        });
    })();
    </script>
    <?php
}
