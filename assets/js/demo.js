(function () {
  'use strict';
  var page = (location.pathname.split('/').pop() || 'index.html').toLowerCase();

  /* Aviso breve para enlaces que aún no existen en la demo */
  var toast;
  function showToast(msg) {
    if (!toast) { toast = document.createElement('div'); toast.id = 'demo-toast'; toast.setAttribute('role', 'status'); document.body.appendChild(toast); }
    toast.textContent = msg; toast.classList.add('show');
    clearTimeout(showToast.t); showToast.t = setTimeout(function () { toast.classList.remove('show'); }, 2800);
  }
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('[data-demo]');
    if (a) { e.preventDefault(); showToast(a.getAttribute('data-demo')); }
  });
  window.demoSubmit = function (e, msg) { e.preventDefault(); showToast(msg); e.target.reset(); return false; };

  /* Enlace activo en el menú superior */
  var activeFor = { 'catalogo.html': 'caballos-en-venta', 'ficha-cornet-d-or.html': 'caballos-en-venta', 'entrenamiento.html': 'entrenamiento', 'pupilaje.html': 'pupilaje' };
  var nav = document.querySelector('header nav');
  var cur = activeFor[page];
  if (nav) {
    var cls = (nav.getAttribute('data-active-classes') || 'text-primary font-semibold').split(' ');
    nav.querySelectorAll('a').forEach(function (l) {
      var on = l.getAttribute('data-path') === cur;
      l.removeAttribute('aria-current');
      cls.forEach(function (c) { l.classList.remove(c); });
      l.classList.add('text-on-surface-variant');
      if (on) { l.classList.remove('text-on-surface-variant'); cls.forEach(function (c) { l.classList.add(c); }); l.setAttribute('aria-current', 'page'); }
    });
  }

  /* Menú móvil / tablet (el diseño oculta la navegación por debajo de 1280px) */
  var btn = document.getElementById('menu-toggle');
  if (btn && nav) {
    var menu = document.createElement('div'); menu.id = 'mobile-menu';
    nav.querySelectorAll('a').forEach(function (l) {
      var a = document.createElement('a'); a.href = l.getAttribute('href'); a.textContent = l.textContent;
      if (l.getAttribute('aria-current')) a.setAttribute('aria-current', 'page');
      menu.appendChild(a);
    });
    var home = document.querySelector('header a[data-path="inicio"]'), ct = document.querySelector('header a[data-path="contacto"]');
    [['Inicio', home && home.getAttribute('href'), ''], ['Contacto', ct && ct.getAttribute('href'), 'cta']].forEach(function (x, i) {
      var a = document.createElement('a'); a.href = x[1] || '#'; a.textContent = x[0]; if (x[2]) a.className = x[2];
      i === 0 ? menu.insertBefore(a, menu.firstChild) : menu.appendChild(a);
    });
    document.body.appendChild(menu);
    var set = function (open) { menu.classList.toggle('open', open); document.body.classList.toggle('menu-open', open); btn.setAttribute('aria-expanded', open); btn.querySelector('span').textContent = open ? 'close' : 'menu'; };
    btn.addEventListener('click', function () {
      if (window.innerWidth >= 1280) { if (ct) location.href = ct.getAttribute('href'); return; }
      set(!menu.classList.contains('open'));
    });
    menu.addEventListener('click', function (e) { if (e.target.tagName === 'A') set(false); });
    window.addEventListener('resize', function () { if (window.innerWidth >= 1280) set(false); });
  }

  /* Catálogo: filtros por categoría */
  if (page === 'catalogo.html') {
    var cats = { 'CORNET D\'OR': 'clasica', 'DIAMANT BLUE': 'gp', 'KALLISTO CR': 'clasica amateur', 'VALENTINA': 'gp', 'BALOU STARLET': 'jovenes', 'CASANOVA CR': 'gp' };
    var keys = ['todos', 'gp', 'clasica', 'jovenes', 'amateur'];
    var cards = Array.prototype.slice.call(document.querySelectorAll('article')).filter(function (a) { return a.querySelector('h3'); });
    var pills = document.querySelectorAll('.filter-pill');
    var counter = Array.prototype.filter.call(document.querySelectorAll('span'), function (s) { return /^Mostrando/.test(s.textContent.trim()); })[0];
    var clear = Array.prototype.filter.call(document.querySelectorAll('button'), function (b) { return b.textContent.trim() === 'Limpiar'; })[0];
    var apply = function (i) {
      var k = keys[i], n = 0;
      cards.forEach(function (c) {
        var t = (cats[c.querySelector('h3').textContent.trim().toUpperCase()] || '');
        var show = k === 'todos' || t.split(' ').indexOf(k) > -1;
        c.classList.toggle('horse-hidden', !show); if (show) n++;
      });
      if (counter) counter.textContent = 'Mostrando ' + n + ' de 9';
    };
    pills.forEach(function (p, i) { p.addEventListener('click', function () { apply(i); }); });
    if (clear) clear.addEventListener('click', function () { pills[0] && pills[0].click(); });
    var q = new URLSearchParams(location.search).get('filtro'), qi = keys.indexOf(q);
    if (qi > 0 && pills[qi]) pills[qi].click();
  }
})();
