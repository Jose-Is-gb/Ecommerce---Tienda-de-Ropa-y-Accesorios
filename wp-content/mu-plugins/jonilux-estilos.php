<?php
/**
 * Plugin Name: JONILUX – Estilos de la portada
 * Description: Márgenes y ancho máximo de las secciones de la portada.
 */

defined('ABSPATH') || exit;

add_action('wp_head', function () {
	if (!is_front_page()) return;
	?>
	<style>
		.jonilux-seccion{max-width:1240px;margin-left:auto;margin-right:auto;padding-left:24px!important;padding-right:24px!important;box-sizing:border-box}
		.jonilux-seccion ul.products li.product img{border-radius:6px}
		.jonilux-seccion ul.products li.product-category h2{margin-top:12px;font-size:17px;text-align:center}
		.home .wp-block-image.alignfull img{width:100%;height:auto;display:block}
	</style>
	<?php
});
