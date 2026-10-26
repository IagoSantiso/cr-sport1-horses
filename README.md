# CR Sport Horses

Web de [crsporthorses.es](https://crsporthorses.es/): centro ecuestre de Roi Calviño en el Encoro de Cecebre (A Coruña).

## WordPress (web definitiva)

Tema y plugin a medida, bilingües ES/EN con Polylang:

| Ruta | Qué es |
|---|---|
| `wordpress/wp-content/themes/crsport/` | Tema «CR Sport Horses»: diseño, cabecera, pie y ficha de caballo. |
| `wordpress/wp-content/plugins/crsport-core/` | Plugin «CR Sport Horses · Núcleo»: caballos en venta, formulario de consultas, ajustes, textos ES/EN, redirecciones y contenido inicial. |
| `bin/build-zips.sh` | Genera `dist/crsport-tema.zip` y `dist/crsport-core-plugin.zip` para subirlos a WordPress. |
| `docs/guia-staging-y-migracion.md` | Cómo montar el staging en Don Dominio y pasarlo a producción. |
| `docs/mantenimiento.md` | Cómo añadir caballos, editar textos y gestionar consultas. |
| `docs/pendientes-cliente.md` | Fotos, datos y confirmaciones que faltan de Roi. |

Instalación rápida: Polylang → plugin `crsport-core` → tema `crsport` → **CR Sport › Contenido inicial** (o `wp crsport setup`).

## Demo estática (diseño de Stitch)

La raíz del repositorio contiene la demo navegable original (`index.html`, `catalogo.html`, `ficha-cornet-d-or.html`, `entrenamiento.html`, `pupilaje.html` y `assets/`), que se publica en Vercel. Sirvió de referencia visual para el tema. Las carpetas `wordpress/`, `docs/` y `bin/` quedan fuera del despliegue (`.vercelignore`).
