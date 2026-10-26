<?php
/**
 * Ajustes del sitio: datos de contacto, redes y datos legales.
 * Menú «CR Sport» en el escritorio de WordPress.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Campos de ajustes: clave => [etiqueta, valor por defecto, ayuda, sección].
 */
function crs_settings_fields(): array {
	return array(
		'telefono'       => array( 'Teléfono público', '673 22 55 60', 'Tal y como se mostrará en la web.', 'contacto' ),
		'whatsapp'       => array( 'WhatsApp (formato internacional, solo números)', '34673225560', 'Ejemplo: 34673225560.', 'contacto' ),
		'email_publico'  => array( 'Email público', '', 'Se muestra en la web. Déjalo vacío para no mostrar ninguno.', 'contacto' ),
		'email_destino'  => array( 'Email que recibe las consultas', '', 'Si está vacío se usa el email de administración del sitio.', 'contacto' ),
		'direccion'      => array( 'Dirección o ubicación', 'Encoro de Cecebre · A Coruña', 'Texto que se muestra en el pie y en contacto.', 'contacto' ),
		'maps_query'     => array( 'Búsqueda para Google Maps', 'Encoro de Cecebre, A Coruña', 'Dirección exacta o coordenadas (ej.: 43.28,-8.30).', 'contacto' ),
		'horario'        => array( 'Horario de atención', '', 'Opcional. Ej.: Lunes a sábado · 9:00–20:00', 'contacto' ),
		'instagram'      => array( 'Usuario de Instagram (sin @)', 'crsport_horses', '', 'redes' ),
		'facebook'       => array( 'URL de Facebook', '', 'Opcional.', 'redes' ),
		'razon_social'   => array( 'Titular / razón social', '', 'Para el aviso legal y la política de privacidad.', 'legal' ),
		'nif'            => array( 'NIF / CIF', '', '', 'legal' ),
		'domicilio'      => array( 'Domicilio fiscal', '', '', 'legal' ),
		'email_legal'    => array( 'Email para ejercicio de derechos RGPD', '', 'Si está vacío se usa el email público.', 'legal' ),
		'registro'       => array( 'Datos registrales', '', 'Opcional (Registro Mercantil u otros).', 'legal' ),
	);
}

/**
 * Lee un ajuste aplicando su valor por defecto.
 */
if ( ! function_exists( 'crs_opt' ) ) {
	function crs_opt( string $key ): string {
		$opts   = get_option( 'crs_settings', array() );
		$fields = crs_settings_fields();
		$value  = isset( $opts[ $key ] ) && '' !== $opts[ $key ] ? $opts[ $key ] : ( $fields[ $key ][1] ?? '' );

		if ( 'email_destino' === $key && '' === $value ) {
			$value = get_option( 'admin_email' );
		}
		if ( 'email_legal' === $key && '' === $value ) {
			$value = crs_opt( 'email_publico' );
		}
		return (string) $value;
	}
}

if ( ! function_exists( 'crs_tel_href' ) ) {
	function crs_tel_href(): string {
		return 'tel:+34' . preg_replace( '/\D/', '', crs_opt( 'telefono' ) );
	}
}

if ( ! function_exists( 'crs_whatsapp_url' ) ) {
	function crs_whatsapp_url( string $text = '' ): string {
		$url = 'https://wa.me/' . preg_replace( '/\D/', '', crs_opt( 'whatsapp' ) );
		return $text ? $url . '?text=' . rawurlencode( $text ) : $url;
	}
}

function crs_instagram_url(): string {
	return 'https://www.instagram.com/' . rawurlencode( crs_opt( 'instagram' ) ) . '/';
}

if ( ! function_exists( 'crs_maps_url' ) ) {
	function crs_maps_url(): string {
		return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( crs_opt( 'maps_query' ) );
	}
}

/* ---------------------------------------------------------------------------
 * Menú de administración
 * ------------------------------------------------------------------------- */

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			'CR Sport Horses',
			'CR Sport',
			'manage_options',
			'crs',
			'crs_render_settings_page',
			'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="black" d="M10 2a7 7 0 0 0-7 7v7.5a1.5 1.5 0 0 0 3 0V9a4 4 0 0 1 8 0v7.5a1.5 1.5 0 0 0 3 0V9a7 7 0 0 0-7-7z"/></svg>' ),
			26
		);
		add_submenu_page( 'crs', 'Ajustes · CR Sport', 'Ajustes', 'manage_options', 'crs', 'crs_render_settings_page' );
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'crs_settings',
			'crs_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => function ( $input ) {
					$clean = array();
					foreach ( crs_settings_fields() as $key => $field ) {
						$value = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';
						if ( in_array( $key, array( 'email_publico', 'email_destino', 'email_legal' ), true ) ) {
							$value = sanitize_email( $value );
						} elseif ( 'facebook' === $key ) {
							$value = esc_url_raw( $value );
						} elseif ( 'instagram' === $key ) {
							$value = ltrim( sanitize_text_field( $value ), '@' );
						} else {
							$value = sanitize_text_field( $value );
						}
						$clean[ $key ] = $value;
					}
					return $clean;
				},
			)
		);
	}
);

function crs_render_settings_page(): void {
	$opts     = get_option( 'crs_settings', array() );
	$sections = array(
		'contacto' => 'Contacto',
		'redes'    => 'Redes sociales',
		'legal'    => 'Datos legales (aviso legal y privacidad)',
	);
	?>
	<div class="wrap">
		<h1>CR Sport Horses · Ajustes</h1>
		<p>Estos datos se usan en toda la web (cabecera, pie, botones de consulta, fichas de caballos y textos legales) en los dos idiomas.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'crs_settings' ); ?>
			<?php foreach ( $sections as $section => $title ) : ?>
				<h2><?php echo esc_html( $title ); ?></h2>
				<table class="form-table" role="presentation">
					<?php foreach ( crs_settings_fields() as $key => $field ) : ?>
						<?php
						if ( $field[3] !== $section ) {
							continue;
						}
						?>
						<tr>
							<th scope="row"><label for="crs-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
							<td>
								<input class="regular-text" id="crs-<?php echo esc_attr( $key ); ?>" name="crs_settings[<?php echo esc_attr( $key ); ?>]"
									value="<?php echo esc_attr( $opts[ $key ] ?? '' ); ?>" placeholder="<?php echo esc_attr( $field[1] ); ?>">
								<?php if ( $field[2] ) : ?>
									<p class="description"><?php echo esc_html( $field[2] ); ?></p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
