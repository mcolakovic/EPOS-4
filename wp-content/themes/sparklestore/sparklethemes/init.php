<?php
/**
 * Main Custom admin functions area
 *
 * @since Sparkle Themes
 *
 * @param SparkleStore
 *
*/

/**
 * Load Custom Admin functions that act independently of the theme functions.
*/
require get_template_directory().'/sparklethemes/functions.php';

/**
 * Implement the Custom Header feature.
*/
require get_template_directory().'/sparklethemes/core/custom-header.php';

/**
 * Custom template tags for this theme.
*/
require get_template_directory().'/sparklethemes/core/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
*/
require get_template_directory().'/sparklethemes/core/extras.php';

/**
 * Customizer additions.
*/
require get_template_directory().'/sparklethemes/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
*/
require get_template_directory().'/sparklethemes/core/jetpack.php';

/**
 * Load widget compatibility field file.
*/
require get_template_directory().'/sparklethemes/sparkle-widgets/widgets-fields.php';

/**
 * Load header hooks file.
*/
require get_template_directory().'/sparklethemes/hooks/header.php';

/**
 * Load footer hooks file.
*/
require get_template_directory() .'/sparklethemes/hooks/footer.php';

/**
 * Load woocommerce hooks file.
*/
require get_template_directory() .'/sparklethemes/hooks/woocommerce.php';

/**
 * Load load breadcrumbs file.
*/
require get_template_directory() .'/sparklethemes/breadcrumbs.php';

/**
 * Welcome Page.
 */
require get_template_directory() . '/welcome/welcome.php';

/**
 * Dunamic CSS Color Options file.
*/
require get_template_directory() .'/sparklethemes/dynamic-css.php';

/**
 *  Custom Elementor Block Widgets 
*/
require get_template_directory() .'/sparklethemes/elementor/elementor-function.php';

/**
 * Load in customizer upgrade to pro
*/
require get_template_directory() .'/sparklethemes/customizer/customizer-pro/class-customize.php';

/** 
 * Mobile Menu 
*/
require get_template_directory() . '/sparklethemes/mobile-menu/init.php';

/**
 * starter content
 */
require get_template_directory() .'/sparklethemes/starter-content/init.php';