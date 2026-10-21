# Revisión de flujos locales — 20 de octubre de 2026

## Correcciones aplicadas

- Restauradas las tarifas ausentes usando la política de envíos publicada: Lima/Callao estándar S/10, express S/18 y provincias S/18. Envío estándar gratis desde S/150, calculado después de descuentos.
- Asignadas ubicaciones a las zonas: Lima/Callao (estados LIM, LMA y CAL) y resto de Perú. Antes ambas zonas carecían de ubicaciones y provincias recibía los métodos de Lima.
- Contraentrega limitado a los métodos de envío de Lima/Callao, según su descripción existente.
- El envío gratis oculta solo el estándar; se conserva la alternativa express.
- Mensaje del carrito precisa que el envío gratis corresponde a Lima/Callao. Cambios: se muestran 7 días, coincidiendo con la política publicada, en lugar de 30.
- Política de privacidad de WordPress vinculada a la página publicada 186; apuntaba a un borrador.
- Portada muestra valoraciones reales; los productos sin reseñas ya no reciben un 5.0 inventado.
- Eliminadas dos cachés temporales con serialización inválida; WordPress puede regenerarlas.

## Pruebas realizadas

- 12 páginas públicas responden HTTP 200: portada, tienda, tres categorías, un producto, carrito, cuenta y cuatro páginas informativas.
- Sin imágenes, scripts ni hojas CSS locales faltantes en el HTML de esas páginas.
- Todos los productos publicados tienen su archivo de imagen principal.
- Sin anclas inexistentes en los enlaces de la portada.
- Store API: agregar un producto, cambiar cantidad a 2 y quitarlo. Sesión de prueba independiente.
- Flujo con cookies: agregar producto y abrir finalizar compra; HTTP 200 en /checkout/, con bacs y cod en la respuesta. Carrito de prueba vaciado al terminar.
- Envíos y disponibilidad de contraentrega: Lima S/100 → S/10 estándar y S/18 express; Lima S/200 → gratis y S/18 express; Arequipa S/100 → S/18, sin contraentrega.
- Los 11 archivos PHP del tema LUNARIS y mu-plugins pasan la comprobación de sintaxis.
- Revisión de campos de texto serializados en todas las tablas: sin valores inválidos detectados tras las correcciones.

## Pendientes encontrados

1. **Libro de Reclamaciones incompleto.** La página 183 tiene etiquetas y un botón, pero faltan campos de entrada, formulario y un controlador de registro. No existe un flujo funcional de envío. El número REC-2026-0042 está fijo. Requiere implementar almacenamiento, numeración y confirmación; el correo deberá configurarse y probarse por separado.
2. **Datos de contacto por confirmar.** El teléfono 987654321, RUC 20612345678, cuentas bancarias y enlaces genéricos a redes sociales aparecen como datos de ejemplo. Confirmar los datos reales antes de usar pagos manuales o atención al público.
3. **Cobertura de Lima.** La configuración usa los estados LIM, LMA y CAL; no distingue distritos ni Lima región de Lima Metropolitana. Si solo se entrega en determinados distritos, hace falta definir esa cobertura.
4. **Promesas comerciales contradictorias fuera del carrito.** Revisar el contenido del chatbot y demás textos antes de publicar: el plazo de cambios debe coincidir con la política comercial elegida.

## Límites y respaldos

La revisión usó solicitudes HTTP, Store API y ejecución de WooCommerce en PHP. No se automatizó un navegador visual, no se enviaron reclamaciones/correos y no se generaron pedidos ni pagos reales. No se garantiza la ejecución de todos los comportamientos JavaScript del navegador.

Respaldos, scripts y resultados están en `.local-backups/`, excluida de Git y bloqueada por Apache. `tienda_wp-corregida.sql` dentro de esa carpeta contiene una exportación actualizada después de estas correcciones. Los cambios de configuración de la base no se comparten mediante un commit de los archivos PHP.
