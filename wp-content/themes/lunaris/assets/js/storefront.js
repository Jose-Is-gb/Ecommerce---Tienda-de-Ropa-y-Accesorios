(function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function initCarousel(root) {
        var track = root.querySelector('.jx-slides');
        var slides = Array.prototype.slice.call(root.querySelectorAll('.jx-slide'));
        var dots = Array.prototype.slice.call(root.querySelectorAll('.jx-dot'));
        var prev = root.querySelector('.jx-car-prev');
        var next = root.querySelector('.jx-car-next');
        if (!track || slides.length < 2) { return; }

        var index = 0;
        var timer = null;
        var paused = false;

        function go(i) {
            index = (i + slides.length) % slides.length;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
            slides.forEach(function (slide, n) {
                var active = n === index;
                slide.setAttribute('aria-hidden', active ? 'false' : 'true');
                if (active) { slide.removeAttribute('inert'); } else { slide.setAttribute('inert', ''); }
            });
            dots.forEach(function (dot, n) {
                dot.classList.toggle('is-active', n === index);
                dot.setAttribute('aria-current', n === index ? 'true' : 'false');
            });
        }
        function stop() {
            if (timer) { clearInterval(timer); timer = null; }
        }
        function play() {
            stop();
            if (reduceMotion || paused || document.hidden) { return; }
            timer = setInterval(function () { go(index + 1); }, 6500);
        }

        if (prev) { prev.addEventListener('click', function () { go(index - 1); play(); }); }
        if (next) { next.addEventListener('click', function () { go(index + 1); play(); }); }
        dots.forEach(function (dot, n) {
            dot.addEventListener('click', function () { go(n); play(); });
        });

        root.addEventListener('mouseenter', function () { paused = true; stop(); });
        root.addEventListener('mouseleave', function () { paused = false; play(); });
        root.addEventListener('focusin', function () { paused = true; stop(); });
        root.addEventListener('focusout', function () { paused = false; play(); });
        document.addEventListener('visibilitychange', play);

        var startX = null;
        root.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        root.addEventListener('touchend', function (e) {
            if (startX === null) { return; }
            var dx = e.changedTouches[0].clientX - startX;
            startX = null;
            if (Math.abs(dx) > 45) { go(index + (dx < 0 ? 1 : -1)); play(); }
        }, { passive: true });

        go(0);
        play();
    }

    function initRow(row) {
        var track = row.querySelector('.jx-row-track');
        var buttons = row.querySelectorAll('.jx-row-btn');
        if (!track || buttons.length < 2) { return; }

        function update() {
            var max = track.scrollWidth - track.clientWidth - 2;
            row.classList.toggle('is-static', max <= 0);
            buttons[0].disabled = track.scrollLeft <= 2;
            buttons[1].disabled = track.scrollLeft >= max;
        }
        Array.prototype.forEach.call(buttons, function (button) {
            button.addEventListener('click', function () {
                var dir = parseInt(button.getAttribute('data-dir'), 10) || 1;
                track.scrollBy({ left: dir * track.clientWidth * 0.9, behavior: reduceMotion ? 'auto' : 'smooth' });
            });
        });
        track.addEventListener('scroll', function () { window.requestAnimationFrame(update); }, { passive: true });
        window.addEventListener('resize', update);
        update();
    }

    function initMega() {
        var mega = document.getElementById('jx-mega');
        var button = document.getElementById('jx-mega-btn');
        if (!mega || !button) { return; }

        function set(open) {
            mega.classList.toggle('is-open', open);
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            set(!mega.classList.contains('is-open'));
        });
        document.addEventListener('click', function (e) {
            if (!mega.contains(e.target)) { set(false); }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && mega.classList.contains('is-open')) {
                set(false);
                button.focus();
            }
        });
        mega.addEventListener('focusout', function (e) {
            if (e.relatedTarget && !mega.contains(e.relatedTarget)) { set(false); }
        });
    }

    var carousel = document.getElementById('jx-carousel');
    if (carousel) { initCarousel(carousel); }
    Array.prototype.forEach.call(document.querySelectorAll('.jx-row'), initRow);
    initMega();
})();
