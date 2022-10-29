<?php

/**
 * Fired during plugin activation
 *
 * @package   Burger Companion
 */

/**
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
class Burger_Companion_Activator {

	public static function activate() {

        $item_details_page = get_option('item_details_page'); 
		$theme = wp_get_theme(); // gets the current theme
		if(!$item_details_page){
			
			if ( 'Spintech' == $theme->name || 'ITpress' == $theme->name || 'Burgertech' == $theme->name || 'KitePress' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/spintech/default-widgets/default-widget.php';
			}
			
			if ( 'CoziPress' == $theme->name || 'Sipri' == $theme->name || 'Anexa' == $theme->name || 'CoziWeb' == $theme->name || 'CoziPlus' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/cozipress/default-widgets/default-widget.php';
			}
			
			if ( 'StoreBiz' == $theme->name || 'ShopMax' == $theme->name  || 'StoreWise' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/storebiz/default-widgets/default-widget.php';
			}
			
			
			if ( 'SeoKart' == $theme->name){
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/default-pages/upload-media.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/default-pages/home-page.php';
				require BURGER_COMPANION_PLUGIN_DIR . 'inc/seokart/default-widgets/default-widget.php';
			}
			
			update_option( 'item_details_page', 'Done' );
		}
	}

}