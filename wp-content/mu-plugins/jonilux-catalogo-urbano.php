<?php
/**
 * Plugin Name: JONILUX – Catalogo Urbano Ampliado (Zapatillas, Hoodies y Casacas)
 * Description: Registra de forma automatica e idempotente el lote de 16 prendas y zapatillas urbanas en WooCommerce (compatible con XAMPP local y cPanel) y establece el orden del catalogo por novedades para destacar la moda urbana.
 * Version: 1.0.0
 * Author: JONILUX Team
 */

defined('ABSPATH') || exit;

// 1. Mostrar primero las prendas mas recientes en la tienda (/shop/) en lugar de orden alfabetico
add_filter('woocommerce_default_catalog_orderby', function () {
    return 'date';
}, 99);

// 2. Sembrado idempotente de los 16 nuevos productos urbanos (8 zapatillas y 8 hoodies/casacas)
add_action('init', 'jonilux_seed_catalogo_urbano_v1', 25);

function jonilux_seed_catalogo_urbano_v1() {
    if (!function_exists('wc_get_product_id_by_sku') || !class_exists('WC_Product_Simple')) {
        return;
    }
    if (get_option('jonilux_catalogo_urbano_v1_ready') === 'yes') {
        return;
    }

    $items = [
        [
            'sku'   => 'JX-URB-001',
            'name'  => 'Casaca Acolchada Sherpa Bicolor Negro y Crema',
            'price' => '169.00',
            'cats'  => ['hombre', 'ropa', 'chaquetas-y-abrigos'],
            'img'   => '1062e9a5ba68c963e903aec3d186b2bc.jpg',
            'short' => 'Casaca urbana acolchada con mangas y capucha de borrego sherpa color crema. Corte boxy térmico.',
            'desc'  => "La Casaca Acolchada Sherpa Bicolor combina cuerpo acolchado negro mate con mangas y capucha en textura sherpa crema de alto abrigo.\n\n• Material exterior: Nylon impermeable acolchado y mangas sherpa de alta densidad\n• Capucha amplia con cordones ajustables\n• Bolsillos frontales con solapa y parche urbano en manga\n• Corte: Boxy / Oversize urbano",
        ],
        [
            'sku'   => 'JX-URB-002',
            'name'  => 'Zapatillas Chunky Plataforma Triple White',
            'price' => '189.00',
            'cats'  => ['mujer', 'calzado', 'zapatillas', 'zapatos-y-sandalias'],
            'img'   => '1c947836fc1225807ced24e3e9b9c4dd.jpg',
            'short' => 'Zapatillas urbanas blancas con suela chunky de plataforma ergonómica y acabado minimalista.',
            'desc'  => "Zapatillas Chunky Plataforma Triple White diseñadas para destacar cualquier outfit urbano con altura y comodidad durante todo el día.\n\n• Capellada: Cuero sintético premium fácil de limpiar\n• Suela: Plataforma de caucho ultraligero antideslizante (4.5 cm)\n• Plantilla interior acolchada de alto confort\n• Color: Blanco total (Triple White)",
        ],
        [
            'sku'   => 'JX-URB-003',
            'name'  => 'Casaca Bomber Negra Edición Espacial NASA',
            'price' => '179.00',
            'cats'  => ['hombre', 'ropa', 'chaquetas-y-abrigos'],
            'img'   => '4396fea933f88f9006f9a6cf439e017f.jpg',
            'short' => 'Casaca bomber flight negra con parches bordados edición espacial y forro interior acolchado.',
            'desc'  => "Casaca Bomber Negra Edición Espacial con bordados de alta definición en el pecho y parches tejidos en las mangas.\n\n• Material: Poliéster técnico repelente al viento y agua\n• Forro interno acolchado tipo diamante\n• Bolsillo utilitario con cierre en la manga izquierda\n• Puños y cuello en rib elástico resistente",
        ],
        [
            'sku'   => 'JX-URB-004',
            'name'  => 'Zapatillas Skate Gamuza Negra Franja Blanca',
            'price' => '195.00',
            'cats'  => ['hombre', 'calzado', 'zapatillas'],
            'img'   => '45116e41c78369f788022e19ea7f2ba8.jpg',
            'short' => 'Zapatillas estilo skate chunky en gamuza negra con lengüeta acolchada, pasadores anchos y franja lateral blanca.',
            'desc'  => "Inspiradas en la cultura skate de los 90, estas zapatillas cuentan con lengüeta extra acolchada, pasadores gruesos y franja lateral en relieve 3D.\n\n• Exterior: Gamuza resistente y lona reforzada\n• Costuras blancas en contraste de alta durabilidad\n• Suela vulcanizada con tracción tipo waffle\n• Ideal para pantalones cargo, baggy o jeans anchos",
        ],
        [
            'sku'   => 'JX-URB-005',
            'name'  => 'Casaca Puffer Negra Oversize con Capucha Gris',
            'price' => '159.00',
            'cats'  => ['hombre', 'mujer', 'ropa', 'chaquetas-y-abrigos'],
            'img'   => '4ebefffb50c0e0539b77f4251faf3787.jpg',
            'short' => 'Casaca acolchada negra de corte holgado con capucha de algodón gris melange integrada.',
            'desc'  => "Diseño 2 en 1 que fusiona la estética de una casaca puffer acolchada con la comodidad de una capucha de polerón gris claro.\n\n• Corte: Oversize relajado unisex\n• Cierre metálico frontal completo\n• Bolsillos laterales profundos y puños elásticos\n• Térmica, ligera y fácil de combinar",
        ],
        [
            'sku'   => 'JX-URB-006',
            'name'  => 'Zapatillas Urbanas Gamuza Azul Marino y Blanco',
            'price' => '199.00',
            'cats'  => ['hombre', 'calzado', 'zapatillas'],
            'img'   => '58eff0f3379d32bf2e7df554d37b35af.jpg',
            'short' => 'Zapatillas streetwear en gamuza azul marino con tres franjas blancas, pasadores anchos y suela color caramelo.',
            'desc'  => "Silueta retro urbana en gamuza azul marino profundo con franjas laterales blancas y suela de goma color caramelo.\n\n• Capellada de gamuza suave de primera calidad\n• Lengüeta acolchada y pasadores anchos estilo skate\n• Entresuela crema con base de caucho natural adherente\n• El complemento ideal para outfits streetwear diarios",
        ],
        [
            'sku'   => 'JX-URB-007',
            'name'  => 'Zapatillas Urbanas Clásicas Blancas Low',
            'price' => '179.00',
            'cats'  => ['hombre', 'mujer', 'calzado', 'zapatillas'],
            'img'   => '61975701871714829bf89f91e68d3428.jpg',
            'short' => 'El clásico infaltable del streetwear: zapatillas blancas de corte bajo con puntera perforada y suela cápsula.',
            'desc'  => "Un básico indispensable en cualquier armario urbano. Diseño limpio en blanco total con placa metálica en los pasadores y suela con cámara de amortiguación.\n\n• Puntera con microperforaciones de ventilación\n• Costuras reforzadas y cuello acolchado\n• Suela de goma de alta resistencia al desgaste\n• Combina con joggers, jeans o shorts",
        ],
        [
            'sku'   => 'JX-URB-008',
            'name'  => 'Polerón Hoodie Negro con Cierre Completo Oversize',
            'price' => '119.00',
            'cats'  => ['hombre', 'mujer', 'ropa', 'hoodies-y-sudaderas'],
            'img'   => 'a3b3fc758765c2fe26cf9657851d9f4e.jpg',
            'short' => 'Polerón hoodie negro de algodón franela pesado con cierre metálico frontal y caída de hombros oversize.',
            'desc'  => "Polerón zip-up básico de corte oversize confeccionado en franela de algodón de alto gramaje, ideal para usar abierto sobre polos gráficos o cerrado.\n\n• Material: Algodón franela reactiva (no destiñe ni encoge)\n• Cierre metálico plateado de deslizamiento suave\n• Bolsillos frontales divididos tipo canguro\n• Hombros caídos y puños acanalados",
        ],
        [
            'sku'   => 'JX-URB-009',
            'name'  => 'Sweater Tejido Negro Cuello Alto Medio Cierre',
            'price' => '129.00',
            'cats'  => ['hombre', 'ropa', 'hoodies-y-sudaderas'],
            'img'   => 'a53f79e030a49b51c0b61d63ab08f790.jpg',
            'short' => 'Chompa tejida negra en punto acanalado grueso con cuello alto convertible y medio cierre metálico (Half-Zip).',
            'desc'  => "El Sweater Half-Zip aporta una textura limpia y moderna al estilo urbano contemporáneo.\n\n• Tejido de punto inglés acanalado suave al tacto\n• Cuello alto con cierre metálico de cuarto (Quarter-Zip)\n• Calce relajado con caída estructurada\n• Perfecto para salidas nocturnas o días fríos en la ciudad",
        ],
        [
            'sku'   => 'JX-URB-010',
            'name'  => 'Zapatillas Urbanas Gamuza Rosa Pastel',
            'price' => '185.00',
            'cats'  => ['mujer', 'calzado', 'zapatillas', 'zapatos-y-sandalias'],
            'img'   => 'b2033b9cbe3781cedf32777d459a615a.jpg',
            'short' => 'Zapatillas de perfil bajo estilo motorsport en gamuza rosa pastel con franja lateral blanca.',
            'desc'  => "Inspiradas en las siluetas retro de pista, estas zapatillas de gamuza rosa pastel son ligeras, estilizadas y súper cómodas.\n\n• Exterior en gamuza suave color rosa pastel con detalles en blanco\n• Suela de perfil bajo de caucho negro flexible\n• Bordado frontal en la puntera\n• Ideales para estilos coquette urbano, jeans wide-leg o faldas",
        ],
        [
            'sku'   => 'JX-URB-011',
            'name'  => 'Polerón Hoodie Oversize Marrón Chocolate',
            'price' => '115.00',
            'cats'  => ['hombre', 'mujer', 'ropa', 'hoodies-y-sudaderas'],
            'img'   => 'd76981f5273367c93c2074ac03a12571.jpg',
            'short' => 'Hoodie cerrado color marrón chocolate en tendencia earth-tone, con bolsillo canguro y capucha doble.',
            'desc'  => "Los tonos tierra dominan el streetwear actual. Este hoodie marrón chocolate ofrece un corte holgado auténtico y suavidad interior afelpada.\n\n• Material: Algodón franela 320 g/m² con interior perchado\n• Capucha de doble tela con excelente estructura\n• Bolsillo canguro frontal y cordones con puntera metálica\n• Corte: Oversize unisex",
        ],
        [
            'sku'   => 'JX-URB-012',
            'name'  => 'Zapatillas Running Retro Blanco y Plata',
            'price' => '199.00',
            'cats'  => ['hombre', 'mujer', 'calzado', 'zapatillas'],
            'img'   => 'd8013b5be86dd72e652f3cfd327e7c7c.jpg',
            'short' => 'Zapatillas urbanas estética Y2K / Retro Runner en malla blanca transpirable con detalles metalizados plateados y azul marino.',
            'desc'  => "La tendencia Y2K Retro Runner llevada a la calle. Su combinación de malla respirable y paneles plateados brinda ventilación y amortiguación superior.\n\n• Capellada de malla deportiva abierta y refuerzos sintéticos metalizados\n• Entresuela con absorción de impacto para caminatas largas\n• Detalles en azul marino y suela de tracción mixta",
        ],
        [
            'sku'   => 'JX-URB-013',
            'name'  => 'Casaca Bomber Azul Marino Edición NASA',
            'price' => '179.00',
            'cats'  => ['hombre', 'ropa', 'chaquetas-y-abrigos'],
            'img'   => 'da1d9149ae9f000bdd5fed3ab85f500c.jpg',
            'short' => 'Casaca bomber azul marino satinado con panel gris en contraste, bolsillos frontales y cinta roja en manga.',
            'desc'  => "Versión azul marino de nuestra bomber espacial, con cuello tipo camisa, paneles en contraste gris plomo y cinta roja de vuelo en el brazo.\n\n• Tejido exterior de nylon satinado impermeable\n• Dos bolsillos frontales con tapa y botón a presión\n• Cierre metálico frontal de alta resistencia\n• Estampados técnicos y parche bordado en el pecho",
        ],
        [
            'sku'   => 'JX-URB-014',
            'name'  => 'Polerón Hoodie Gris Melange con Cierre',
            'price' => '119.00',
            'cats'  => ['hombre', 'mujer', 'ropa', 'hoodies-y-sudaderas'],
            'img'   => 'efa1eaf081e3c4d18765f5b173417733.jpg',
            'short' => 'Polerón gris claro melange con cierre frontal completo, capucha estructurada sin cordones y corte boxy.',
            'desc'  => "Un esencial minimalista de corte boxy limpio. Su diseño sin cordones y cierre metálico le da un acabado moderno y ordenado.\n\n• Algodón franela gris melange de tacto suave\n• Costuras reforzadas en hombros y bolsillos\n• Puños y pretina anchos en rib de alta recuperación\n• Calce Boxy Fit moderno",
        ],
        [
            'sku'   => 'JX-URB-015',
            'name'  => 'Zapatillas Urbanas Low Blanco y Negro Panda',
            'price' => '189.00',
            'cats'  => ['hombre', 'mujer', 'calzado', 'zapatillas'],
            'img'   => 'f41370be3ba9f38f1647622c151b57d0.jpg',
            'short' => 'Zapatillas urbanas de cuero sintético en combinación blanco y negro (colorway Panda) de alto contraste.',
            'desc'  => "El contraste blanco y negro más buscado del streetwear. Fáciles de combinar con cualquier prenda del catálogo.\n\n• Paneles en negro mate sobre base blanca con puntera perforada\n• Suela bicolor con costura perimetral completa\n• Cuello acolchado de corte bajo para máxima movilidad",
        ],
        [
            'sku'   => 'JX-URB-016',
            'name'  => 'Zapatillas Retro Court Blanco Franjas Negras',
            'price' => '189.00',
            'cats'  => ['hombre', 'mujer', 'calzado', 'zapatillas'],
            'img'   => 'fde60eb3ebb8a8f2050cb045b774b075.jpg',
            'short' => 'Zapatillas urbanas estilo retro court en blanco con tres franjas negras en gamuza y suela robusta.',
            'desc'  => "Inspiradas en el calzado clásico de cancha, estas zapatillas combinan estructura firme, detalles de gamuza negra en las tres franjas y cuello acolchado negro.\n\n• Capellada blanca con perforaciones de ventilación\n• Franjas laterales texturizadas en contraste negro\n• Suela cápsula con base negra de gran agarre",
        ],
    ];

    $upload_dir = wp_upload_dir();
    $base_dir = trailingslashit($upload_dir['basedir']) . '2026/10/nuevos/';
    $base_url = trailingslashit($upload_dir['baseurl']) . '2026/10/nuevos/';

    foreach ($items as $item) {
        $existing_id = wc_get_product_id_by_sku($item['sku']);
        if ($existing_id) {
            continue;
        }

        $file_path = $base_dir . $item['img'];
        if (!file_exists($file_path)) {
            continue;
        }

        $attach_id = jonilux_get_or_create_attachment($file_path, $base_url . $item['img'], $item['name']);

        $product = new WC_Product_Simple();
        $product->set_name($item['name']);
        $product->set_sku($item['sku']);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_regular_price($item['price']);
        $product->set_price($item['price']);
        $product->set_short_description($item['short']);
        $product->set_description($item['desc']);
        $product->set_stock_status('instock');
        $product->set_featured(true);

        if ($attach_id) {
            $product->set_image_id($attach_id);
        }

        $cat_ids = [];
        foreach ($item['cats'] as $slug) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term && !is_wp_error($term)) {
                $cat_ids[] = (int) $term->term_id;
            }
        }
        if (!empty($cat_ids)) {
            $product->set_category_ids($cat_ids);
        }

        $product->save();
    }

    delete_transient('jx_cat_img_' . md5('calzado'));
    delete_transient('jx_cat_img_' . md5('zapatillas'));
    delete_transient('jx_cat_img_' . md5('hoodies-y-sudaderas'));
    delete_transient('jx_cat_img_' . md5('chaquetas-y-abrigos'));

    update_option('jonilux_catalogo_urbano_v1_ready', 'yes');
}

function jonilux_get_or_create_attachment($file_path, $file_url, $title) {
    global $wpdb;
    $rel_path = '2026/10/nuevos/' . basename($file_path);

    $existing = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
        $rel_path
    ));
    if ($existing > 0) {
        return $existing;
    }

    $filetype = wp_check_filetype(basename($file_path), null);
    $attachment = [
        'guid'           => $file_url,
        'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/jpeg',
        'post_title'     => $title,
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attach_id = wp_insert_attachment($attachment, $rel_path);
    if (is_wp_error($attach_id) || !$attach_id) {
        return 0;
    }

    update_post_meta($attach_id, '_wp_attached_file', $rel_path);
    $size = @getimagesize($file_path);
    $w = isset($size[0]) ? (int) $size[0] : 800;
    $h = isset($size[1]) ? (int) $size[1] : 1000;
    update_post_meta($attach_id, '_wp_attachment_metadata', [
        'width'  => $w,
        'height' => $h,
        'file'   => $rel_path,
        'sizes'  => [],
    ]);

    return (int) $attach_id;
}
