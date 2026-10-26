<?php
/**
 * Plugin Name:       CR Sport Horses · Núcleo
 * Description:       Caballos en venta, formulario de consultas, datos de contacto, textos ES/EN y contenido inicial de crsporthorses.es. Funciona junto al tema «CR Sport Horses».
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.0
 * Author:            CR Sport Horses
 * License:           GPL-2.0-or-later
 * Text Domain:       crsport
 */

defined( 'ABSPATH' ) || exit;

define( 'CRS_VERSION', '1.0.0' );
define( 'CRS_FILE', __FILE__ );
define( 'CRS_DIR', plugin_dir_path( __FILE__ ) );
define( 'CRS_URL', plugin_dir_url( __FILE__ ) );

require_once CRS_DIR . 'inc/i18n.php';
require_once CRS_DIR . 'inc/settings.php';
require_once CRS_DIR . 'inc/horses.php';
require_once CRS_DIR . 'inc/form.php';
require_once CRS_DIR . 'inc/shortcodes.php';
require_once CRS_DIR . 'inc/seo.php';
require_once CRS_DIR . 'inc/redirects.php';
require_once CRS_DIR . 'inc/setup.php';

register_activation_hook(
	__FILE__,
	function () {
		crs_register_horse_types();
		flush_rewrite_rules();
	}
);

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
