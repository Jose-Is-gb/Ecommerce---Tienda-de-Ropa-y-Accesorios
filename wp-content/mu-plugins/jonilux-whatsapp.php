<?php
/**
 * Plugin Name: JONILUX – Botón de WhatsApp
 * Description: Botón flotante de WhatsApp en todas las páginas de la tienda.
 */

defined('ABSPATH') || exit;

add_action('wp_footer', function () {
	$numero  = '51900000000'; // Número de ejemplo (proyecto académico)
	$mensaje = rawurlencode('Hola JONILUX, quiero información sobre un producto.');
	?>
	<a href="https://wa.me/<?php echo esc_attr($numero); ?>?text=<?php echo $mensaje; ?>"
	   class="jonilux-whatsapp" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
		<svg viewBox="0 0 32 32" width="30" height="30" aria-hidden="true"><path fill="#fff" d="M16 3C9 3 3.3 8.6 3.3 15.6c0 2.2.6 4.4 1.7 6.3L3.2 29l7.3-1.9c1.8 1 3.9 1.5 6 1.5 7 0 12.7-5.7 12.7-12.7S23 3 16 3zm0 23.2c-1.9 0-3.8-.5-5.4-1.5l-.4-.2-4.3 1.1 1.2-4.2-.3-.4c-1.1-1.7-1.6-3.6-1.6-5.5C5.2 9.8 10 5 16 5s10.8 4.8 10.8 10.7S21.9 26.2 16 26.2zm5.9-8c-.3-.2-1.9-.9-2.2-1s-.5-.2-.7.2-.8 1-1 1.2-.4.2-.7.1c-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.2s0-.5.1-.6l.5-.6c.2-.2.2-.3.3-.5s0-.4 0-.5l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.5 1.1 2.9 1.2 3.1 2.1 3.2 5.1 4.5c.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.9-.8 2.1-1.5s.3-1.4.2-1.5c0-.2-.3-.3-.6-.4z"/></svg>
	</a>
	<style>
		.jonilux-whatsapp{position:fixed;left:20px;bottom:20px;z-index:9999;width:56px;height:56px;border-radius:50%;background:#25d366;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.25);transition:transform .2s}
		.jonilux-whatsapp:hover{transform:scale(1.08)}
	</style>
	<?php
});
