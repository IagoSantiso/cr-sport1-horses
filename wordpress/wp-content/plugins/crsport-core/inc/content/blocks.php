<?php
/**
 * Pequeños ayudantes para escribir el contenido inicial con bloques nativos de WordPress.
 * Generan exactamente el HTML que guarda el editor, para que todo se pueda editar después
 * sin avisos de «bloque no válido».
 */

defined( 'ABSPATH' ) || exit;

function crs_b( string $name, array $attrs, string $html ): string {
	$name  = str_replace( 'core/', '', $name );
	$attrs = $attrs ? ' ' . serialize_block_attributes( $attrs ) : '';
	return "<!-- wp:{$name}{$attrs} -->\n{$html}\n<!-- /wp:{$name} -->\n";
}

function crs_class_attr( array $classes ): string {
	$classes = trim( implode( ' ', array_filter( $classes ) ) );
	return $classes ? ' class="' . esc_attr( $classes ) . '"' : '';
}

/**
 * Reparte un className en atributo de bloque.
 */
function crs_with_class( array $attrs, string $class ): array {
	if ( $class ) {
		$attrs['className'] = $class;
	}
	return $attrs;
}

function crs_p( string $html, string $class = '' ): string {
	return crs_b( 'paragraph', crs_with_class( array(), $class ), '<p' . crs_class_attr( array( $class ) ) . '>' . $html . '</p>' );
}

function crs_h( string $html, int $level = 2, string $class = '' ): string {
	$attrs = crs_with_class( 2 === $level ? array() : array( 'level' => $level ), $class );
	return crs_b( 'heading', $attrs, "<h{$level}" . crs_class_attr( array( 'wp-block-heading', $class ) ) . ">{$html}</h{$level}>" );
}

/**
 * Grupo. $opts: tag, align, anchor, layout (array), class.
 */
function crs_group( string $inner, array $opts = array() ): string {
	$attrs = array();
	if ( ! empty( $opts['anchor'] ) ) {
		$attrs['anchor'] = $opts['anchor'];
	}
	$tag = $opts['tag'] ?? 'div';
	if ( 'div' !== $tag ) {
		$attrs['tagName'] = $tag;
	}
	if ( ! empty( $opts['align'] ) ) {
		$attrs['align'] = $opts['align'];
	}
	$class = $opts['class'] ?? '';
	$attrs = crs_with_class( $attrs, $class );
	$attrs['layout'] = $opts['layout'] ?? array( 'type' => 'constrained' );

	$id   = ! empty( $opts['anchor'] ) ? ' id="' . esc_attr( $opts['anchor'] ) . '"' : '';
	$html = "<{$tag}{$id}" . crs_class_attr( array( 'wp-block-group', ! empty( $opts['align'] ) ? 'align' . $opts['align'] : '', $class ) ) . ">\n{$inner}</{$tag}>";
	return crs_b( 'group', $attrs, $html );
}

/**
 * Rejilla adaptable (grupo con distribución en cuadrícula).
 */
function crs_grid( string $inner, string $class = '', string $min = '16rem', int $cols = 0 ): string {
	$layout = array(
		'type'               => 'grid',
		'minimumColumnWidth' => $min,
	);
	if ( $cols ) {
		$layout['columnCount'] = $cols; // Máximo de columnas; con poco ancho se reparten solas.
	}
	return crs_group(
		$inner,
		array(
			'class'  => $class,
			'layout' => $layout,
		)
	);
}

/**
 * Columnas: $columns = [ [html, width|null, class], … ].
 */
function crs_columns( array $columns, string $class = '', string $align = 'wide' ): string {
	$inner = '';
	foreach ( $columns as $col ) {
		$html  = $col[0];
		$width = $col[1] ?? null;
		$cc    = $col[2] ?? '';
		$attrs = crs_with_class( $width ? array( 'width' => $width ) : array(), $cc );
		$style = $width ? ' style="flex-basis:' . esc_attr( $width ) . '"' : '';
		$inner .= crs_b( 'column', $attrs, '<div' . crs_class_attr( array( 'wp-block-column', $cc ) ) . $style . ">\n{$html}</div>" );
	}
	$attrs = crs_with_class( $align ? array( 'align' => $align ) : array(), $class );
	return crs_b( 'columns', $attrs, '<div' . crs_class_attr( array( 'wp-block-columns', $align ? 'align' . $align : '', $class ) ) . ">\n{$inner}</div>" );
}

function crs_list( array $items, string $class = '' ): string {
	$inner = '';
	foreach ( $items as $item ) {
		$inner .= crs_b( 'list-item', array(), "<li>{$item}</li>" );
	}
	return crs_b( 'list', crs_with_class( array(), $class ), '<ul' . crs_class_attr( array( 'wp-block-list', $class ) ) . ">\n{$inner}</ul>" );
}

/**
 * Botones: $buttons = [ [texto, url, estilo ('' | 'outline' | 'light')], … ].
 */
function crs_buttons( array $buttons, string $class = '' ): string {
	$inner = '';
	foreach ( $buttons as $btn ) {
		$style  = ! empty( $btn[2] ) ? 'is-style-' . ( 'outline' === $btn[2] ? 'outline' : 'crs-' . $btn[2] ) : '';
		$inner .= crs_b(
			'button',
			crs_with_class( array(), $style ),
			'<div' . crs_class_attr( array( 'wp-block-button', $style ) ) . '><a class="wp-block-button__link wp-element-button" href="' . esc_url( $btn[1] ) . '">' . $btn[0] . '</a></div>'
		);
	}
	return crs_b( 'buttons', crs_with_class( array(), $class ), '<div' . crs_class_attr( array( 'wp-block-buttons', $class ) ) . ">\n{$inner}</div>" );
}

function crs_image( int $id, string $alt, string $size = 'large', string $class = '', string $caption = '' ): string {
	if ( ! $id ) {
		return '';
	}
	$src   = wp_get_attachment_image_url( $id, $size );
	$attrs = crs_with_class(
		array(
			'id'              => $id,
			'sizeSlug'        => $size,
			'linkDestination' => 'none',
		),
		$class
	);
	return crs_b(
		'image',
		$attrs,
		'<figure' . crs_class_attr( array( 'wp-block-image', 'size-' . $size, $class ) ) . '><img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . $id . '"/>'
		. ( $caption ? '<figcaption class="wp-element-caption">' . $caption . '</figcaption>' : '' ) . '</figure>'
	);
}

function crs_shortcode( string $code ): string {
	return "<!-- wp:shortcode -->\n{$code}\n<!-- /wp:shortcode -->\n";
}

function crs_details( string $summary, string $inner ): string {
	return crs_b( 'details', array(), "<details class=\"wp-block-details\"><summary>{$summary}</summary>{$inner}</details>" );
}

/* ---------------------------------------------------------------------------
 * Composiciones del diseño CR (usadas por el contenido inicial y los patrones)
 * ------------------------------------------------------------------------- */

/**
 * Cabecera de sección: antetítulo + título + entradilla.
 */
function crs_section_head( string $eyebrow, string $title, string $lead = '', string $class = '' ): string {
	$inner  = $eyebrow ? crs_p( $eyebrow, 'is-style-crs-eyebrow' ) : '';
	$inner .= crs_h( $title, 2 );
	$inner .= $lead ? crs_p( $lead, 'crs-lead' ) : '';
	return crs_group( $inner, array( 'class' => trim( 'crs-section-head ' . $class ) ) );
}

/**
 * Sección a todo el ancho. $style: '' | 'soft' | 'dark'.
 */
function crs_section( string $inner, string $style = '', string $anchor = '', string $extra = '' ): string {
	$class = trim( 'crs-section ' . ( $style ? 'is-style-crs-' . $style : '' ) . ' ' . $extra );
	return crs_group(
		$inner,
		array(
			'tag'    => 'section',
			'align'  => 'full',
			'anchor' => $anchor,
			'class'  => $class,
		)
	);
}

/**
 * Portada: imagen de fondo + antetítulo + H1 + entradilla + botones.
 */
function crs_hero( int $image, string $alt, string $eyebrow, string $title, string $lead, array $buttons = array(), string $variant = 'page' ): string {
	$content  = crs_p( $eyebrow, 'is-style-crs-eyebrow' );
	$content .= crs_h( $title, 1 );
	$content .= $lead ? crs_p( $lead, 'crs-lead' ) : '';
	$content .= $buttons ? crs_buttons( $buttons ) : '';

	$inner  = crs_image( $image, $alt, 'full', 'crs-hero__media' );
	$inner .= crs_group( $content, array( 'class' => 'crs-hero__content' ) );
	return crs_group(
		$inner,
		array(
			'tag'   => 'section',
			'align' => 'full',
			'class' => 'crs-hero crs-hero--' . $variant,
		)
	);
}

/**
 * Tarjeta: número/etiqueta + título + texto + enlace opcional.
 */
function crs_card( string $title, string $text, string $label = '', array $link = array(), string $class = '' ): string {
	$inner  = $label ? crs_p( $label, 'crs-card__label' ) : '';
	$inner .= crs_h( $title, 3 );
	$inner .= crs_p( $text );
	$inner .= $link ? crs_p( '<a href="' . esc_url( $link[1] ) . '">' . $link[0] . '</a>', 'crs-card__link' ) : '';
	return crs_group( $inner, array( 'class' => trim( 'crs-card ' . $class ) ) );
}

/**
 * Dato destacado: cifra + descripción.
 */
function crs_stat( string $value, string $label ): string {
	return crs_group( crs_p( $value, 'crs-stat__value' ) . crs_p( $label, 'crs-stat__label' ), array( 'class' => 'crs-stat' ) );
}

/**
 * Franja de pruebas de nivel (resultados destacados). $items = [ [cifra/título, detalle], … ].
 */
function crs_proof( array $items, string $link = '' ): string {
	$inner = '';
	foreach ( $items as $item ) {
		$inner .= crs_stat( $item[0], $item[1] );
	}
	return crs_grid( $inner, 'crs-stats crs-proof', '12rem', count( $items ) ) . $link;
}

/**
 * Subtítulo de grupo dentro de una sección (p. ej. «Deporte» / «Escuela»).
 */
function crs_subhead( string $title, string $text = '' ): string {
	return crs_group( crs_h( $title, 3 ) . ( $text ? crs_p( $text ) : '' ), array( 'class' => 'crs-subhead' ) );
}

/**
 * Bloque imagen + texto a dos columnas.
 */
function crs_split( string $image_html, string $text_html, bool $reverse = false, string $class = '' ): string {
	$cols = array(
		array( $image_html, null, 'crs-split__media' ),
		array( $text_html, null, 'crs-split__text' ),
	);
	return crs_columns( $reverse ? array_reverse( $cols ) : $cols, trim( 'crs-split ' . ( $reverse ? 'crs-split--reverse ' : '' ) . $class ) );
}
