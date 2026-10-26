# CR Sport Horses · Guía de staging y paso a producción

Dominio: **crsporthorses.es** (Don Dominio, hosting contratado hasta el 04/08/2027).
Web actual: WordPress 7.1.3 con tema Enfold, GTranslate, GDPR Cookie Compliance y Site Kit.

La nueva web se monta entera en un **staging** (copia privada). Cuando esté aprobada, sustituye a la actual en una sola operación.

---

## 0. Antes de tocar nada: copia de la web actual

1. En el panel de Don Dominio, haz una **copia de seguridad completa** (ficheros + base de datos) del hosting.
2. Descárgala también en local. Si algo sale mal el día de la migración, se restaura en minutos.

## 1. Crear el staging

1. **Panel de Don Dominio → Dominios → crsporthorses.es → DNS / subdominios:** crea `staging.crsporthorses.es` apuntando a una carpeta nueva del hosting (por ejemplo `/staging`).
2. **Base de datos:** crea una base de datos MySQL nueva para el staging. No reutilices la de la web actual.
3. **Instala WordPress** en esa carpeta, con el instalador de aplicaciones del panel o subiendo WordPress a mano. Usa la misma versión que la web actual o superior.
4. **SSL:** activa el certificado Let's Encrypt para el subdominio en el panel.
5. **Privacidad del staging:**
   - Ajustes → Lectura → marca **«Disuade a los motores de búsqueda…»**.
   - Protege la carpeta con contraseña desde el panel (protección de directorios / `.htpasswd`), para que solo tú y Roi la veáis.
6. **Ajustes → Enlaces permanentes:** «Nombre de la entrada». El instalador del paso 3 también lo deja así.

## 2. Instalar la nueva web en el staging

1. Genera los paquetes desde el repositorio: `./bin/build-zips.sh` crea `dist/crsport-tema.zip` y `dist/crsport-core-plugin.zip`.
2. **Plugins → Añadir nuevo:** instala y activa **Polylang**, que gestiona el español y el inglés.
3. **Plugins → Añadir → Subir plugin:** sube `crsport-core-plugin.zip` y actívalo.
4. **Apariencia → Temas → Añadir → Subir tema:** sube `crsport-tema.zip` y actívalo.
5. **CR Sport → Contenido inicial → «Crear contenido inicial».** En unos segundos crea:
   - los idiomas ES (por defecto) y EN, con el inglés en `/en/`;
   - las 11 páginas en los dos idiomas, ya vinculadas entre sí;
   - los menús de cabecera y pie de cada idioma;
   - 3 caballos de ejemplo y las imágenes provisionales (marcadas «PROVISIONAL» en Medios).
6. **CR Sport → Ajustes:** completa el email que recibe las consultas, el email público y los datos legales (titular, NIF, domicilio). Hasta que estén, las páginas legales muestran un aviso amarillo, visible solo cuando has iniciado sesión.
7. **Apariencia → Personalizar → Identidad del sitio:** sube el logo definitivo cuando lo tengas (si no, se usa el monograma CR) y el icono del sitio (favicon). El favicon actual está en `wp-content/uploads/2025/11/ROI-FAVICON-1.png` de la web antigua.

> Con WP-CLI el paso 5 equivale a `wp crsport setup`.

## 3. Plugins recomendados

| Para | Plugin | Notas |
|---|---|---|
| Idiomas | **Polylang** (gratis) | Imprescindible. |
| Correo fiable | **WP Mail SMTP** | Configúralo con un buzón del dominio (p. ej. `web@crsporthorses.es`, creado en Don Dominio). Sin esto, los avisos de consultas pueden acabar en spam. De todos modos, todas las consultas quedan guardadas en *CR Sport → Consultas*. |
| SEO | **Rank Math** o **Yoast SEO** | Compatibles con Polylang. Cuando haya uno activo, el tema le cede la meta descripción. |
| Feed de Instagram | **Smash Balloon Instagram Feed** (gratis) | Requiere conectar la cuenta @crsport_horses (cuenta profesional). En cuanto está conectado aparece solo en la sección Instagram de la portada. |
| Copias y migración | **Duplicator** o **WPvivid Backup** | Para pasar el staging a producción y para las copias periódicas. |
| Caché | **WP Super Cache** o la caché del hosting | Actívala en producción, no en el staging. |
| Imágenes | **Converter for Media** (WebP) | Opcional: aligera las fotos. |

**No hace falta** GTranslate (la traducción es manual y de calidad con Polylang) ni Enfold.

**Cookies:** la nueva web no instala cookies de terceros por defecto. Mapa y vídeos se cargan solo cuando el visitante pulsa, y las fuentes están alojadas en el propio servidor.
- Si se mantiene Google Analytics/Site Kit, hace falta un banner de consentimiento (Complianz o el GDPR Cookie Compliance actual) y añadirlo a la política de cookies.
- Si no se usa analítica, no hace falta banner.

## 4. Revisión en el staging (checklist)

- [ ] Todas las páginas en ES y EN, en móvil y ordenador.
- [ ] Selector ES | EN en cada página y en la ficha de cada caballo.
- [ ] Formulario: envía una consulta de prueba desde cada página. Comprueba que llega el email y que aparece en *CR Sport → Consultas*.
- [ ] Botones de teléfono y WhatsApp abren con el número de Roi (673 22 55 60).
- [ ] Crear un caballo real de principio a fin (ver `docs/mantenimiento.md`) y su traducción.
- [ ] Sustituir todas las imágenes «PROVISIONAL» (Medios → buscar «PROVISIONAL»).
- [ ] Textos legales revisados (y datos de *CR Sport → Ajustes* completos).
- [ ] Velocidad: [PageSpeed Insights](https://pagespeed.web.dev/) sobre la portada y una ficha.

## 5. Paso a producción

Recomendado: migrar el staging completo sobre el dominio principal.

1. Elige un momento tranquilo y avisa a Roi de que la web estará unos minutos en mantenimiento.
2. Haz otra copia completa de la web actual (paso 0).
3. Con **Duplicator** (o WPvivid) crea un paquete del staging. Sube el instalador a la raíz de `crsporthorses.es` y ejecútalo: reemplaza la web vieja y cambia todas las URL de `staging.crsporthorses.es` a `crsporthorses.es`.
4. En la web ya migrada:
   - Ajustes → Lectura → **desmarca** «Disuade a los motores de búsqueda».
   - Quita la protección por contraseña si se copió.
   - Ajustes → Enlaces permanentes → «Guardar» (regenera las reglas).
   - Activa la caché.
   - Comprueba el SSL (https) y que `www.` redirige al dominio sin www (o al revés, pero solo uno).
5. **Redirecciones:** las URL de la web antigua (`/inicio/pupilaje-caballos/`, `/inicio/clases-equitacion/`, `/mail-telefono/`…) ya redirigen con 301 a las páginas nuevas (plugin, `inc/redirects.php`). Revisa en Google Search Console si aparece alguna otra URL antigua con errores.
6. **Google:** en Search Console envía el sitemap (`/wp-sitemap.xml`, o el del plugin SEO). Revisa también la ficha de Google Business Profile: web, teléfono y fotos.
7. Borra el staging, o vuelve a protegerlo, una vez todo esté comprobado.

## 6. Cómo trabajar con cambios futuros

- El código del tema y del plugin vive en este repositorio (`wordpress/wp-content/…`).
- Cambia el código aquí, prueba en staging (subiendo el zip o por SFTP) y después en producción.
- El contenido (textos, fotos, caballos) se edita directamente en el WordPress de producción una vez publicada la web.
- `wp crsport setup --textos` (o la casilla «Reescribir textos») **sobrescribe** las páginas. Úsalo solo en staging antes del lanzamiento.
