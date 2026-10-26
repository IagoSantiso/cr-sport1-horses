<?php
/**
 * Textos legales base (LSSI-CE, RGPD/LOPDGDD y cookies) en español e inglés.
 * Los datos del titular salen de CR Sport › Ajustes mediante [crsport_dato].
 * Conviene que un asesor revise el texto final antes de publicar.
 */

defined( 'ABSPATH' ) || exit;

function crs_seed_legal( string $key, string $lang ): string {
	$en = 'en' === $lang;
	$d  = fn( string $campo ) => '[crsport_dato campo="' . $campo . '"]';
	$h  = fn( string $text ) => crs_h( $text, 2 );
	$p  = fn( string $text ) => crs_p( $text );
	$c  = '';

	if ( 'aviso_legal' === $key ) {
		$c .= $p(
			$en
				? 'In compliance with Article 10 of Spanish Law 34/2002 on Information Society Services and Electronic Commerce (LSSI-CE), the owner of this website is identified below.'
				: 'En cumplimiento del artículo 10 de la Ley 34/2002, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), se informa de los datos del titular de este sitio web.'
		);
		$c .= crs_list(
			array(
				( $en ? '<strong>Owner:</strong> ' : '<strong>Titular:</strong> ' ) . $d( 'razon_social' ),
				'<strong>NIF:</strong> ' . $d( 'nif' ),
				( $en ? '<strong>Address:</strong> ' : '<strong>Domicilio:</strong> ' ) . $d( 'domicilio' ),
				( $en ? '<strong>Phone:</strong> ' : '<strong>Teléfono:</strong> ' ) . $d( 'telefono' ),
				'<strong>Email:</strong> ' . $d( 'email_legal' ),
				( $en ? '<strong>Registration details:</strong> ' : '<strong>Datos registrales:</strong> ' ) . $d( 'registro' ),
			)
		);
		$c .= $h( $en ? 'Purpose' : 'Objeto' );
		$c .= $p( $en ? 'This website provides information about the services of CR Sport Horses (riding school, trail rides, training, livery, show support and horse sales) and allows users to send enquiries.' : 'Este sitio web ofrece información sobre los servicios de CR Sport Horses (escuela de equitación, rutas a caballo, entrenamiento, pupilaje, asistencia a concursos y venta de caballos) y permite enviar consultas.' );
		$c .= $h( $en ? 'Conditions of use' : 'Condiciones de uso' );
		$c .= $p( $en ? 'Accessing this website makes you a user and implies acceptance of these conditions. Users agree to make appropriate use of the content and not to use it for unlawful purposes or in ways that could damage the website or third parties.' : 'El acceso a este sitio web atribuye la condición de usuario e implica la aceptación de estas condiciones. El usuario se compromete a hacer un uso adecuado de los contenidos y a no emplearlos para actividades ilícitas o que puedan dañar el sitio web o a terceros.' );
		$c .= $p( $en ? 'Information about horses for sale is provided for guidance only and does not constitute a binding offer. Characteristics, availability and conditions must be confirmed directly with CR Sport Horses.' : 'La información sobre los caballos en venta tiene carácter orientativo y no constituye una oferta vinculante. Sus características, disponibilidad y condiciones deben confirmarse directamente con CR Sport Horses.' );
		$c .= $h( $en ? 'Intellectual property' : 'Propiedad intelectual e industrial' );
		$c .= $p( $en ? 'All content on this website (text, photographs, videos, logos and design) belongs to the owner or is used with permission. Reproduction, distribution or modification without express authorisation is prohibited.' : 'Todos los contenidos de este sitio web (textos, fotografías, vídeos, logotipos y diseño) son propiedad del titular o se utilizan con autorización. Queda prohibida su reproducción, distribución o modificación sin autorización expresa.' );
		$c .= $h( $en ? 'Liability and links' : 'Responsabilidad y enlaces' );
		$c .= $p( $en ? 'The owner is not responsible for any errors in the content or for the content of third-party websites linked from this site, such as HorseTelex, Instagram or Google Maps.' : 'El titular no se hace responsable de posibles errores en los contenidos ni del contenido de sitios web de terceros enlazados desde esta web, como HorseTelex, Instagram o Google Maps.' );
		$c .= $h( $en ? 'Applicable law' : 'Legislación aplicable' );
		$c .= $p( $en ? 'These conditions are governed by Spanish law. Any dispute shall be submitted to the courts that are competent under applicable law.' : 'Estas condiciones se rigen por la legislación española. Cualquier controversia se someterá a los juzgados y tribunales que resulten competentes conforme a la normativa aplicable.' );
		return $c;
	}

	if ( 'privacidad' === $key ) {
		$c .= $h( $en ? 'Data controller' : 'Responsable del tratamiento' );
		$c .= crs_list(
			array(
				( $en ? '<strong>Controller:</strong> ' : '<strong>Responsable:</strong> ' ) . $d( 'razon_social' ) . ' (CR Sport Horses)',
				'<strong>NIF:</strong> ' . $d( 'nif' ),
				( $en ? '<strong>Address:</strong> ' : '<strong>Domicilio:</strong> ' ) . $d( 'domicilio' ),
				'<strong>Email:</strong> ' . $d( 'email_legal' ),
			)
		);
		$c .= $h( $en ? 'What data we collect and why' : 'Qué datos tratamos y para qué' );
		$c .= $p( $en ? 'When you use the enquiry form we process your name, phone number, email address and the information you include in your message, solely in order to respond to your enquiry and, where applicable, to provide the service you request (lessons, trail rides, training, livery, show support or the purchase of a horse).' : 'Cuando utilizas el formulario de consulta tratamos tu nombre, teléfono, email y la información que incluyas en el mensaje, con la única finalidad de responder a tu consulta y, en su caso, prestarte el servicio que solicitas (clases, rutas, entrenamiento, pupilaje, asistencia a concursos o compra de un caballo).' );
		$c .= $h( $en ? 'Legal basis' : 'Legitimación' );
		$c .= $p( $en ? 'Your consent, given by ticking the box on the form, and, where applicable, the performance of pre-contractual or contractual measures at your request (Art. 6.1.a and 6.1.b GDPR).' : 'Tu consentimiento, otorgado al marcar la casilla del formulario, y, en su caso, la aplicación de medidas precontractuales o contractuales a petición tuya (art. 6.1.a y 6.1.b del RGPD).' );
		$c .= $h( $en ? 'How long we keep your data' : 'Plazo de conservación' );
		$c .= $p( $en ? 'Enquiries are kept for as long as needed to deal with them and for a maximum of two years, unless a business relationship follows, in which case data is kept for the legally required periods.' : 'Las consultas se conservan el tiempo necesario para atenderlas y como máximo dos años, salvo que se inicie una relación comercial, en cuyo caso los datos se conservarán durante los plazos legalmente exigidos.' );
		$c .= $h( $en ? 'Recipients' : 'Destinatarios' );
		$c .= $p( $en ? 'Your data will not be passed on to third parties except where legally required. The website hosting and email providers act as data processors under the corresponding agreements.' : 'No se cederán datos a terceros salvo obligación legal. Los proveedores de alojamiento web y correo electrónico actúan como encargados del tratamiento con los correspondientes contratos.' );
		$c .= $h( $en ? 'Your rights' : 'Tus derechos' );
		$c .= $p( $en ? 'You may exercise your rights of access, rectification, erasure, objection, restriction of processing and data portability, and withdraw your consent at any time, by writing to ' . $d( 'email_legal' ) . '. If you believe your rights have not been respected, you may lodge a complaint with the Spanish Data Protection Agency (www.aepd.es).' : 'Puedes ejercer tus derechos de acceso, rectificación, supresión, oposición, limitación del tratamiento y portabilidad, así como retirar tu consentimiento en cualquier momento, escribiendo a ' . $d( 'email_legal' ) . '. Si consideras que no se han respetado tus derechos, puedes presentar una reclamación ante la Agencia Española de Protección de Datos (www.aepd.es).' );
		return $c;
	}

	// Cookies.
	$c .= $p( $en ? 'This website uses only the cookies strictly necessary for it to work. Third-party content (Google Maps, YouTube, Vimeo or Instagram) is only loaded if you choose to view it, and from that moment those services may set their own cookies.' : 'Esta web utiliza únicamente las cookies estrictamente necesarias para su funcionamiento. El contenido de terceros (Google Maps, YouTube, Vimeo o Instagram) solo se carga si decides verlo y, a partir de ese momento, esos servicios pueden instalar sus propias cookies.' );
	$c .= $h( $en ? 'Cookies we use' : 'Cookies que utilizamos' );
	$c .= crs_list(
		array(
			$en ? '<strong>pll_language</strong> (own, technical, 1 year): remembers the language you are browsing in.' : '<strong>pll_language</strong> (propia, técnica, 1 año): recuerda el idioma en el que navegas.',
			$en ? '<strong>wordpress_*, wp-settings-*</strong> (own, technical, session/1 year): only for users who log in to manage the website.' : '<strong>wordpress_*, wp-settings-*</strong> (propias, técnicas, sesión/1 año): solo para usuarios que acceden a la administración de la web.',
		)
	);
	$c .= $h( $en ? 'Third-party content' : 'Contenido de terceros' );
	$c .= crs_list(
		array(
			$en ? '<strong>Google Maps</strong>: when you click “Load map”. Policy: policies.google.com/privacy' : '<strong>Google Maps</strong>: al pulsar «Cargar mapa». Política: policies.google.com/privacy',
			$en ? '<strong>YouTube / Vimeo</strong>: when you play an external video on a horse profile.' : '<strong>YouTube / Vimeo</strong>: al reproducir un vídeo externo en la ficha de un caballo.',
			$en ? '<strong>Instagram (Meta)</strong>: when viewing embedded posts or the Instagram feed. Policy: privacycenter.instagram.com' : '<strong>Instagram (Meta)</strong>: al ver publicaciones o el feed de Instagram. Política: privacycenter.instagram.com',
		)
	);
	$c .= $p( $en ? 'If analytics cookies are added in the future, your prior consent will be requested through a cookie banner, and this policy will be updated.' : 'Si en el futuro se añaden cookies de analítica, se solicitará tu consentimiento previo mediante un aviso de cookies y se actualizará esta política.' );
	$c .= $h( $en ? 'How to manage cookies' : 'Cómo gestionar las cookies' );
	$c .= $p( $en ? 'You can allow, block or delete cookies at any time from your browser settings.' : 'Puedes permitir, bloquear o eliminar las cookies en cualquier momento desde la configuración de tu navegador.' );
	return $c;
}
