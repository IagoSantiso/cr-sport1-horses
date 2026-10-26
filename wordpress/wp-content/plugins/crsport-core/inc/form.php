<?php
/**
 * Formulario de consulta: [crsport_formulario interes="pupilaje"].
 *
 * Cada consulta se envía por email (con «Responder a» del visitante) y además se guarda en
 * «CR Sport › Consultas», para no perder ninguna aunque falle el correo.
 * Antispam sin captcha: campo trampa, tiempo mínimo de relleno firmado y límite por IP.
 */

defined( 'ABSPATH' ) || exit;

function crs_interests(): array {
	return array( 'caballos', 'escuela', 'rutas', 'academy', 'entrenamiento', 'pack', 'pupilaje', 'concursos', 'otro' );
}

add_action(
	'init',
	function () {
		register_post_type(
			'crs_consulta',
			array(
				'labels'          => array(
					'name'          => 'Consultas',
					'singular_name' => 'Consulta',
					'menu_name'     => 'Consultas',
					'all_items'     => 'Consultas',
					'edit_item'     => 'Consulta',
					'not_found'     => 'Todavía no hay consultas',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'crs',
				'supports'        => array( 'title' ),
				'capability_type' => 'post',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
			)
		);
	}
);

/* ---------------------------------------------------------------------------
 * Formulario
 * ------------------------------------------------------------------------- */

add_shortcode( 'crsport_formulario', 'crs_render_form' );

function crs_render_form( $atts = array() ): string {
	$atts = shortcode_atts(
		array(
			'interes' => '',
			'caballo' => '',
			'titulo'  => '',
		),
		$atts
	);

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$interes = sanitize_key( $_GET['interes'] ?? $atts['interes'] );
	$caballo = $atts['caballo'];
	if ( $caballo ) {
		$interes = 'caballos';
	}
	$status = sanitize_key( $_GET['consulta'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$time   = time();
	$token  = $time . '.' . hash_hmac( 'sha256', (string) $time, wp_salt( 'nonce' ) );
	$uid    = wp_unique_id( 'crs-f' );

	ob_start();
	?>
	<form class="crs-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-crs-form novalidate>
		<input type="hidden" name="action" value="crs_consulta">
		<input type="hidden" name="crs_t" value="<?php echo esc_attr( $token ); ?>">
		<input type="hidden" name="crs_lang" value="<?php echo esc_attr( crs_lang() ); ?>">
		<input type="hidden" name="crs_ref" value="<?php echo esc_url( get_permalink() ?: home_url( '/' ) ); ?>">
		<?php if ( $caballo ) : ?>
			<input type="hidden" name="crs_caballo" value="<?php echo esc_attr( $caballo ); ?>">
		<?php endif; ?>
		<div class="crs-form__hp" aria-hidden="true">
			<label>Website <input type="text" name="crs_web" tabindex="-1" autocomplete="off"></label>
		</div>

		<?php if ( $atts['titulo'] ) : ?>
			<p class="crs-form__title"><?php echo esc_html( $atts['titulo'] ); ?></p>
		<?php endif; ?>

		<div class="crs-form__grid">
			<p class="crs-field">
				<label for="<?php echo esc_attr( $uid ); ?>-n"><?php crs_e( 'form_name' ); ?> *</label>
				<input id="<?php echo esc_attr( $uid ); ?>-n" type="text" name="crs_nombre" required autocomplete="name" maxlength="120">
			</p>
			<p class="crs-field">
				<label for="<?php echo esc_attr( $uid ); ?>-p"><?php crs_e( 'form_phone' ); ?> *</label>
				<input id="<?php echo esc_attr( $uid ); ?>-p" type="tel" name="crs_telefono" required autocomplete="tel" maxlength="30">
			</p>
			<p class="crs-field">
				<label for="<?php echo esc_attr( $uid ); ?>-e"><?php crs_e( 'form_email' ); ?> *</label>
				<input id="<?php echo esc_attr( $uid ); ?>-e" type="email" name="crs_email" required autocomplete="email" maxlength="160">
			</p>
			<p class="crs-field">
				<label for="<?php echo esc_attr( $uid ); ?>-i"><?php crs_e( 'form_interest' ); ?></label>
				<select id="<?php echo esc_attr( $uid ); ?>-i" name="crs_interes">
					<?php foreach ( crs_interests() as $key ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $interes, $key ); ?>><?php crs_e( 'interest_' . $key ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php if ( $caballo ) : ?>
				<p class="crs-field crs-field--full crs-field--horse">
					<span class="crs-field__label"><?php crs_e( 'form_horse' ); ?></span>
					<strong><?php echo esc_html( $caballo ); ?></strong>
				</p>
			<?php endif; ?>
			<p class="crs-field crs-field--full">
				<label for="<?php echo esc_attr( $uid ); ?>-m"><?php crs_e( 'form_message' ); ?></label>
				<textarea id="<?php echo esc_attr( $uid ); ?>-m" name="crs_mensaje" rows="4" maxlength="3000" placeholder="<?php echo esc_attr( crs_t( $caballo ? 'form_message_ph_horse' : 'form_message_ph' ) ); ?>"></textarea>
			</p>
		</div>

		<p class="crs-form__consent">
			<label>
				<input type="checkbox" name="crs_privacidad" value="1" required>
				<span>
					<?php
					printf(
						/* translators: %s: enlace a la política de privacidad */
						esc_html( crs_t( 'form_consent' ) ),
						'<a href="' . esc_url( crs_page_url( 'privacidad' ) ) . '" target="_blank">' . esc_html( crs_t( 'privacy_policy' ) ) . '</a>'
					);
					?>
				</span>
			</label>
		</p>
		<p class="crs-form__legal"><?php crs_e( 'form_legal' ); ?></p>

		<div class="crs-form__actions">
			<button type="submit" class="crs-btn crs-btn--primary"><?php crs_e( 'form_submit' ); ?></button>
			<p class="crs-form__or">
				<?php crs_e( 'form_or_call' ); ?>
				<a href="<?php echo esc_attr( crs_tel_href() ); ?>"><?php echo esc_html( crs_opt( 'telefono' ) ); ?></a>
			</p>
		</div>

		<div class="crs-form__status" role="status" aria-live="polite" data-crs-form-status>
			<?php if ( 'ok' === $status ) : ?>
				<p class="is-ok"><?php crs_e( 'form_ok' ); ?></p>
			<?php elseif ( $status ) : ?>
				<p class="is-error"><?php echo esc_html( crs_t( 'form_err_' . $status ) ); ?></p>
			<?php endif; ?>
		</div>
	</form>
	<?php
	return (string) ob_get_clean();
}

/* ---------------------------------------------------------------------------
 * Envío
 * ------------------------------------------------------------------------- */

add_action( 'admin_post_crs_consulta', 'crs_handle_form' );
add_action( 'admin_post_nopriv_crs_consulta', 'crs_handle_form' );

function crs_handle_form(): void {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- formulario público cacheable: se protege con token firmado.
	$p    = wp_unslash( $_POST );
	$lang = 'en' === ( $p['crs_lang'] ?? '' ) ? 'en' : 'es';
	$ref  = wp_validate_redirect( esc_url_raw( $p['crs_ref'] ?? '' ), home_url( '/' ) );
	$ajax = ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'fetch' === $_SERVER['HTTP_X_REQUESTED_WITH'];
	// phpcs:enable

	$finish = function ( string $code ) use ( $ajax, $ref, $lang ) {
		if ( $ajax ) {
			wp_send_json(
				array(
					'ok'      => 'ok' === $code,
					'message' => crs_t( 'ok' === $code ? 'form_ok' : 'form_err_' . $code, $lang ),
				),
				'ok' === $code ? 200 : 400
			);
		}
		wp_safe_redirect( add_query_arg( 'consulta', $code, $ref ) . '#consulta' );
		exit;
	};

	// Trampa para bots: si rellenan el campo oculto, fingimos éxito.
	if ( ! empty( $p['crs_web'] ) ) {
		$finish( 'ok' );
	}

	// Token firmado: el formulario debe haberse cargado hace más de 3 s y menos de 7 días (por si hay caché de página).
	$parts = explode( '.', (string) ( $p['crs_t'] ?? '' ) );
	$ts    = (int) ( $parts[0] ?? 0 );
	$valid = 2 === count( $parts ) && hash_equals( hash_hmac( 'sha256', (string) $ts, wp_salt( 'nonce' ) ), $parts[1] );
	if ( ! $valid || time() - $ts < 3 || time() - $ts > 7 * DAY_IN_SECONDS ) {
		$finish( 'spam' );
	}

	$nombre   = sanitize_text_field( $p['crs_nombre'] ?? '' );
	$email    = sanitize_email( $p['crs_email'] ?? '' );
	$telefono = sanitize_text_field( $p['crs_telefono'] ?? '' );
	$interes  = sanitize_key( $p['crs_interes'] ?? 'otro' );
	$interes  = in_array( $interes, crs_interests(), true ) ? $interes : 'otro';
	$caballo  = sanitize_text_field( $p['crs_caballo'] ?? '' );
	$mensaje  = sanitize_textarea_field( $p['crs_mensaje'] ?? '' );

	if ( '' === $nombre || ! is_email( $email ) || strlen( preg_replace( '/\D/', '', $telefono ) ) < 6 ) {
		$finish( 'fields' );
	}
	if ( empty( $p['crs_privacidad'] ) ) {
		$finish( 'consent' );
	}

	// Límite: 5 consultas por hora y por IP.
	$ip_key = 'crs_rl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		$finish( 'limit' );
	}
	set_transient( $ip_key, $count + 1, HOUR_IN_SECONDS );

	$interes_txt = crs_t( 'interest_' . $interes, 'es' );
	$title       = $nombre . ' · ' . $interes_txt . ( $caballo ? ' · ' . $caballo : '' );

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'crs_consulta',
			'post_status' => 'private',
			'post_title'  => $title,
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		foreach ( compact( 'nombre', 'email', 'telefono', 'interes', 'caballo', 'mensaje', 'lang', 'ref' ) as $key => $value ) {
			update_post_meta( $post_id, '_crs_' . $key, $value );
		}
		update_post_meta( $post_id, '_crs_privacidad', current_time( 'mysql' ) );
	}

	$body  = "Nueva consulta desde la web\n\n";
	$body .= "Nombre: {$nombre}\nTeléfono: {$telefono}\nEmail: {$email}\nInterés: {$interes_txt}\n";
	$body .= $caballo ? "Caballo: {$caballo}\n" : '';
	$body .= 'Idioma: ' . ( 'en' === $lang ? 'inglés' : 'español' ) . "\nPágina: {$ref}\n\n";
	$body .= "Mensaje:\n" . ( $mensaje ?: '—' ) . "\n\n";
	$body .= '— Puedes responder directamente a este correo. Todas las consultas quedan guardadas en el escritorio de WordPress › CR Sport › Consultas.';

	$sent = wp_mail(
		crs_opt( 'email_destino' ),
		'[Web CR Sport] ' . $title,
		$body,
		array( 'Reply-To: ' . $nombre . ' <' . $email . '>' )
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_crs_email_enviado', $sent ? 1 : 0 );
	}

	$finish( 'ok' );
}

/* ---------------------------------------------------------------------------
 * Consultas en el admin
 * ------------------------------------------------------------------------- */

add_filter(
	'manage_crs_consulta_posts_columns',
	fn() => array(
		'cb'           => '<input type="checkbox">',
		'title'        => 'Consulta',
		'crs_contacto' => 'Contacto',
		'crs_mensaje'  => 'Mensaje',
		'date'         => 'Fecha',
	)
);

add_action(
	'manage_crs_consulta_posts_custom_column',
	function ( $column, $post_id ) {
		$m = fn( $k ) => get_post_meta( $post_id, '_crs_' . $k, true );
		if ( 'crs_contacto' === $column ) {
			printf(
				'<a href="mailto:%1$s">%1$s</a><br><a href="tel:%2$s">%3$s</a>%4$s',
				esc_attr( $m( 'email' ) ),
				esc_attr( preg_replace( '/[^\d+]/', '', $m( 'telefono' ) ) ),
				esc_html( $m( 'telefono' ) ),
				'0' === (string) $m( 'email_enviado' ) ? '<br><span style="color:#b32d2e">⚠ El email de aviso no se pudo enviar</span>' : ''
			);
		} elseif ( 'crs_mensaje' === $column ) {
			echo esc_html( wp_trim_words( $m( 'mensaje' ), 30 ) );
		}
	},
	10,
	2
);

add_action(
	'add_meta_boxes_crs_consulta',
	function () {
		remove_meta_box( 'submitdiv', 'crs_consulta', 'side' );
		add_meta_box(
			'crs-consulta',
			'Detalle',
			function ( $post ) {
				$m    = fn( $k ) => get_post_meta( $post->ID, '_crs_' . $k, true );
				$rows = array(
					'Nombre'      => esc_html( $m( 'nombre' ) ),
					'Teléfono'    => '<a href="tel:' . esc_attr( preg_replace( '/[^\d+]/', '', $m( 'telefono' ) ) ) . '">' . esc_html( $m( 'telefono' ) ) . '</a>',
					'Email'       => '<a href="mailto:' . esc_attr( $m( 'email' ) ) . '">' . esc_html( $m( 'email' ) ) . '</a>',
					'Interés'     => esc_html( crs_t( 'interest_' . $m( 'interes' ), 'es' ) ),
					'Caballo'     => esc_html( $m( 'caballo' ) ?: '—' ),
					'Idioma'      => 'en' === $m( 'lang' ) ? 'Inglés' : 'Español',
					'Página'      => '<a href="' . esc_url( $m( 'ref' ) ) . '" target="_blank">' . esc_html( $m( 'ref' ) ) . '</a>',
					'Privacidad'  => 'Aceptada el ' . esc_html( $m( 'privacidad' ) ),
					'Mensaje'     => nl2br( esc_html( $m( 'mensaje' ) ?: '—' ) ),
				);
				echo '<table class="form-table">';
				foreach ( $rows as $label => $value ) {
					echo '<tr><th>' . esc_html( $label ) . '</th><td>' . $value . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
				}
				echo '</table>';
			},
			'crs_consulta',
			'normal',
			'high'
		);
	}
);
