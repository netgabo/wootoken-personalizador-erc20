<?php
/*
Plugin Name:  WooToken Personalizador de moneda ERC20
Plugin URI:   https://www.solucionesenblockchain.copm/wootoken-personalizador-de-moneda/
Description:  Este complemento agregará una nueva moneda personalizada para ayudarlo a recibir en su sitio web WooCommerce tokens personalizados compatibles con la máquina virtual de ethereum (ERC20).
Version:      0.0.1
Author:       Gabriel Estrada / Soluciones en Blockchain
Author URI:   https://www.solucionesenblockchain.com/
License:      GPL3
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:  woo-token-personalizador
Domain Path:  /languages
 */

if (!defined('ABSPATH')) {
	exit;
}
function woo_token_personalizador_load_plugin_textdomain() {

	$path = basename(dirname(__FILE__)) . '/languages';
	$result = load_plugin_textdomain('woo-token-personalizador', FALSE, $path);
	if ($result) {
		return;
	}

	// $locale = apply_filters('theme_locale', get_locale(), 'woo-token-personalizador');
	// die("Could not find $path/$locale.mo.");
}
add_action('plugins_loaded', 'woo_token_personalizador_load_plugin_textdomain');
/**
 * custom currency
 */
add_filter('woocommerce_currencies', 'woo_token_personalizador_add_my_currency');

function woo_token_personalizador_add_my_currency($currencies) {
	$options = get_option('woo_token_personalizador_settings');

	$currencies['ERC20'] = $options['currency_name'];
	return $currencies;
}

/**
 * custom currency symbol
 */
add_filter('woocommerce_currency_symbol', 'woo_token_personalizador_add_my_currency_symbol', 10, 2);

function woo_token_personalizador_add_my_currency_symbol($currency_symbol, $currency) {
	$options = get_option('woo_token_personalizador_settings');
	switch ($currency) {
	case 'ERC20':$currency_symbol = $options['currency_symbol'];
		break;
	}
	return $currency_symbol;
}
add_action('admin_menu', 'woo_token_personalizador_add_admin_menu');
add_action('admin_init', 'woo_token_personalizador_settings_init');

function woo_token_personalizador_add_admin_menu() {

	add_options_page(__('WooToken Personalizador de moneda', 'woo-token-personalizador'), __('WooToken Personalizador de moneda', 'woo-token-personalizador'), 'manage_options', 'woocommerce_customize_erc20_currency', 'woo_token_personalizador_options_page');

}

function woo_token_personalizador_settings_init() {

	register_setting('woo_token_personalizador_plugin_page', 'woo_token_personalizador_settings');

	add_settings_section(
		'woo_token_personalizador_woo_token_personalizador_plugin_page_section',
		__('Configuración de moneda', 'woo-token-personalizador'),
		'woo_token_personalizador_settings_section_callback',
		'woo_token_personalizador_plugin_page'
	);

	add_settings_field(
		'currency_name',
		__('Nombre de la moneda', 'woo-token-personalizador'),
		'woo_token_personalizador_currency_name_render',
		'woo_token_personalizador_plugin_page',
		'woo_token_personalizador_woo_token_personalizador_plugin_page_section'
	);

	add_settings_field(
		'currency_symbol',
		__('Símbolo de moneda', 'woo-token-personalizador'),
		'woo_token_personalizador_currency_symbol_render',
		'woo_token_personalizador_plugin_page',
		'woo_token_personalizador_woo_token_personalizador_plugin_page_section'
	);

}

function woo_token_personalizador_currency_name_render() {

	$options = get_option('woo_token_personalizador_settings');
	?>
    <input type='text' name='woo_token_personalizador_settings[currency_name]' value='<?php echo $options['currency_name']; ?>'>
    <?php

}

function woo_token_personalizador_currency_symbol_render() {

	$options = get_option('woo_token_personalizador_settings');
	?>
    <input type='text' name='woo_token_personalizador_settings[currency_symbol]' value='<?php echo $options['currency_symbol']; ?>'>
    <?php

}

function woo_token_personalizador_settings_section_callback() {

	echo __('Configura tu moneda a continuación.', 'woo-token-personalizador');

}

function woo_token_personalizador_options_page() {

	?>
    <form action='options.php' method='post'>

        <h1><?php _e('WooToken Personalizador de moneda', 'woo-token-personalizador');?></h1>

        <?php
settings_fields('woo_token_personalizador_plugin_page');
	do_settings_sections('woo_token_personalizador_plugin_page');
	submit_button();
	?>

    </form>
    <?php

}
