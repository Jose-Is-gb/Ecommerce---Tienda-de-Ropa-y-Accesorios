<?php
/**
 * Plugin Name: JONILUX – Herramientas de Accesibilidad Web (WCAG 2.1)
 * Description: Consideraciones de accesibilidad para personas con habilidades diferentes (visión reducida, daltonismo, dislexia).
 */

defined('ABSPATH') || exit;

add_action('wp_footer', function () {
    ?>
    <!-- HERRAMIENTA DE ACCESIBILIDAD PARA PERSONAS CON HABILIDADES DIFERENTES (SEMANA 9) -->
    <div id="jonilux-a11y-widget" class="jonilux-a11y-container">
        <!-- Panel de Opciones de Accesibilidad -->
        <div id="jonilux-a11y-panel" class="jonilux-a11y-panel" style="display:none;" role="dialog" aria-label="Opciones de accesibilidad">
            <div class="jonilux-a11y-header">
                <div>
                    <strong style="display:block;font-size:14px;color:#fff;">Accesibilidad e Inclusión</strong>
                    <small style="font-size:11px;color:#a8b4a2;">Cumplimiento WCAG 2.1 AA</small>
                </div>
                <button type="button" id="jonilux-a11y-close" class="jonilux-a11y-close-btn" aria-label="Cerrar panel">&times;</button>
            </div>
            
            <div class="jonilux-a11y-content">
                <!-- 1. Tamaño del Texto -->
                <div class="jonilux-a11y-row">
                    <span class="jonilux-a11y-label">Tamaño de Texto:</span>
                    <div class="jonilux-a11y-btn-group">
                        <button type="button" class="jonilux-a11y-btn" id="a11y-font-reset">A</button>
                        <button type="button" class="jonilux-a11y-btn" id="a11y-font-plus">A+</button>
                        <button type="button" class="jonilux-a11y-btn" id="a11y-font-max">A++</button>
                    </div>
                </div>

                <!-- 2. Alto Contraste -->
                <div class="jonilux-a11y-row">
                    <span class="jonilux-a11y-label">Modo Visual:</span>
                    <button type="button" class="jonilux-a11y-toggle-btn" id="a11y-contrast-toggle">
                        Alternar Alto Contraste
                    </button>
                </div>

                <!-- 3. Tipografía Legible / Dislexia -->
                <div class="jonilux-a11y-row">
                    <span class="jonilux-a11y-label">Lectura Fácil:</span>
                    <button type="button" class="jonilux-a11y-toggle-btn" id="a11y-dyslexic-toggle">
                        Fuente Hiperlegible
                    </button>
                </div>

                <!-- 4. Resaltar Enlaces -->
                <div class="jonilux-a11y-row">
                    <span class="jonilux-a11y-label">Navegación:</span>
                    <button type="button" class="jonilux-a11y-toggle-btn" id="a11y-links-toggle">
                        Resaltar Todos los Enlaces
                    </button>
                </div>

                <!-- 5. Reset -->
                <div style="text-align:center;margin-top:10px;">
                    <button type="button" id="a11y-reset-all" style="background:none;border:none;color:#666;font-size:12px;text-decoration:underline;cursor:pointer;">
                        Restablecer valores originales
                    </button>
                </div>
            </div>
        </div>

        <!-- Botón Disparador en esquina izquierda -->
        <button type="button" id="jonilux-a11y-trigger" class="jonilux-a11y-trigger" aria-label="Abrir panel de accesibilidad web">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <circle cx="12" cy="7.5" r="1.5"></circle>
                <path d="M12 9v6"></path>
                <path d="M8 11.5l8 0"></path>
                <path d="M9.5 19l2.5-4 2.5 4"></path>
            </svg>
            <span class="jonilux-a11y-tooltip">Accesibilidad Web (WCAG)</span>
        </button>
    </div>

    <!-- Estilos de Accesibilidad -->
    <style>
        .jonilux-a11y-container{position:fixed;left:25px;bottom:25px;z-index:99999;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif}
        .jonilux-a11y-trigger{width:52px;height:52px;border-radius:50%;background:#093028;border:2px solid #23a6d5;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 20px rgba(0,0,0,.25);position:relative;transition:transform .2s}
        .jonilux-a11y-trigger:hover{transform:scale(1.08)}
        .jonilux-a11y-tooltip{position:absolute;left:64px;background:#111a16;color:#fff;padding:6px 12px;border-radius:6px;font-size:12px;white-space:nowrap;pointer-events:none;opacity:0;transform:translateX(-8px);transition:all .2s;border:1px solid #23a6d5}
        .jonilux-a11y-trigger:hover .jonilux-a11y-tooltip{opacity:1;transform:translateX(0)}
        
        .jonilux-a11y-panel{width:300px;background:#fff;border-radius:12px;box-shadow:0 12px 35px rgba(0,0,0,.25);border:1px solid #ddd;position:absolute;left:0;bottom:65px;overflow:hidden;animation:a11ySlideUp .25s ease}
        .jonilux-a11y-header{background:#093028;color:#fff;padding:14px 16px;display:flex;justify-content:space-between;align-items:center}
        .jonilux-a11y-close-btn{background:none;border:none;color:#aaa;font-size:22px;cursor:pointer;line-height:1}
        .jonilux-a11y-close-btn:hover{color:#fff}
        .jonilux-a11y-content{padding:16px}
        .jonilux-a11y-row{margin-bottom:14px}
        .jonilux-a11y-label{display:block;font-size:12px;font-weight:600;color:#333;margin-bottom:6px}
        .jonilux-a11y-btn-group{display:flex;gap:6px}
        .jonilux-a11y-btn{flex:1;background:#f0f2eb;border:1px solid #ccc;padding:8px;border-radius:6px;cursor:pointer;font-weight:bold;transition:background .2s}
        .jonilux-a11y-btn:hover{background:#111a16;color:#fff}
        .jonilux-a11y-toggle-btn{width:100%;background:#f5f6f4;border:1px solid #ccc;padding:8px 12px;border-radius:6px;font-size:12px;font-weight:600;text-align:left;cursor:pointer;transition:all .2s}
        .jonilux-a11y-toggle-btn:hover{background:#093028;color:#fff}
        .jonilux-a11y-toggle-btn.active{background:#093028;color:#fff;border-color:#093028}

        /* Modos accesibles inyectados al body */
        body.a11y-font-lg{font-size:120%!important}
        body.a11y-font-xl{font-size:140%!important}
        body.a11y-high-contrast{background:#000!important;color:#ffff00!important}
        body.a11y-high-contrast *{background-color:#000!important;color:#ffff00!important;border-color:#ffff00!important}
        body.a11y-high-contrast a{color:#00ffff!important;text-decoration:underline!important}
        body.a11y-high-contrast img{filter:grayscale(100%) contrast(150%)!important}
        body.a11y-dyslexic{font-family:'Comic Sans MS',Arial,sans-serif!important;letter-spacing:.08em!important;line-height:1.9!important}
        body.a11y-highlight-links a{background:#ffff00!important;color:#000!important;text-decoration:underline!important;font-weight:bold!important}
        
        @keyframes a11ySlideUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    </style>

    <!-- Lógica de Accesibilidad -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var trigger = document.getElementById('jonilux-a11y-trigger');
        var panel = document.getElementById('jonilux-a11y-panel');
        var closeBtn = document.getElementById('jonilux-a11y-close');
        
        if (!trigger || !panel) return;

        trigger.addEventListener('click', function () {
            panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
        });
        if (closeBtn) {
            closeBtn.addEventListener('click', function () { panel.style.display = 'none'; });
        }

        // Font size handlers
        document.getElementById('a11y-font-reset').addEventListener('click', function () {
            document.body.classList.remove('a11y-font-lg', 'a11y-font-xl');
            localStorage.setItem('a11y_font', 'normal');
        });
        document.getElementById('a11y-font-plus').addEventListener('click', function () {
            document.body.classList.remove('a11y-font-xl');
            document.body.classList.add('a11y-font-lg');
            localStorage.setItem('a11y_font', 'lg');
        });
        document.getElementById('a11y-font-max').addEventListener('click', function () {
            document.body.classList.remove('a11y-font-lg');
            document.body.classList.add('a11y-font-xl');
            localStorage.setItem('a11y_font', 'xl');
        });

        // Contrast handler
        var contrastBtn = document.getElementById('a11y-contrast-toggle');
        contrastBtn.addEventListener('click', function () {
            document.body.classList.toggle('a11y-high-contrast');
            var isActive = document.body.classList.contains('a11y-high-contrast');
            contrastBtn.classList.toggle('active', isActive);
            localStorage.setItem('a11y_contrast', isActive ? '1' : '0');
        });

        // Dyslexic handler
        var dysBtn = document.getElementById('a11y-dyslexic-toggle');
        dysBtn.addEventListener('click', function () {
            document.body.classList.toggle('a11y-dyslexic');
            var isActive = document.body.classList.contains('a11y-dyslexic');
            dysBtn.classList.toggle('active', isActive);
            localStorage.setItem('a11y_dyslexic', isActive ? '1' : '0');
        });

        // Links handler
        var linksBtn = document.getElementById('a11y-links-toggle');
        linksBtn.addEventListener('click', function () {
            document.body.classList.toggle('a11y-highlight-links');
            var isActive = document.body.classList.contains('a11y-highlight-links');
            linksBtn.classList.toggle('active', isActive);
            localStorage.setItem('a11y_links', isActive ? '1' : '0');
        });

        // Reset all
        document.getElementById('a11y-reset-all').addEventListener('click', function () {
            document.body.classList.remove('a11y-font-lg', 'a11y-font-xl', 'a11y-high-contrast', 'a11y-dyslexic', 'a11y-highlight-links');
            contrastBtn.classList.remove('active');
            dysBtn.classList.remove('active');
            linksBtn.classList.remove('active');
            localStorage.clear();
        });

        // Restaurar preferencias guardadas
        if (localStorage.getItem('a11y_font') === 'lg') document.body.classList.add('a11y-font-lg');
        if (localStorage.getItem('a11y_font') === 'xl') document.body.classList.add('a11y-font-xl');
        if (localStorage.getItem('a11y_contrast') === '1') {
            document.body.classList.add('a11y-high-contrast');
            contrastBtn.classList.add('active');
        }
        if (localStorage.getItem('a11y_dyslexic') === '1') {
            document.body.classList.add('a11y-dyslexic');
            dysBtn.classList.add('active');
        }
        if (localStorage.getItem('a11y_links') === '1') {
            document.body.classList.add('a11y-highlight-links');
            linksBtn.classList.add('active');
        }
    });
    </script>
    <?php
});
