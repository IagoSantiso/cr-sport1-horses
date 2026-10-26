# CR Sport Horses · Manual de mantenimiento

## Añadir un caballo en venta

1. **Caballos en venta → Añadir caballo.**
2. **Título:** nombre del caballo. **Editor:** la descripción (carácter, trabajo actual, resultados, para qué jinete es ideal).
3. **Foto principal** (columna derecha): la que sale en el listado. Mejor vertical u horizontal bien centrada en el caballo.
4. **Ficha del caballo:**
   - Datos clave: año de nacimiento (la edad se calcula sola), sexo, raza, capa, alzada y estado.
   - Origen (padre × madre), nivel actual, potencial y enlace a HorseTelex.
   - **Valoración CR**: 5 criterios del 1 al 10. Si se dejan en 0 no se muestran.
   - **Galería**: fotos y vídeos MP4 (comprimidos, máx. ~60 s). Se ordenan arrastrando.
   - **Vídeos externos**: un enlace por línea de YouTube, Vimeo o un reel/post de Instagram. Se cargan solo al pulsar «Ver vídeo».
   - «Destacar en la página de inicio»: aparece en la portada (se muestran 3).
5. **Extracto** (abajo): una frase corta para la tarjeta del listado.
6. **Categoría** (columna derecha): Jóvenes promesas / Amateur / Competición. Sirven para los filtros del listado.
7. **Publicar.**
8. **Versión en inglés:** en la caja «Languages» pulsa el **+** junto a la bandera inglesa. Se crea la traducción con todos los datos técnicos, fotos y vídeos ya copiados (los marcados con ⇄). Solo hay que traducir el nombre si cambia, la descripción, el extracto y los campos de texto (raza, capa, nivel, potencial).

**Para el comprador:** marca «Radiografías disponibles» y «Disponible para prueba» si procede, y añade los resultados destacados (uno por línea). Si el caballo tiene vídeos, el primero aparece en primer lugar en la galería.

**Caballos de competición de Roi:** se crean igual, con el estado **Competición**. No aparecen en el listado de venta ni llevan formulario; se muestran en la página de Roi («Caballos de competición»). Si no tienen foto principal, salen como tarjeta de texto.

**Vender o reservar:** cambia el **Estado**.
- *Reservado*: sigue en el listado con una etiqueta.
- *Vendido*: sale del listado, pero su ficha sigue accesible y sin formulario.

El cambio se aplica también a la versión en inglés.

## Editar textos de las páginas

- **Páginas →** abre la página (ES o EN) y edita con el editor de bloques.
- En el editor se pueden insertar los diseños propios de la web desde «Patrones → CR Sport Horses»: portada, imagen + texto, tarjetas, cifras, bloque «Consultar» y listado de caballos.
- Estilos disponibles en la barra lateral (pestaña «Estilos»):
  - en un grupo: *Sección gris* o *Sección azul marino*;
  - en un párrafo: *Antetítulo*;
  - en una lista: *Lista con marcas*;
  - en un botón: *Claro (sobre foto)*.
- Cada página tiene su traducción. Si cambias algo en español, cámbialo también en la versión inglesa.

## Vídeo en la portada

Cuando haya un vídeo bueno (10–20 s, horizontal, sin sonido, MP4 comprimido de 5–10 MB):
1. Edita la página Inicio y selecciona la imagen de la portada.
2. Sustitúyela por un bloque **Vídeo** con el MP4, y en sus ajustes activa **Reproducción automática**, **Bucle**, **Silenciado** y **Reproducción en línea**.
3. En *Avanzado → Clases CSS adicionales* escribe `crs-hero__media`.
Repite en la versión inglesa.

## Instagram

La sección de Instagram se ha quitado de la portada hasta que el feed esté conectado. Cuando lo esté, insértala desde *Patrones → CR Sport Horses → Instagram*.

## Piezas automáticas (códigos cortos)

| Código | Qué hace |
|---|---|
| `[crsport_consulta interes="pupilaje"]` | Bloque «Consultar»: texto, teléfono, WhatsApp y formulario con el interés preseleccionado. |
| `[crsport_formulario]` | Solo el formulario. |
| `[crsport_caballos limite="3" destacados="si" filtros="si"]` | Listado de caballos en venta. |
| `[crsport_caballos estado="competicion"]` | Caballos de competición de Roi. |
| `[crsport_contacto]` | Datos de contacto. |
| `[crsport_telefono]` | Teléfono enlazado dentro de un texto. |
| `[crsport_instagram]` | Feed de Instagram (si Smash Balloon está conectado) + botón al perfil. |
| `[crsport_mapa]` | Mapa de Google que solo se carga al pulsar. |
| `[crsport_dato campo="nif"]` | Un dato de *CR Sport → Ajustes*. |

Valores de `interes`: `caballos`, `escuela`, `rutas`, `academy`, `entrenamiento`, `pack`, `pupilaje`, `concursos`, `otro`.

Cualquier enlace del tipo `?interes=rutas#consulta` baja al formulario de la misma página con esa opción ya elegida.

## Teléfono, emails, dirección, Instagram, datos legales

También el enlace al perfil FEI de Roi. Se editan en **CR Sport → Ajustes** y se actualizan en toda la web y en los dos idiomas.

## Consultas recibidas

En **CR Sport → Consultas** quedan todas, con nombre, teléfono, email, interés, caballo, idioma y la página desde la que escribieron. Si el aviso por email falló, la consulta aparece marcada en rojo.

## Textos fijos (menú, etiquetas de la ficha, formulario…)

Están en `wp-content/plugins/crsport-core/inc/strings.php`, en ES y EN. Para cambiar uno, edita el fichero en el repositorio y vuelve a subir el plugin.

## Archivos del proyecto

```
wordpress/wp-content/themes/crsport/         Tema (diseño)
  theme.json                                 Colores, tipografías y anchos
  assets/css/main.css · assets/js/main.js    Estilos e interacción
  header.php · footer.php · page.php         Cabecera, pie y páginas
  single-caballo.php                         Ficha de caballo
  patterns/                                  Patrones para el editor
wordpress/wp-content/plugins/crsport-core/   Plugin (funcionalidad)
  inc/horses.php                             Caballos, campos y valoración
  inc/form.php                               Formulario y consultas
  inc/shortcodes.php                         Piezas automáticas
  inc/settings.php                           CR Sport → Ajustes
  inc/strings.php                            Textos fijos ES/EN
  inc/redirects.php                          Redirecciones de la web antigua
  inc/setup.php · inc/content/               Contenido inicial (páginas ES/EN)
```
