# CR Sport Horses · Guía de staging y paso a producción

Dominio: **crsporthorses.es** (Don Dominio, hosting contratado hasta el 04/08/2027).
Web actual: WordPress 7.1.3 con tema Enfold, GTranslate, GDPR Cookie Compliance y Site Kit.

La nueva web es un **tema y un plugin de WordPress a medida** (carpeta `wordpress/`). La demo estática de la raíz del repositorio y su despliegue en Vercel eran solo para enseñar el diseño: **no son la web final** (ver `README.md`).

Orden de trabajo: **Local** (pruebas en tu ordenador) → **staging** (copia privada en DonDominio para el cliente) → **producción** (sustituye a la web actual en una sola operación).

---

## Probar en Local (ordenador)

1. En la aplicación **Local**, crea un sitio nuevo (o usa uno existente) y abre **WP Admin**.
2. Instala y activa **Polylang**; sube y activa `crsport-core-plugin.zip`; sube y **activa** `crsport-tema.zip`.
3. **CR Sport → Contenido inicial → Crear contenido inicial.**
4. **Para enseñarlo ya al cliente:** activa **Live Link** en Local (URL pública temporal mientras Local esté abierto).
5. **Al actualizar** el tema o el plugin, sube el ZIP nuevo y acepta «Reemplazar». **No marques «Reescribir también los textos»** si has editado páginas a mano (por ejemplo, un vídeo de portada): esa casilla las sobrescribe.
6. Si ves el diseño del tema anterior, falta **activar el tema** en *Apariencia → Temas*.
7. Las páginas sobrantes de pruebas anteriores (Elementor, «Privacy Policy» en borrador, «Home – English» duplicada por Polylang) se pueden mandar a la papelera.
8. En Local no se envían emails; las consultas del formulario sí quedan guardadas en *CR Sport → Consultas*.

---

## 0. Antes de tocar nada: copia de la web actual

1. En el panel de Don Dominio, haz una **copia de seguridad completa** (ficheros + base de datos) del hosting.
2. Descárgala también en local. Si algo sale mal el día de la migración, se restaura en minutos.

## 1. Crear el staging (DonDominio)

1. **Subdominio:** en el panel del hosting crea `staging.crsporthorses.es`. Aparece con las opciones *Instalar SSL*, *Acceder a WebFTP*, *Instalar aplicación* y *Ver aplicaciones instaladas*.
2. **⚠ Directorio propio.** El subdominio debe apuntar a **su propia carpeta** (por ejemplo `/staging`), **nunca** a la carpeta donde está la web actual. Entra en *Acceder a WebFTP*, crea la carpeta `staging`, y desde el menú ⋮ del subdominio cambia el directorio a `/staging`. Si se instala WordPress sobre la carpeta de la web actual, se pisa `crsporthorses.es`.
3. **Instalar WordPress:** con *Instalar aplicación → WordPress*, dominio `staging.crsporthorses.es`, carpeta vacía, usuario administrador que no sea «admin» y contraseña larga. El instalador crea la base de datos; si la pide, créala antes en el apartado de bases de datos. No reutilices la de la web actual.
4. **HTTPS:**
   - El botón *Instalar SSL* del panel abre una ventana para **pegar un certificado ya comprado** (certificado, clave y autoridad). No es el gratuito.
   - Para un staging, pide a soporte de DonDominio que activen el certificado gratuito (Let's Encrypt) para el subdominio, o pregunta si está disponible en tu plan.
   - Un certificado de `crsporthorses.es` **no** vale para el subdominio salvo que sea un comodín (`*.crsporthorses.es`), y nunca compartas la clave privada.
   - Mientras tanto puedes trabajar sin SSL (el navegador marcará «No seguro»). Cuando lo tengas, cambia las dos URL de *Ajustes → Generales* a `https://`.
5. **Privacidad del staging:**
   - *Ajustes → Lectura* → marca **«Disuadir a los motores de búsqueda…»**.
   - Instala el plugin **Password Protected** y activa una contraseña (la que le darás a Roi). Tú, como administrador, entras sin ella.
6. **Ajustes → Enlaces permanentes:** «Nombre de la entrada».

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
6. **CR Sport → Ajustes:** el email público y el que recibe las consultas ya vienen puestos (`info@crsporthorses.es`). Completa los datos legales (titular, NIF, domicilio). Hasta que estén, las páginas legales muestran un aviso amarillo, visible solo cuando has iniciado sesión.
7. **Apariencia → Personalizar → Identidad del sitio:** sube el logo definitivo cuando lo tengas (si no, se usa el monograma CR) y el icono del sitio (favicon). El favicon actual está en `wp-content/uploads/2025/11/ROI-FAVICON-1.png` de la web antigua.

> Con WP-CLI el paso 5 equivale a `wp crsport setup`.

## 3. Plugins recomendados

| Para | Plugin | Notas |
|---|---|---|
| Idiomas | **Polylang** (gratis) | Imprescindible. |
| Correo fiable | **WP Mail SMTP** | Configúralo con el buzón `info@crsporthorses.es` (creado en DonDominio), que es el que recibe las consultas. Sin esto, los avisos de consultas pueden acabar en spam. De todos modos, todas las consultas quedan guardadas en *CR Sport → Consultas*. |
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

> ⚠ **No instales el plugin, Polylang ni el tema en la web en vivo antes del lanzamiento**, ni pulses «Crear contenido inicial» allí: cambiaría la portada, los menús y los enlaces de la web actual al instante, y la versión inglesa de GTranslate (`/en/`) chocaría con la de Polylang. Todo se prueba en Local y en el staging.

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
8. La **demo estática de Vercel** ya no hace falta: se puede eliminar el proyecto de Vercel cuando el cliente haya aprobado la web.

## 6. Cómo trabajar con cambios futuros

- El código del tema y del plugin vive en este repositorio (`wordpress/wp-content/…`).
- Cambia el código aquí, prueba en staging (subiendo el zip o por SFTP) y después en producción.
- El contenido (textos, fotos, caballos) se edita directamente en el WordPress de producción una vez publicada la web.
- `wp crsport setup --textos` (o la casilla «Reescribir textos») **sobrescribe** las páginas. Úsalo solo en staging antes del lanzamiento.
