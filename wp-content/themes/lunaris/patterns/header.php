<?php
/**
 * Title: JONILUX header
 * Slug: lunaris/header
 * Categories: featured
 * Inserter: no
 */
if (!defined("ABSPATH")) { exit; }
$shop = function_exists("wc_get_page_permalink") ? wc_get_page_permalink("shop") : home_url("/shop/");
$myaccount = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
$mega = function_exists('jx_mega_groups') ? jx_mega_groups() : [];
$nav = [
    ['Hombre', jx_term_url('hombre')],
    ['Mujer', jx_term_url('mujer')],
    ['Accesorios', jx_term_url('accesorios')],
    ['Calzado', jx_term_url('calzado')],
    ['Novedades', add_query_arg('orderby', 'date', $shop)],
];
?>
<!-- wp:group {"className":"jx-header","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group jx-header" style="margin-top:0;margin-bottom:0">
<!-- wp:group {"className":"jx-header-main","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group jx-header-main" style="margin-top:0;margin-bottom:0">
<!-- wp:group {"className":"jx-header-inner","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group jx-header-inner" style="margin-top:0;margin-bottom:0">
<!-- wp:html -->
<a class="jx-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="JONILUX, ir al inicio">JONILUX</a>
<form class="jx-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="jx-sr" for="jx-search-input">Buscar productos</label>
    <input id="jx-search-input" type="search" name="s" placeholder="Busca polos, hoodies, gorras y más" autocomplete="off">
    <input type="hidden" name="post_type" value="product">
    <button type="submit" aria-label="Buscar">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    </button>
</form>
<!-- /wp:html -->
<!-- wp:group {"className":"jx-actions","layout":{"type":"default"}} -->
<div class="wp-block-group jx-actions">
<!-- wp:html -->
<a class="jx-action" href="<?php echo esc_url($myaccount); ?>">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
    <span class="jx-action-text">Mi cuenta</span>
</a>
<button type="button" id="jonilux-wishlist-open-btn" class="jonilux-header-icon-btn jx-action" aria-label="Ver mis favoritos" title="Mis favoritos">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
    <span class="jonilux-wishlist-badge" id="jonilux-wishlist-counter">0</span>
</button>
<!-- /wp:html -->
<!-- wp:woocommerce/mini-cart /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<nav class="jx-catbar" aria-label="Categorías principales">
    <div class="jx-catbar-inner">
        <div class="jx-mega" id="jx-mega">
            <button type="button" class="jx-mega-btn" id="jx-mega-btn" aria-expanded="false" aria-controls="jx-mega-panel">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                <span>Todas las categorías</span>
            </button>
            <div class="jx-mega-panel" id="jx-mega-panel">
                <?php foreach ($mega as $group) : ?>
                    <div class="jx-mega-col">
                        <a class="jx-mega-title" href="<?php echo esc_url($group['url']); ?>"><?php echo esc_html($group['name']); ?></a>
                        <ul>
                            <?php foreach ($group['items'] as $item) : ?>
                                <li><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['name']); ?></a></li>
                            <?php endforeach; ?>
                            <li><a class="jx-mega-all" href="<?php echo esc_url($group['url']); ?>">Ver todo</a></li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <ul class="jx-catlinks">
            <?php foreach ($nav as $link) : ?>
                <li><a href="<?php echo esc_url($link[1]); ?>"><?php echo esc_html($link[0]); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <p class="jx-catbar-note">Envío gratis en Lima y Callao desde S/ 150</p>
    </div>
</nav>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
