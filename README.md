# CR Sport Horses

Web de [crsporthorses.es](https://crsporthorses.es/): centro ecuestre de **Roi Calviño** en el Encoro de Cecebre (A Coruña). Escuela, entrenamiento, pupilaje y venta de caballos de salto. Versión en español e inglés.

## Resumen: qué es qué en este repositorio

| Parte | Qué es | ¿Es la web final? |
|---|---|---|
| **`wordpress/`** | Tema y plugin de WordPress a medida | **Sí.** Es lo que se publicará en `crsporthorses.es`. |
| **Raíz** (`index.html`, `catalogo.html`…, `assets/`) | Demo estática exportada de Stitch | **No.** Solo servía para enseñar el diseño al cliente. |
| `docs/` | Guías de staging, mantenimiento y pendientes | Documentación. |
| `dist/` y `bin/` | ZIP instalables y el script que los genera | Material de trabajo. |

## Historia del proyecto

1. **Demo en Stitch → Vercel.** El diseño se hizo en Stitch y se publicó como demo estática en Vercel, **únicamente para que el cliente viera el diseño**. Vercel no puede alojar WordPress (no ejecuta PHP ni base de datos), así que nunca será el alojamiento real.
2. **Migración a WordPress.** Ese diseño se rehízo como tema y plugin de WordPress: la web real se edita desde el escritorio de WordPress, tiene dos idiomas, formulario de consultas y fichas de caballos que se dan de alta desde un formulario.
3. **Pruebas en local** con la aplicación Local (WordPress de pruebas en el ordenador).
4. **Staging** en un subdominio del hosting de DonDominio (`staging.crsporthorses.es`), para que el cliente lo revise (en curso).
5. **Paso a producción**: sustituir la web actual (WordPress con tema Enfold) por la nueva en `crsporthorses.es`.

El `vercel.json` de la raíz pone `noindex`, así que la demo de Vercel no se indexa en Google.

## Datos de la demo estática: son de ejemplo

Los textos, teléfonos y emails de la demo estática **no son reales**. Por ejemplo: `+34 600 123 456`, `info@crsportshorses.com` (con «s» de más), «Galicia / Madrid», «Doma Clásica», «FEI & RFHE Registered» o los caballos «Cornet d'Or» y similares. Los datos reales están en el WordPress (ver abajo) y en `docs/pendientes-cliente.md`.

## WordPress (web definitiva)

Tema y plugin a medida, bilingües ES/EN con Polylang:

| Ruta | Qué es |
|---|---|
| `wordpress/wp-content/themes/crsport/` | Tema «CR Sport Horses»: diseño, cabecera, pie y ficha de caballo. |
| `wordpress/wp-content/plugins/crsport-core/` | Plugin «CR Sport Horses · Núcleo»: caballos en venta, formulario de consultas, ajustes, textos ES/EN, redirecciones y contenido inicial. |
| `bin/build-zips.sh` | Genera `dist/crsport-tema.zip` y `dist/crsport-core-plugin.zip` para subirlos a WordPress. |

**Instalación rápida:** Polylang → plugin `crsport-core` → tema `crsport` → **CR Sport › Contenido inicial** (o `wp crsport setup`).

> ⚠ **Los ZIP de `dist/` se generan a partir del código.** Si cambias algo en `wordpress/`, ejecuta `./bin/build-zips.sh` antes de subirlos o llevarás a WordPress una versión antigua.
>
> ⚠ **Actualizar el plugin no reescribe las páginas.** Los textos de las páginas viven en la base de datos de WordPress. Marca «Reescribir también los textos» solo en staging y antes de editar a mano: sobrescribe los cambios (por ejemplo, un vídeo de portada).

## Decisiones tomadas

- **Plataforma:** WordPress, con tema y plugin propios (sin Elementor ni plugins de maquetación).
- **Idiomas:** español (por defecto) e inglés, traducidos a mano con **Polylang**. No se usa GTranslate (la web actual sí lo usa).
- **Precios:** no se publican. Los botones «Consultar» llevan al formulario e indican el teléfono de Roi (673 22 55 60) y WhatsApp.
- **Email:** `info@crsporthorses.es` (público y para recibir las consultas). Falta crear el buzón en DonDominio y conectarlo con WP Mail SMTP.
- **Vídeos:** en las fichas de caballos, subidos como MP4 o enlazados desde YouTube, Vimeo o Instagram; se cargan solo al pulsar. Portada con vídeo opcional.
- **Fotos:** las actuales son **provisionales** (de la web antigua y de la demo) y se sustituirán por las de Roi.
- **Mantenimiento:** lo lleva Iago.

## Documentación

| Archivo | Para qué sirve |
|---|---|
| [`docs/guia-staging-y-migracion.md`](docs/guia-staging-y-migracion.md) | Probar en Local, montar el staging en DonDominio y pasar a producción. |
| [`docs/mantenimiento.md`](docs/mantenimiento.md) | Añadir caballos, editar textos, vídeo de portada y gestionar consultas. |
| [`docs/pendientes-cliente.md`](docs/pendientes-cliente.md) | Fotos, datos y confirmaciones que faltan de Roi. |

## Ramas

El trabajo se desarrolla en `claude/compassionate-cray-bu0d3y`. Todavía no está fusionado con `main`.
