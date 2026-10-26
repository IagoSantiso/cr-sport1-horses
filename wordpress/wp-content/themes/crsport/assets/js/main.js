/* CR Sport Horses · interacción del tema (sin dependencias). */
(function () {
	'use strict';

	var doc = document.documentElement;
	doc.classList.add('js');
	var T = window.CRS || {};
	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---------- Cabecera: estado al hacer scroll y menú móvil ---------- */
	var header = document.querySelector('[data-crs-header]');
	var burger = document.querySelector('[data-crs-burger]');

	function onScroll() {
		if (header) header.classList.toggle('is-scrolled', window.scrollY > 24);
	}
	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });

	function setMenu(open) {
		if (!header || !burger) return;
		header.classList.toggle('is-open', open);
		burger.setAttribute('aria-expanded', open ? 'true' : 'false');
		document.body.classList.toggle('crs-menu-open', open);
	}
	if (burger) {
		burger.addEventListener('click', function () {
			setMenu(burger.getAttribute('aria-expanded') !== 'true');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') setMenu(false);
		});
		document.querySelectorAll('[data-crs-nav] a').forEach(function (a) {
			a.addEventListener('click', function () { setMenu(false); });
		});
	}

	/* ---------- Enlaces «?interes=…#consulta» en la misma página: preseleccionar y bajar ---------- */
	document.addEventListener('click', function (e) {
		var link = e.target.closest('a[href*="#consulta"]');
		if (!link) return;
		var url = new URL(link.href, location.href);
		if (url.pathname !== location.pathname) return;
		var target = document.getElementById('consulta');
		if (!target) return;
		e.preventDefault();
		var interest = url.searchParams.get('interes');
		var select = target.querySelector('select[name="crs_interes"]');
		if (interest && select && select.querySelector('option[value="' + interest + '"]')) select.value = interest;
		target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth' });
		var first = target.querySelector('input[name="crs_nombre"]');
		if (first) setTimeout(function () { first.focus({ preventScroll: true }); }, 600);
	});

	/* ---------- Animaciones de entrada ---------- */
	var revealTargets = document.querySelectorAll(
		'.crs-section-head, .crs-split__media, .crs-split__text, .crs-card, .crs-stat, .crs-horse-card, .crs-timeline__item, .crs-consult__intro, .crs-consult__form, .crs-scores, .crs-fact'
	);
	if ('IntersectionObserver' in window && !reduceMotion) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-visible');
				io.unobserve(entry.target);
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		revealTargets.forEach(function (el) {
			// Escalonado suave entre elementos hermanos.
			var index = Array.prototype.indexOf.call(el.parentNode.children, el);
			el.style.transitionDelay = Math.min(index, 5) * 70 + 'ms';
			if (!el.classList.contains('crs-scores')) el.classList.add('crs-reveal');
			io.observe(el);
		});
	} else {
		revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* ---------- Filtros del listado de caballos ---------- */
	document.querySelectorAll('[data-crs-filters]').forEach(function (bar) {
		var grid = bar.nextElementSibling;
		bar.addEventListener('click', function (e) {
			var btn = e.target.closest('button[data-filter]');
			if (!btn || !grid) return;
			var filter = btn.getAttribute('data-filter');
			bar.querySelectorAll('button').forEach(function (b) {
				var active = b === btn;
				b.classList.toggle('is-active', active);
				b.setAttribute('aria-pressed', active ? 'true' : 'false');
			});
			grid.querySelectorAll('.crs-horse-card').forEach(function (card) {
				var cats = (card.getAttribute('data-cats') || '').split(' ');
				card.classList.toggle('is-hidden', filter !== '*' && cats.indexOf(filter) === -1);
			});
		});
	});

	/* ---------- Contenido externo que se carga al pulsar (vídeos y mapa) ---------- */
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('[data-crs-embed-load]');
		if (!btn) return;
		var box = btn.closest('[data-crs-embed]');
		if (!box) return;
		var iframe = document.createElement('iframe');
		iframe.src = box.getAttribute('data-crs-embed');
		iframe.title = box.getAttribute('data-title') || '';
		iframe.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media';
		iframe.allowFullscreen = true;
		iframe.loading = 'lazy';
		box.innerHTML = '';
		box.appendChild(iframe);
	});

	/* ---------- Galería de la ficha ---------- */
	document.querySelectorAll('[data-crs-gallery]').forEach(function (gallery) {
		var slides = Array.prototype.slice.call(gallery.querySelectorAll('.crs-gallery__slide'));
		var thumbs = Array.prototype.slice.call(gallery.querySelectorAll('[data-crs-goto]'));
		var counter = gallery.querySelector('[data-crs-current]');
		var stage = gallery.querySelector('.crs-gallery__stage');
		var current = 0;
		if (slides.length < 2) return;

		function go(i) {
			i = (i + slides.length) % slides.length;
			var old = slides[current];
			old.querySelectorAll('video').forEach(function (v) { v.pause(); });
			// Al salir de un vídeo externo ya cargado, se detiene restaurando el iframe.
			old.querySelectorAll('iframe').forEach(function (f) { f.src = f.src; });
			old.classList.remove('is-active');
			old.hidden = true;
			slides[i].hidden = false;
			slides[i].classList.add('is-active');
			thumbs.forEach(function (t, n) { t.classList.toggle('is-active', n === i); });
			if (thumbs[i]) thumbs[i].scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: reduceMotion ? 'auto' : 'smooth' });
			if (counter) counter.textContent = i + 1;
			current = i;
		}

		gallery.querySelector('[data-crs-prev]').addEventListener('click', function () { go(current - 1); });
		gallery.querySelector('[data-crs-next]').addEventListener('click', function () { go(current + 1); });
		thumbs.forEach(function (t) {
			t.addEventListener('click', function () { go(parseInt(t.getAttribute('data-crs-goto'), 10)); });
		});
		gallery.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') go(current - 1);
			if (e.key === 'ArrowRight') go(current + 1);
		});

		// Deslizar con el dedo.
		var startX = null;
		stage.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
		stage.addEventListener('touchend', function (e) {
			if (startX === null) return;
			var dx = e.changedTouches[0].clientX - startX;
			if (Math.abs(dx) > 45) go(current + (dx < 0 ? 1 : -1));
			startX = null;
		});
	});

	/* ---------- Visor de fotos a pantalla completa ---------- */
	var lbLinks = Array.prototype.slice.call(document.querySelectorAll('a[data-crs-lightbox]'));
	if (lbLinks.length && typeof HTMLDialogElement === 'function') {
		var dialog = document.createElement('dialog');
		dialog.className = 'crs-lightbox';
		dialog.innerHTML = '<img alt=""><button type="button" data-close aria-label="' + (T.close || 'Cerrar') + '">×</button>' +
			(lbLinks.length > 1 ? '<button type="button" data-prev aria-label="' + (T.prev || 'Anterior') + '">‹</button><button type="button" data-next aria-label="' + (T.next || 'Siguiente') + '">›</button>' : '');
		document.body.appendChild(dialog);
		var img = dialog.querySelector('img');
		var at = 0;
		function show(i) {
			at = (i + lbLinks.length) % lbLinks.length;
			img.src = lbLinks[at].href;
			var inner = lbLinks[at].querySelector('img');
			img.alt = inner ? inner.alt : '';
		}
		lbLinks.forEach(function (a, i) {
			a.addEventListener('click', function (e) {
				e.preventDefault();
				show(i);
				dialog.showModal();
			});
		});
		dialog.addEventListener('click', function (e) {
			if (e.target.closest('[data-close]') || e.target === dialog) dialog.close();
			if (e.target.closest('[data-prev]')) show(at - 1);
			if (e.target.closest('[data-next]')) show(at + 1);
		});
		dialog.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') show(at - 1);
			if (e.key === 'ArrowRight') show(at + 1);
		});
	}

	/* ---------- Formulario de consulta (envío sin recargar) ---------- */
	document.querySelectorAll('[data-crs-form]').forEach(function (form) {
		var status = form.querySelector('[data-crs-form-status]');
		var submit = form.querySelector('button[type=submit]');
		function say(ok, msg) {
			status.innerHTML = '';
			var p = document.createElement('p');
			p.className = ok ? 'is-ok' : 'is-error';
			p.textContent = msg;
			status.appendChild(p);
		}
		form.addEventListener('submit', function (e) {
			if (!form.checkValidity()) {
				e.preventDefault();
				form.reportValidity();
				return;
			}
			if (!window.fetch || !window.FormData) return; // sin JS moderno: envío normal
			e.preventDefault();
			var label = submit.textContent;
			form.classList.add('is-sending');
			submit.textContent = T.sending || '…';
			fetch(form.getAttribute('action'), {
				method: 'POST',
				body: new FormData(form),
				headers: { 'X-Requested-With': 'fetch' },
				credentials: 'same-origin'
			}).then(function (r) {
				return r.json();
			}).then(function (data) {
				say(data.ok, data.message);
				if (data.ok) {
					form.classList.add('is-sent');
					form.reset();
				}
			}).catch(function () {
				say(false, T.error || 'Error');
			}).finally(function () {
				form.classList.remove('is-sending');
				submit.textContent = label;
				status.scrollIntoView({ block: 'nearest', behavior: reduceMotion ? 'auto' : 'smooth' });
			});
		});
	});
})();
