<?php
/**
 * Contenido inicial de las páginas, en español e inglés.
 *
 * Fuente: notas funcionales de Roi (19/10/2026) y tarifas 2026 (sin publicar precios).
 * Todo se crea con bloques nativos y se edita después desde el editor de WordPress.
 *
 * @param string   $lang 'es' | 'en'.
 * @param callable $img  fn( string $key ): array{0:int,1:string} → [ID de imagen, texto alternativo].
 * @param callable $url  fn( string $page_key ): string → URL de otra página en el mismo idioma.
 */

defined( 'ABSPATH' ) || exit;

function crs_seed_page_defs(): array {
	return array(
		// clave => [ título ES, slug ES, título EN, slug EN ]
		'inicio'         => array( 'Inicio', 'inicio', 'Home', 'home' ),
		'caballos'       => array( 'Caballos en venta', 'caballos-en-venta', 'Horses for sale', 'horses-for-sale' ),
		'escuela'        => array( 'Escuela', 'escuela', 'Riding school', 'riding-school' ),
		'entrenamiento'  => array( 'Entrenamiento', 'entrenamiento', 'Training', 'training' ),
		'pupilaje'       => array( 'Pupilaje', 'pupilaje', 'Livery', 'livery' ),
		'instalaciones'  => array( 'Instalaciones', 'instalaciones', 'Facilities', 'facilities' ),
		'roi'            => array( 'Roi Calviño', 'roi-calvino', 'Roi Calviño', 'roi-calvino-rider' ),
		'contacto'       => array( 'Contacto', 'contacto', 'Contact', 'contact' ),
		'aviso_legal'    => array( 'Aviso legal', 'aviso-legal', 'Legal notice', 'legal-notice' ),
		'privacidad'     => array( 'Política de privacidad', 'politica-de-privacidad', 'Privacy policy', 'privacy-policy' ),
		'cookies'        => array( 'Política de cookies', 'politica-de-cookies', 'Cookie policy', 'cookie-policy' ),
	);
}

function crs_seed_page_content( string $key, string $lang, callable $img, callable $url ): array {
	$t   = fn( string $es, string $en ) => 'en' === $lang ? $en : $es;
	$im  = function ( string $k, string $size = 'large', string $class = '' ) use ( $img ) {
		[ $id, $alt ] = $img( $k );
		return crs_image( $id, $alt, $size, $class );
	};
	$her = function ( string $k ) use ( $img ) {
		return $img( $k );
	};
	$consult = fn( string $interes, string $titulo = '', string $texto = '' ) => crs_section(
		crs_shortcode( sprintf( '[crsport_consulta interes="%s"%s%s]', $interes, $titulo ? ' titulo="' . esc_attr( $titulo ) . '"' : '', $texto ? ' texto="' . esc_attr( $texto ) . '"' : '' ) ),
		'soft',
		'',
		'crs-section--consult'
	);
	$ask = fn( string $interes ) => '?interes=' . $interes . '#consulta';

	switch ( $key ) {

		/* ================================================================ INICIO */
		case 'inicio':
			[ $hid, $halt ] = $her( 'hero-home' );
			$fei = crs_opt( 'fei_url' );
			$c   = crs_hero(
				$hid,
				$halt,
				$t( 'CR Sport Horses · Encoro de Cecebre, A Coruña', 'CR Sport Horses · Cecebre, A Coruña, Spain' ),
				$t( 'Roi Calviño. <em>Salto internacional.</em>', 'Roi Calviño. <em>International show jumping.</em>' ),
				$t(
					'Jinete de Copa de Naciones y fundador de CR Sport Horses. Venta de caballos de salto, entrenamiento de competición, pupilaje y escuela, a 15 minutos de A Coruña.',
					'Nations Cup rider and founder of CR Sport Horses. Show jumping horses for sale, competition training, livery and a riding school, 15 minutes from A Coruña, Spain.'
				),
				array(
					array( $t( 'Caballos en venta', 'Horses for sale' ), $url( 'caballos' ) ),
					array( $t( 'Conoce a Roi', 'Meet Roi' ), $url( 'roi' ), 'light' ),
				),
				'home'
			);

			// Franja de resultados: lo primero que se ve tras la portada.
			$c .= crs_section(
				crs_proof(
					array(
						array( $t( 'Copa de Naciones', 'Nations Cup' ), $t( '4º en la Final · 2026', '4th at the Final · 2026' ) ),
						array( 'CSIO 5* Gijón', $t( 'Gran Premio · Las Mestas', 'Grand Prix · Las Mestas' ) ),
						array( $t( 'Campeón de España', 'Spanish Champion' ), $t( 'Jóvenes Jinetes · 2019', 'Young Riders · 2019' ) ),
						array( $t( '1º Gran Premio', 'Grand Prix winner' ), 'CSI1* Casas Novas · A Coruña 2023' ),
					),
					$fei ? crs_p( '<a href="' . esc_url( $fei ) . '" target="_blank" rel="noopener">' . $t( 'Perfil oficial en la FEI', 'Official FEI profile' ) . '</a>', 'crs-proof__link' ) : ''
				),
				'dark',
				'',
				'crs-section--proof'
			);

			$c .= crs_section(
				crs_split(
					$im( 'roi-salto' ),
					crs_p( $t( 'Dirección deportiva', 'Head rider & coach' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Un jinete de competición detrás de cada caballo', 'A competition rider behind every horse' ) )
					. crs_p(
						$t(
							'Roi Calviño Rabón compite sin interrupción desde hace más de quince años en los circuitos nacionales 4* y 5* y en concursos internacionales. En marzo de 2022 fundó CR Sport Horses, la empresa familiar desde la que selecciona y prepara caballos de salto, entrena a jinetes de competición y dirige la escuela.',
							'Roi Calviño Rabón has competed without interruption for more than fifteen years on Spain’s 4* and 5* national circuits and at international shows. In March 2022 he founded CR Sport Horses, the family business through which he selects and produces show jumpers, trains competition riders and runs the riding school.'
						),
						'crs-lead'
					)
					. crs_buttons(
						array(
							array( $t( 'Trayectoria completa', 'Full career' ), $url( 'roi' ) ),
							array( $t( 'Perfil FEI', 'FEI profile' ), $fei, 'outline' ),
						)
					)
				),
				'',
				'centro'
			);

			$c .= crs_section(
				crs_section_head(
					$t( 'Caballos en venta', 'Horses for sale' ),
					$t( 'Seleccionados y montados por Roi', 'Selected and ridden by Roi' ),
					$t( 'Cada caballo se monta y se valora a diario antes de salir a la venta. En su ficha: vídeos, origen, nivel, resultados y nuestra valoración técnica.', 'Every horse is ridden and assessed daily before it goes on sale. Each profile includes videos, pedigree, level, results and our technical assessment.' )
				)
				. crs_shortcode( '[crsport_caballos limite="3" destacados="si"]' )
				. crs_buttons( array( array( $t( 'Ver todos los caballos', 'View all horses' ), $url( 'caballos' ) ) ), 'crs-center' ),
				'soft'
			);

			$c .= crs_section(
				crs_section_head(
					$t( 'Servicios', 'Services' ),
					$t( 'Deporte y escuela, con el mismo método', 'Sport and school, one method' )
				)
				. crs_subhead( $t( 'Deporte', 'Sport' ), $t( 'Para jinetes y propietarios que compiten.', 'For riders and owners who compete.' ) )
				. crs_grid(
					crs_card( $t( 'Venta de caballos', 'Horses for sale' ), $t( 'Caballos de salto seleccionados y preparados por Roi, con ficha completa, vídeos y origen.', 'Show jumpers selected and produced by Roi, with full profiles, videos and pedigree.' ), '01', array( $t( 'Ver caballos', 'View horses' ), $url( 'caballos' ) ) )
					. crs_card( $t( 'Entrenamiento y concursos', 'Training & shows' ), $t( 'Sesiones con tu caballo, plan de competición y asistencia en concurso, dentro y fuera de Galicia.', 'Sessions with your horse, a competition plan and show support, in Galicia and beyond.' ), '02', array( $t( 'Ver entrenamiento', 'Training' ), $url( 'entrenamiento' ) ) )
					. crs_card( $t( 'Pupilaje', 'Livery' ), $t( 'Box propio, alimentación cuidada y acceso a toda la instalación, solo o con entrenamiento.', 'Your own stable, careful feeding and access to the whole facility, with or without training.' ), '03', array( $t( 'Ver pupilaje', 'Livery' ), $url( 'pupilaje' ) ) ),
					'crs-cards',
					'16rem',
					3
				)
				. crs_subhead( $t( 'Escuela', 'Riding school' ), $t( 'Para quien empieza o quiere dar el salto a la competición.', 'For beginners and for those taking the step into competition.' ) )
				. crs_grid(
					crs_card( 'CR Sports Academy', $t( 'Tecnificación en salto para niños y jóvenes jinetes. El caballo lo ponemos nosotros.', 'Show jumping development for children and young riders. We provide the horse.' ), '', array( $t( 'Ver academy', 'The Academy' ), $url( 'escuela' ) . '#academy' ) )
					. crs_card( $t( 'Escuela de equitación', 'Riding lessons' ), $t( 'Clases para niños y adultos con nuestros caballos, con convenio oficial con el Ayuntamiento.', 'Lessons for children and adults on our horses, officially partnered with the local council.' ), '', array( $t( 'Ver escuela', 'Riding school' ), $url( 'escuela' ) . '#clases' ) )
					. crs_card( $t( 'Rutas por Cecebre', 'Rides around Cecebre' ), $t( 'Rutas guiadas a caballo por los caminos del Encoro de Cecebre.', 'Guided rides along the paths around the Cecebre reservoir.' ), '', array( $t( 'Ver rutas', 'Trail rides' ), $url( 'escuela' ) . '#rutas' ) ),
					'crs-cards crs-cards--steps',
					'16rem',
					3
				)
			);

			$c .= crs_section(
				crs_section_head(
					$t( 'Instalaciones', 'Facilities' ),
					$t( 'Pensadas para el caballo de deporte', 'Designed for the sport horse' )
				)
				. crs_grid(
					crs_stat( '70 × 35 m', $t( 'Pista de arena de sílice con geotextil', 'Silica sand arena with geotextile base' ) )
					. crs_stat( '26', $t( 'Cuadras adaptadas de 3 × 3 m', 'Purpose-built 3 × 3 m stables' ) )
					. crs_stat( '6', $t( 'Plazas en el caminador', 'Horse walker places' ) )
					. crs_stat( '1.000 m²', $t( 'De paddocks de gran formato', 'Of large paddocks' ) ),
					'crs-stats',
					'11rem',
					4
				)
				. crs_buttons( array( array( $t( 'Ver instalaciones', 'View facilities' ), $url( 'instalaciones' ), 'outline' ) ) ),
				'soft'
			);

			[ $cid, $calt ] = $her( 'cecebre' );
			$c .= crs_section(
				crs_split(
					crs_image( $cid, $calt, 'large', '', crs_seed_image_credit( 'cecebre', $lang ) ),
					crs_p( $t( 'Dónde estamos', 'Where we are' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Encoro de Cecebre, A Coruña', 'Cecebre reservoir, A Coruña' ) )
					. crs_p(
						$t(
							'El centro está junto al Encoro de Cecebre, un espacio natural protegido muy cerca de Betanzos: tranquilidad para el caballo y buenas comunicaciones para quien viene de fuera, con dos aeropuertos a menos de una hora.',
							'The centre sits beside the Cecebre reservoir, a protected natural area close to the historic town of Betanzos: peace and quiet for the horses and easy access for visitors from abroad, with two airports less than an hour away.'
						)
					)
					. crs_grid(
						crs_stat( '15 min', 'A Coruña' )
						. crs_stat( '40 min', $t( 'Santiago de Compostela y Ferrol', 'Santiago de Compostela & Ferrol' ) )
						. crs_stat( '45 min', 'Lugo' )
						. crs_stat( '20 min', $t( 'Aeropuerto de A Coruña', 'A Coruña Airport' ) )
						. crs_stat( '40 min', $t( 'Aeropuerto de Santiago', 'Santiago Airport' ) ),
						'crs-stats crs-stats--compact',
						'8rem',
						3
					),
					true
				),
				'dark'
			);

			$c .= $consult( 'otro' );

			return array(
				'content' => $c,
				'excerpt' => $t(
					'CR Sport Horses, el centro de Roi Calviño en el Encoro de Cecebre (A Coruña): venta de caballos de salto, entrenamiento de competición, pupilaje y escuela de equitación.',
					'CR Sport Horses, Roi Calviño’s yard at the Cecebre reservoir (A Coruña, Spain): show jumping horses for sale, competition training, livery and riding school.'
				),
			);

		/* ================================================================ CABALLOS */
		case 'caballos':
			[ $hid, $halt ] = $her( 'hero-caballos' );
			$c  = crs_hero(
				$hid,
				$halt,
				$t( 'Venta de caballos', 'Horses for sale' ),
				$t( 'Caballos en venta', 'Horses for sale' ),
				$t(
					'Caballos de salto seleccionados y preparados en nuestras instalaciones. Cada ficha incluye fotos, vídeos, origen y nuestra valoración técnica, con enlace a su registro en HorseTelex.',
					'Show jumping horses selected and produced at our yard. Each profile includes photos, videos, pedigree and our technical assessment, with a link to the horse’s HorseTelex record.'
				)
			);
			$c .= crs_section( crs_shortcode( '[crsport_caballos filtros="si"]' ), '', 'listado' );
			$c .= crs_section(
				crs_section_head( $t( 'Cómo trabajamos', 'How we work' ), $t( 'Comprar con confianza', 'Buy with confidence' ) )
				. crs_grid(
					crs_card( $t( 'Selección', 'Selection' ), $t( 'Elegimos cada caballo por su físico, su carácter y su capacidad para el salto.', 'Each horse is chosen for its conformation, temperament and jumping ability.' ), '01' )
					. crs_card( $t( 'Preparación y valoración', 'Production & assessment' ), $t( 'Lo montamos y evaluamos a diario. La valoración CR resume, criterio a criterio, lo que vemos en pista.', 'We ride and assess every horse daily. The CR assessment sums up, point by point, what we see in the ring.' ), '02' )
					. crs_card( $t( 'Prueba y acompañamiento', 'Trial & support' ), $t( 'Organizamos la prueba en nuestras instalaciones y te acompañamos durante todo el proceso de compra.', 'We arrange a trial at our facilities and support you throughout the whole buying process.' ), '03' ),
					'crs-cards crs-cards--steps',
					'16rem',
					3
				),
				'soft'
			);
			$c .= $consult(
				'caballos',
				$t( '¿Buscas un caballo concreto?', 'Looking for a specific horse?' ),
				$t( 'Cuéntanos nivel, edad y presupuesto aproximado, y te avisamos cuando tengamos el caballo adecuado para ti.', 'Tell us the level, age and approximate budget you have in mind and we will let you know when we have the right horse for you.' )
			);
			return array(
				'content' => $c,
				'excerpt' => $t( 'Caballos de salto en venta seleccionados y preparados por Roi Calviño en A Coruña: fotos, vídeos, origen y valoración técnica de cada caballo.', 'Show jumping horses for sale, selected and produced by Roi Calviño in A Coruña, Spain: photos, videos, pedigree and technical assessment.' ),
			);

		/* ================================================================ ESCUELA */
		case 'escuela':
			[ $hid, $halt ] = $her( 'hero-escuela' );
			$c  = crs_hero(
				$hid,
				$halt,
				$t( 'Escuela', 'Riding school' ),
				$t( 'Aprende y disfruta del caballo', 'Learn to ride, enjoy horses' ),
				$t(
					'Aprender a montar, recorrer Cecebre a caballo o dar el salto a la competición. Tres maneras de vivir la equitación, siempre con nuestros caballos.',
					'Learn to ride, explore Cecebre on horseback or take the step into competition. Three ways to enjoy riding, always on our own horses.'
				),
				array(
					array( $t( 'Clases', 'Lessons' ), '#clases', 'light' ),
					array( $t( 'Rutas', 'Trail rides' ), '#rutas', 'light' ),
					array( 'Academy', '#academy', 'light' ),
				)
			);

			$c .= crs_section(
				crs_split(
					$im( 'escuela-clases' ),
					crs_p( $t( 'Escuela municipal de equitación', 'Municipal riding school' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Clases de escuela', 'Riding lessons' ) )
					. crs_p(
						$t(
							'Clases para niños y adultos con los caballos de la escuela: no necesitas tener caballo propio. Nuestra escuela tiene convenio oficial con el Ayuntamiento.',
							'Lessons for children and adults on the school’s horses, so you don’t need a horse of your own. Our riding school has an official partnership with the local council.'
						),
						'crs-lead'
					)
					. crs_list(
						array(
							$t( '<strong>Paso:</strong> primer contacto con el caballo, confianza y equilibrio.', '<strong>Walk:</strong> getting to know the horse, building confidence and balance.' ),
							$t( '<strong>Trote:</strong> posición, ritmo y control de la montura.', '<strong>Trot:</strong> position, rhythm and control.' ),
							$t( '<strong>Galope:</strong> seguridad y autonomía para seguir progresando.', '<strong>Canter:</strong> security and independence to keep progressing.' ),
						),
						'is-style-crs-check'
					)
					. crs_buttons( array( array( $t( 'Consultar clases', 'Ask about lessons' ), $ask( 'escuela' ) ) ) )
				),
				'',
				'clases'
			);

			$c .= crs_section(
				crs_split(
					$im( 'escuela-rutas' ),
					crs_p( $t( 'Rutas a caballo', 'Trail rides' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Rutas por el Encoro de Cecebre', 'Rides around the Cecebre reservoir' ) )
					. crs_p(
						$t(
							'Recorre a caballo los caminos que rodean el Encoro de Cecebre, un espacio natural de especial belleza. Rutas guiadas para disfrutar del paisaje, del caballo y de la tranquilidad del entorno.',
							'Ride the paths around the Cecebre reservoir, a natural area of outstanding beauty. Guided rides to enjoy the landscape, the horses and the peace and quiet of the countryside.'
						),
						'crs-lead'
					)
					. crs_list(
						array(
							$t( 'Rutas guiadas por nuestro equipo.', 'Rides guided by our team.' ),
							$t( 'Nosotros ponemos el caballo.', 'We provide the horse.' ),
							$t( 'Pregúntanos por fechas, duración y niveles.', 'Ask us about dates, duration and levels.' ),
						),
						'is-style-crs-check'
					)
					. crs_buttons( array( array( $t( 'Reservar una ruta', 'Book a ride' ), $ask( 'rutas' ) ) ) ),
					true
				),
				'soft',
				'rutas'
			);

			$c .= crs_section(
				crs_split(
					$im( 'academy' ),
					crs_p( $t( 'Tecnificación deportiva', 'Competitive development' ), 'is-style-crs-eyebrow' )
					. crs_h( 'CR Sports Academy' )
					. crs_p(
						$t(
							'Clases de tecnificación deportiva orientadas a la competición en salto de obstáculos, pensadas para niños y jóvenes jinetes que quieren dar el siguiente paso.',
							'Development coaching aimed at show jumping competition, designed for children and young riders who want to take the next step.'
						),
						'crs-lead'
					)
					. crs_list(
						array(
							$t( 'Orientada a la competición en salto de obstáculos.', 'Focused on show jumping competition.' ),
							$t( 'Para niños y jóvenes jinetes.', 'For children and young riders.' ),
							$t( '<strong>No es necesario traer caballo:</strong> lo aportamos nosotros.', '<strong>No need to bring a horse:</strong> we provide one.' ),
							$t( 'Con el método y la experiencia de Roi Calviño.', 'With Roi Calviño’s method and experience.' ),
						),
						'is-style-crs-check'
					)
					. crs_buttons( array( array( $t( 'Quiero entrar en la Academy', 'Join the Academy' ), $ask( 'academy' ), 'light' ) ) )
				),
				'dark',
				'academy'
			);

			$c .= $consult( 'escuela' );
			return array(
				'content' => $c,
				'excerpt' => $t( 'Escuela municipal de equitación, rutas a caballo por el Encoro de Cecebre y CR Sports Academy: tecnificación en salto para niños y jóvenes jinetes.', 'Municipal riding school, trail rides around the Cecebre reservoir and CR Sports Academy: show jumping development for children and young riders.' ),
			);

		/* ================================================================ ENTRENAMIENTO */
		case 'entrenamiento':
			[ $hid, $halt ] = $her( 'hero-entrenamiento' );
			$c  = crs_hero(
				$hid,
				$halt,
				$t( 'Entrenamiento', 'Training' ),
				$t( 'Entrenamiento de salto', 'Show jumping training' ),
				$t(
					'Tú pones el caballo y la ambición; nosotros, el método, el seguimiento diario y unas instalaciones preparadas para competir.',
					'You bring the horse and the ambition; we bring the method, daily follow-up and facilities built for competition.'
				),
				array( array( $t( 'Consultar', 'Enquire' ), $ask( 'entrenamiento' ) ) )
			);

			$c .= crs_section(
				crs_section_head( $t( 'Modalidades', 'Options' ), $t( 'Elige cómo quieres entrenar', 'Choose how you want to train' ) )
				. crs_columns(
					array(
						array(
							crs_p( $t( 'Con tu caballo', 'With your horse' ), 'crs-card__label' )
							. crs_h( $t( 'Entrenamiento personal', 'Personal training' ), 3 )
							. crs_p( $t( 'Traes tu caballo y nosotros programamos las sesiones. Puedes contar con un preparador cada día de entrenamiento y con uso garantizado de las zonas comunes.', 'You bring your horse and we plan the sessions. You can have a groom available on every training day and guaranteed use of the shared facilities.' ) )
							. crs_list(
								array(
									$t( 'Sesiones programadas según tus objetivos.', 'Sessions planned around your goals.' ),
									$t( 'Preparador disponible cada día de entrenamiento.', 'A groom available on every training day.' ),
									$t( 'Uso garantizado de las zonas comunes.', 'Guaranteed use of shared facilities.' ),
									$t( 'También por horas, sin compromiso de continuidad.', 'Also available by the hour, with no ongoing commitment.' ),
								),
								'is-style-crs-check'
							)
							. crs_buttons( array( array( $t( 'Consultar', 'Enquire' ), $ask( 'entrenamiento' ), 'outline' ) ) ),
							null,
							'crs-card crs-card--plan',
						),
						array(
							crs_p( $t( 'La opción más completa', 'Our complete package' ), 'crs-card__label' )
							. crs_h( $t( 'Pack pupilaje + entrenamiento', 'Livery + training package' ), 3 )
							. crs_p( $t( 'Todo listo para llegar y saltar, con el máximo cuidado por tu caballo: box propio, acceso a todos los servicios de la instalación y entrenamiento programado.', 'Everything ready for you to arrive and jump, with the utmost care for your horse: your own stable, access to every service at the facility and a structured training plan.' ) )
							. crs_list(
								array(
									$t( 'Box propio y pupilaje completo.', 'Your own stable and full livery.' ),
									$t( 'Acceso a todas las zonas comunes y servicios.', 'Access to all shared areas and services.' ),
									$t( 'Entrenamiento programado.', 'Structured training plan.' ),
									$t( 'Pensado para jinetes de competición.', 'Designed for competition riders.' ),
								),
								'is-style-crs-check'
							)
							. crs_buttons( array( array( $t( 'Consultar el pack', 'Ask about the package' ), $ask( 'pack' ) ) ) ),
							null,
							'crs-card crs-card--plan crs-card--featured',
						),
					),
					'crs-plans'
				)
			);

			$c .= crs_section(
				crs_split(
					$im( 'concursos' ),
					crs_p( $t( 'Concursos', 'Shows' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Te acompañamos a competir', 'We go to shows with you' ) )
					. crs_p(
						$t(
							'Organizamos la asistencia en concurso para nuestros alumnos, tanto si Roi compite en el mismo concurso como si no, dentro y fuera de A Coruña.',
							'We provide show support for our riders, whether or not Roi is competing at the same show, both in and outside A Coruña.'
						),
						'crs-lead'
					)
					. crs_list(
						array(
							$t( '<strong>Asistencia en concurso:</strong> preparación del caballo y apoyo al jinete durante la competición.', '<strong>Show support:</strong> horse preparation and rider support throughout the competition.' ),
							$t( '<strong>Transporte de caballos:</strong> te damos presupuesto, o puedes organizarlo por tu cuenta.', '<strong>Horse transport:</strong> we can quote for it, or you can arrange your own.' ),
							$t( '<strong>Gastos compartidos:</strong> desplazamiento, alojamiento y dietas se reparten entre los alumnos que asisten.', '<strong>Shared costs:</strong> travel, accommodation and allowances are split between the riders attending.' ),
						),
						'is-style-crs-check'
					)
					. crs_p( $t( 'Tarifas a consultar. Servicios sujetos a disponibilidad.', 'Rates on request. Services subject to availability.' ), 'crs-note' )
					. crs_buttons( array( array( $t( 'Consultar concursos', 'Ask about shows' ), $ask( 'concursos' ) ) ) ),
					true
				),
				'soft',
				'concursos'
			);

			$c .= crs_section(
				crs_split(
					$im( 'roi-salto-2' ),
					crs_p( $t( 'Con quién entrenas', 'Who you train with' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'La experiencia de Roi Calviño, en tu día a día', 'Roi Calviño’s experience, every day' ) )
					. crs_p( $t( 'Jinete con más de quince años en circuitos nacionales 4* y 5* e internacionales CSI, con Copas de Naciones y Grandes Premios en su palmarés.', 'A rider with more than fifteen years on 4* and 5* national circuits and at international CSI shows, with Nations Cups and Grands Prix on his record.' ) )
					. crs_buttons( array( array( $t( 'Conoce su trayectoria', 'See his career' ), $url( 'roi' ), 'outline' ) ) )
				)
			);

			$c .= $consult( 'entrenamiento' );
			return array(
				'content' => $c,
				'excerpt' => $t( 'Entrenamiento de salto con tu caballo en A Coruña: sesiones personales por horas, pack pupilaje + entrenamiento y asistencia en concursos con Roi Calviño.', 'Show jumping training with your own horse near A Coruña: personal sessions by the hour, livery + training package and show support with Roi Calviño.' ),
			);

		/* ================================================================ PUPILAJE */
		case 'pupilaje':
			[ $hid, $halt ] = $her( 'hero-pupilaje' );
			$c  = crs_hero(
				$hid,
				$halt,
				$t( 'Pupilaje', 'Livery' ),
				$t( 'Pupilaje de caballos', 'Horse livery' ),
				$t(
					'Tu caballo, cuidado cada día como uno de los nuestros: alimentación, cama, limpieza y acceso a unas instalaciones pensadas para el caballo de deporte.',
					'Your horse, cared for every day as one of our own: feeding, bedding, mucking out and access to facilities designed for the sport horse.'
				),
				array( array( $t( 'Consultar disponibilidad', 'Check availability' ), $ask( 'pupilaje' ) ) )
			);

			$c .= crs_section(
				crs_section_head( $t( 'Qué incluye', 'What’s included' ), $t( 'Todo lo que tu caballo necesita, cada día', 'Everything your horse needs, every day' ) )
				. crs_grid(
					crs_card( $t( 'Alimentación', 'Feeding' ), $t( 'Tres tomas de pienso y dos tomas de heno al día.', 'Three hard feeds and two hay feeds a day.' ), '01' )
					. crs_card( $t( 'Cama de viruta', 'Shavings bed' ), $t( 'Box de 3 × 3 m con cama de viruta.', '3 × 3 m stable bedded on shavings.' ), '02' )
					. crs_card( $t( 'Limpieza de cuadra', 'Mucking out' ), $t( 'Limpieza de la cuadra para que tu caballo descanse en las mejores condiciones.', 'Stable cleaning so your horse can rest in the best conditions.' ), '03' )
					. crs_card( $t( 'Zonas comunes', 'Shared facilities' ), $t( 'Acceso a pistas, caminador, preparadores y resto de zonas comunes.', 'Access to the arenas, horse walker, grooming bays and all shared areas.' ), '04' ),
					'crs-cards',
					'13rem',
					4
				)
			);

			$c .= crs_section(
				crs_split(
					$im( 'pupilaje' ),
					crs_p( $t( 'A tu medida', 'Tailored to you' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Servicios opcionales', 'Optional services' ) )
					. crs_p( $t( 'Completa el pupilaje con lo que tu caballo necesite en cada momento.', 'Add whatever your horse needs at any time.' ), 'crs-lead' )
					. crs_list(
						array(
							$t( '<strong>Caminador:</strong> 40 minutos al día.', '<strong>Horse walker:</strong> 40 minutes a day.' ),
							$t( '<strong>Paddock</strong> para que tu caballo salga a diario.', '<strong>Paddock</strong> turnout for your horse every day.' ),
							$t( '<strong>Alimentación extra</strong> adaptada en cantidad y tipo de comida.', '<strong>Extra feed</strong> tailored in quantity and type.' ),
							$t( '<strong>Entrenamiento:</strong> combínalo con el pack pupilaje + entrenamiento.', '<strong>Training:</strong> combine it with our livery + training package.' ),
						),
						'is-style-crs-check'
					)
					. crs_buttons( array( array( $t( 'Ver entrenamiento', 'See training' ), $url( 'entrenamiento' ), 'outline' ) ) ),
					true
				),
				'soft'
			);

			$c .= crs_section(
				crs_section_head(
					$t( 'Veterinario y herrador', 'Vet & farrier' ),
					$t( 'Los profesionales que tú elijas', 'The professionals of your choice' ),
					$t( 'Podemos proporcionarte veterinario y herrador de confianza o, si lo prefieres, puedes traer los tuyos. La instalación cuenta con preparadores con zona de herraje y veterinario.', 'We can arrange a trusted vet and farrier for you or, if you prefer, you can bring your own. The facility has grooming bays with a dedicated farrier and vet area.' ),
					'crs-center'
				),
				'dark'
			);

			$c .= $consult( 'pupilaje' );
			return array(
				'content' => $c,
				'excerpt' => $t( 'Pupilaje de caballos en A Coruña: tres tomas de pienso y dos de heno, cama de viruta, limpieza diaria y acceso a pista, caminador y paddocks en el Encoro de Cecebre.', 'Horse livery near A Coruña, Spain: three hard feeds and two hay feeds, shavings, daily mucking out and access to arena, walker and paddocks at Cecebre.' ),
			);

		/* ================================================================ INSTALACIONES */
		case 'instalaciones':
			[ $hid, $halt ] = $her( 'hero-instalaciones' );
			$c  = crs_hero(
				$hid,
				$halt,
				$t( 'Instalaciones', 'Facilities' ),
				$t( 'Un centro pensado para el caballo', 'A centre built around the horse' ),
				$t(
					'Diseñado para el bienestar del caballo y el trabajo del jinete, en pleno entorno natural del Encoro de Cecebre.',
					'Designed for the horse’s wellbeing and the rider’s work, in the natural setting of the Cecebre reservoir.'
				)
			);

			$c .= crs_section(
				crs_grid(
					crs_stat( '70 × 35 m', $t( 'Pista de arena de sílice con geotextil', 'Silica sand arena with geotextile base' ) )
					. crs_stat( '26', $t( 'Cuadras de 3 × 3 m', '3 × 3 m stables' ) )
					. crs_stat( '6', $t( 'Plazas de caminador', 'Horse walker places' ) )
					. crs_stat( '6', $t( 'Paddocks de 5 × 10 m', '5 × 10 m paddocks' ) )
					. crs_stat( '1.000 m²', $t( 'De paddocks de gran formato', 'Of large paddocks' ) )
					. crs_stat( '5', $t( 'Preparadores con solárium', 'Grooming bays with solarium' ) ),
					'crs-stats',
					'11rem',
					3
				)
			);

			$c .= crs_section(
				crs_split(
					$im( 'pista' ),
					crs_p( $t( 'Pistas y trabajo', 'Arenas & exercise' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Donde se entrena el caballo de salto', 'Where show jumpers are trained' ) )
					. crs_list(
						array(
							$t( 'Pista de 70 × 35 m de arena de sílice con geotextil.', '70 × 35 m silica sand arena with geotextile base.' ),
							$t( 'Pista circular cubierta.', 'Covered round pen.' ),
							$t( 'Caminador con capacidad para 6 caballos.', 'Horse walker for 6 horses.' ),
						),
						'is-style-crs-check'
					)
				)
			);

			$c .= crs_section(
				crs_split(
					$im( 'cuadras' ),
					crs_p( $t( 'Cuadras y descanso', 'Stables & turnout' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Bienestar dentro y fuera del box', 'Wellbeing in and out of the stable' ) )
					. crs_list(
						array(
							$t( '26 cuadras adaptadas de 3 × 3 m.', '26 purpose-built 3 × 3 m stables.' ),
							$t( '6 paddocks de 5 × 10 m.', '6 paddocks measuring 5 × 10 m.' ),
							$t( '1.000 m² de paddocks de gran formato.', '1,000 m² of large paddocks.' ),
						),
						'is-style-crs-check'
					),
					true
				),
				'soft'
			);

			$c .= crs_section(
				crs_split(
					$im( 'preparadores' ),
					crs_p( $t( 'Cuidados y servicios', 'Care & services' ), 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Todo preparado para el día a día', 'Ready for everyday work' ) )
					. crs_list(
						array(
							$t( '5 preparadores con solárium y zona de herraje y veterinario.', '5 grooming bays with solarium and a farrier and vet area.' ),
							$t( 'Zona de lavandería con lavadoras y secadoras.', 'Laundry room with washing machines and dryers.' ),
							$t( '3 guadarneses.', '3 tack rooms.' ),
							$t( 'Nave de 10 × 30 m dedicada al almacenamiento de forraje.', '10 × 30 m barn dedicated to forage storage.' ),
							$t( 'Nave de 5 × 10 m destinada a mantenimiento.', '5 × 10 m maintenance building.' ),
						),
						'is-style-crs-check'
					)
				)
			);

			$c .= crs_section(
				crs_section_head( $t( 'Próximamente', 'Coming soon' ), $t( 'Seguimos creciendo', 'We keep growing' ), $t( 'Proyectos en marcha para seguir mejorando la instalación.', 'Projects under way to keep improving the facility.' ) )
				. crs_grid(
					crs_card( $t( 'Picadero cubierto', 'Indoor arena' ), $t( 'Con arena de sílice, para entrenar con cualquier tiempo.', 'With silica sand, for training in all weathers.' ) )
					. crs_card( $t( 'Pista verde', 'Grass arena' ), $t( 'Pista de hierba para preparar concursos en este tipo de superficie.', 'A grass ring to prepare for shows on this surface.' ) )
					. crs_card( $t( 'Manga de galope', 'Gallop track' ), $t( 'Para el trabajo de fondo y la condición física del caballo.', 'For fitness and conditioning work.' ) )
					. crs_card( $t( 'Nave de camiones', 'Lorry & machinery barn' ), $t( 'Espacio cubierto para camiones y maquinaria.', 'Covered space for horseboxes and machinery.' ) ),
					'crs-cards crs-cards--dark',
					'13rem',
					4
				),
				'dark'
			);

			$c .= $consult( 'pupilaje', $t( 'Ven a conocernos', 'Come and visit us' ), $t( 'La mejor forma de conocer las instalaciones es visitarlas. Escríbenos y organizamos la visita.', 'The best way to see the facilities is in person. Get in touch and we will arrange a visit.' ) );
			return array(
				'content' => $c,
				'excerpt' => $t( 'Pista de 70 × 35 m de arena de sílice, 26 cuadras, caminador, paddocks, preparadores con solárium y zona de veterinario en el Encoro de Cecebre (A Coruña).', '70 × 35 m silica sand arena, 26 stables, horse walker, paddocks, grooming bays with solarium and vet area at the Cecebre reservoir, A Coruña.' ),
			);

		/* ================================================================ ROI */
		case 'roi':
			[ $hid, $halt ] = $her( 'hero-roi' );
			$fei = crs_opt( 'fei_url' );
			$c   = crs_hero(
				$hid,
				$halt,
				$t( 'Jinete internacional · España', 'International rider · Spain' ),
				'Roi Calviño Rabón',
				$t(
					'Más de quince años de competición ininterrumpida en circuitos nacionales 4* y 5* y en concursos internacionales CSI. Jinete de Copa de Naciones y fundador de CR Sport Horses.',
					'More than fifteen years of uninterrupted competition on Spain’s 4* and 5* national circuits and at international CSI shows. Nations Cup rider and founder of CR Sport Horses.'
				),
				$fei ? array( array( $t( 'Perfil oficial FEI', 'Official FEI profile' ), $fei, 'light' ) ) : array()
			);

			$c .= crs_section(
				crs_proof(
					array(
						array( $t( 'Copa de Naciones', 'Nations Cup' ), $t( '4º en la Final · 2026', '4th at the Final · 2026' ) ),
						array( 'CSIO 5* Gijón', $t( 'Gran Premio · Las Mestas', 'Grand Prix · Las Mestas' ) ),
						array( $t( 'Campeón de España', 'Spanish Champion' ), $t( 'Jóvenes Jinetes · 2019, con Enjoy', 'Young Riders · 2019, on Enjoy' ) ),
						array( $t( '7 Copas de Naciones', '7 Nations Cups' ), $t( 'Como joven jinete', 'As a young rider' ) ),
					)
				),
				'dark',
				'',
				'crs-section--proof'
			);

			$timeline = function ( string $stage, string $title, array $items ) {
				return crs_group(
					crs_p( $stage, 'crs-timeline__stage' ) . crs_h( $title, 3 ) . crs_list( $items ),
					array( 'class' => 'crs-timeline__item' )
				);
			};

			$c .= crs_section(
				crs_section_head( $t( 'Palmarés y trayectoria', 'Career & results' ), $t( 'Una vida en la pista', 'A life in the ring' ) )
				. crs_group(
					$timeline(
						$t( 'Los inicios', 'The beginning' ),
						$t( 'Ponis', 'Ponies' ),
						array(
							$t( 'Campeón y subcampeón de España en varias ocasiones.', 'Spanish champion and runner-up on several occasions.' ),
							$t( 'Varias veces campeón de Galicia.', 'Galician champion several times.' ),
						)
					)
					. $timeline(
						$t( 'Categorías de menores', 'Youth categories' ),
						$t( 'Joven jinete', 'Young rider' ),
						array(
							$t( '<strong>Campeón de España de Jóvenes Jinetes 2019</strong> con Enjoy, además de un 2º y un 4º puesto en el Campeonato de España.', '<strong>Spanish Young Riders Champion 2019</strong> on Enjoy, plus a 2nd and a 4th place at the Spanish Championships.' ),
							$t( '8º por equipos en el Campeonato de Europa.', '8th in the team competition at the European Championships.' ),
							$t( '7 Copas de Naciones.', '7 Nations Cups.' ),
							$t( 'Ha disputado y liderado el Campeonato de Galicia en múltiples ocasiones.', 'Has contested and led the Galician Championship many times.' ),
						)
					)
					. $timeline(
						$t( 'Categoría absoluta', 'Senior level' ),
						$t( 'Adulto', 'Senior' ),
						array(
							$t( '10º en su primer Campeonato de España absoluto.', '10th at his first senior Spanish Championship.' ),
							$t( 'Copa de Naciones y Gran Premio clasificatorio para los Juegos Olímpicos de París.', 'Nations Cup and a qualifying Grand Prix for the Paris Olympic Games.' ),
							$t( '<strong>Ganador del Gran Premio CSI1* de Casas Novas</strong> (A Coruña, 2023) con Enjoy CR, tras quedar 2º, 3º y 4º en otras ediciones.', '<strong>Winner of the CSI1* Casas Novas Grand Prix</strong> (A Coruña, 2023) on Enjoy CR, after 2nd, 3rd and 4th places in previous editions.' ),
							$t( 'Ganador del Gran Premio Cidade de Ferrol 2019 con Dollar do Diabolo.', 'Winner of the 2019 Cidade de Ferrol Grand Prix on Dollar do Diabolo.' ),
						)
					)
					. $timeline(
						'2026',
						$t( 'Temporada 2026', '2026 season' ),
						array(
							$t( '<strong>4º en la Final de la Copa de Naciones.</strong>', '<strong>4th at the Nations Cup Final.</strong>' ),
							$t( 'Gran Premio del CSIO 5* de Gijón (Las Mestas).', 'Grand Prix at the CSIO 5* Gijón (Las Mestas).' ),
						)
					),
					array( 'class' => 'crs-timeline' )
				),
				'soft'
			);

			$c .= crs_section(
				crs_section_head(
					$t( 'En pista', 'In the ring' ),
					$t( 'Caballos de competición', 'Competition horses' ),
					$t( 'Los caballos con los que Roi compite esta temporada en concursos nacionales e internacionales.', 'The horses Roi is competing with this season at national and international shows.' )
				)
				. crs_shortcode( '[crsport_caballos estado="competicion"]' )
			);

			$c .= crs_section(
				crs_split(
					$im( 'roi-retrato' ),
					crs_p( 'CR Sport Horses', 'is-style-crs-eyebrow' )
					. crs_h( $t( 'Toda esa experiencia, al servicio de tu caballo', 'All that experience, at the service of your horse' ) )
					. crs_p( $t( 'Roi dirige personalmente el centro: la selección y preparación de los caballos en venta, el entrenamiento de los jinetes y la tecnificación de los más jóvenes en la CR Sports Academy.', 'Roi personally runs the centre: selecting and producing the horses for sale, training riders and coaching young riders at the CR Sports Academy.' ), 'crs-lead' )
					. crs_buttons(
						array(
							array( $t( 'Entrenar con Roi', 'Train with Roi' ), $url( 'entrenamiento' ) ),
							array( $t( 'Caballos en venta', 'Horses for sale' ), $url( 'caballos' ), 'outline' ),
						)
					),
					true
				),
				'soft'
			);

			$c .= $consult( 'entrenamiento' );
			return array(
				'content' => $c,
				'excerpt' => $t( 'Roi Calviño Rabón, jinete internacional de salto: Campeón de España de Jóvenes Jinetes 2019, 4º en la Final de la Copa de Naciones 2026 y Gran Premio del CSIO 5* de Gijón.', 'Roi Calviño Rabón, international show jumper: 2019 Spanish Young Riders Champion, 4th at the 2026 Nations Cup Final and Grand Prix rider at CSIO 5* Gijón.' ),
			);

		/* ================================================================ CONTACTO */
		case 'contacto':
			$c  = crs_group(
				crs_p( $t( 'Contacto', 'Contact' ), 'is-style-crs-eyebrow' )
				. crs_h( $t( 'Hablemos', 'Let’s talk' ), 1 )
				. crs_p( $t( 'Cuéntanos qué necesitas y te respondemos lo antes posible. También puedes llamar o escribir por WhatsApp directamente a Roi.', 'Tell us what you need and we will get back to you as soon as possible. You can also call Roi or message him on WhatsApp.' ), 'crs-lead' ),
				array(
					'tag'   => 'section',
					'align' => 'full',
					'class' => 'crs-page-intro',
				)
			);
			$c .= crs_section(
				crs_columns(
					array(
						array( crs_h( $t( 'Datos de contacto', 'Contact details' ), 2, 'crs-h-small' ) . crs_shortcode( '[crsport_contacto]' ), '38%', 'crs-contact__info' ),
						array( crs_shortcode( '[crsport_formulario]' ), null, 'crs-contact__form' ),
					),
					'crs-contact'
				),
				'',
				'consulta'
			);
			$c .= crs_section( crs_shortcode( '[crsport_mapa]' ), 'soft' );
			return array(
				'content' => $c,
				'excerpt' => $t( 'Contacta con CR Sport Horses: teléfono y WhatsApp 673 22 55 60. Encoro de Cecebre, A Coruña.', 'Contact CR Sport Horses: phone & WhatsApp +34 673 22 55 60. Cecebre reservoir, A Coruña, Spain.' ),
			);

		/* ================================================================ LEGALES */
		case 'aviso_legal':
		case 'privacidad':
		case 'cookies':
			return array(
				'content' => crs_seed_legal( $key, $lang ),
				'excerpt' => '',
			);
	}

	return array(
		'content' => '',
		'excerpt' => '',
	);
}
