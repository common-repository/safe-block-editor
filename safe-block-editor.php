<?php
/**
 * Plugin Name:        Safe Block Editor by WPMarmite
 * Description:        Simplifies your authoring experience by removing distracting options in the WordPress editor.
 * Version:            1.2
 * Author:             Modern Plugins
 * Author URI:         https://modernplugins.com/
 * Contributors:       modernplugins, vincentdubroeucq
 * License:            GPL v3 or later
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        safe-block-editor
 * Domain Path:        languages/
 * Requires at least:  5.8
 * Requires PHP :      7.0
 * Tested up to:       5.8
 */

/*
Safe Block Editor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.
 
Safe Block Editor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Safe Block Editor. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
*/

defined( 'ABSPATH' ) || die();

define( 'SAFE_BLOCK_EDITOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'SAFE_BLOCK_EDITOR_URL', plugin_dir_url( __FILE__ ) );


add_action( 'after_setup_theme', 'safe_block_editor_setup', 999 );
/**
 * Override theme settings
 */
function safe_block_editor_setup() {

    // Disable all font sizes settings. 
    add_theme_support( 'editor-font-sizes', array() );
    add_theme_support( 'disable-custom-font-sizes' );

    // Disable all color settings.
    add_theme_support( 'editor-color-palette', array() );
    add_theme_support( 'disable-custom-colors' );
    
    // Disable gradients.
    add_theme_support( 'editor-gradient-presets', array() );
    add_theme_support( 'disable-custom-gradients' );
    
    // Disable other functionnalities.
    remove_theme_support( 'align-wide' );
    remove_theme_support( 'custom-line-height' );
    remove_theme_support( 'core-block-patterns' ); 
    remove_theme_support( 'custom-spacing' );
    add_theme_support( 'custom-units', array() );
}


add_filter( 'block_editor_settings_all', 'safe_block_editor_block_editor_settings', 100, 2 );
/**
 * Overrides default editor settings
 * 
 * @param   array                    $settings  Settings of the editor.
 * @param   WP_Block_Editor_Context  $context   Block editor context
 * @return  array                    $settings 
 */
function safe_block_editor_block_editor_settings( $settings, $context ){
    $settings['alignWide']    = false;
    $settings['fontSizes']    = false;
    $settings['colors']       = false;
    $settings['gradients']    = false;
    $settings['imageEditing'] = false;
    $settings['disableCustomColors']    = true;
    $settings['disableCustomFontSizes'] = true;
    $settings['disableCustomGradients'] = true;
    $settings['enableCustomLineHeight'] = false;
    $settings['enableCustomSpacing']    = false;
    $settings['enableCustomUnits']      = false;
    $settings['__experimentalBlockPatterns']                       = array();
    $settings['__experimentalBlockPatternCategories']              = array();
    $settings['__experimentalFeatures']['typography']['dropCap']   = false;
    $settings['__experimentalFeatures']['spacing']['customMargin'] = false;
    $settings['__experimentalFeatures']['color']['duotone']        = false;
    $settings['__experimentalFeatures']['color']['customDuotone']  = false;
    $settings['__experimentalFeatures']['color']['link']           = false;
	return $settings;
}


/**
 * Returns rich text formats to de-register.
 * Passing false to a format will disable it.
 * 
 * @return  array  $formats
 */
function safe_block_editor_get_richtext_formats(){
    $formats = array(
        'core/image'       => false,
        'core/text-color'  => false,
        'core/superscript' => false,
        'core/subscript'   => false,
        'core/keyboard'    => false,
    );
    return apply_filters( 'safe_block_editor_richtext_formats', $formats );
}


/**
 * Returns blocks to remove
 * 
 * @return  array  $blocks
 */
function safe_block_editor_get_blocks_blacklist(){
    $widgets = array(
        'core/shortcode',
        'core/archives',
        'core/calendar',
        'core/categories',
        'core/html',
        'core/latest-comments',
        'core/latest-posts',
        'core/page-list',
        'core/rss',
        'core/social-links',
        'core/social-link',
        'core/tag-cloud',
        'core/search',
    );

    $blocks = array(
        'core/column',
        'core/columns',
        'core/cover',
        'core/media-text',
        'core/pullquote',
        'core/verse',
        'core/site-logo',
        'core/site-title',
        'core/site-tagline',
        'core/query',
        'core/query-title',
        'core/query-pagination',
        'core/query-pagination-next',
        'core/query-pagination-previous',
        'core/query-pagination-number',
        'core/post-title',
        'core/post-content',
        'core/post-date',
        'core/post-excerpt',
        'core/post-featured-image',
        'core/post-terms',
        'core/loginout',
    );

    $screen = get_current_screen();
    if( 'post' === $screen->base ){
        $blocks = array_merge( $blocks, $widgets );
    }

    return apply_filters( 'safe_block_editor_blocks_blacklist', $blocks );
}


/**
 * Returns block styles to de-register
 * Passing an empty array will disable all styles.
 * 
 * @return  array  $styles
 */
function safe_block_editor_get_styles_blacklist(){
    $styles = array(
        'core/image'     => array( 'default', 'rounded' ),
        'core/quote'     => array( 'default', 'large' ),
        'core/pullquote' => array( 'default', 'solid-color' ),
        'core/button'    => array( 'fill', 'outline' ),
        'core/table'     => array( 'regular', 'stripes' ),
    );
    return apply_filters( 'safe_block_editor_styles_blackList', $styles );
}


/**
 * Returns the supports overrides
 * 
 * @return  array  $supports
 */
function safe_block_editor_get_supports(){
    $supports = array(
        'anchor'          => false,
        'html'            => false,
        'reusable'        => false,
    );
    return apply_filters( 'safe_block_editor_supports', $supports );
}


/**
 * Returns an array of block editor settings to override or de-register
 * 
 * @return  array  $settings
 */
function safe_block_editor_get_settings(){
    $settings = [
        'supports'         => safe_block_editor_get_supports(),
        'formatsBlacklist' => safe_block_editor_get_richtext_formats(),
        'stylesBlackList'  => safe_block_editor_get_styles_blacklist(),
        'blocksBlacklist'  => safe_block_editor_get_blocks_blacklist(),
    ];
    return apply_filters( 'safe_block_editor_settings', $settings );
}


add_action( 'enqueue_block_editor_assets', 'safe_block_editor_block_editor_scripts', 100 );
/**
 * Registers block editor JS that de-registers blocks and styles. 
 */
function safe_block_editor_block_editor_scripts() {
    $version = get_plugin_data( __FILE__, false, false )['Version'];
    wp_enqueue_script( 'safe-block-editor-js', SAFE_BLOCK_EDITOR_URL . 'js/block-editor.js', array( 'wp-hooks', 'wp-dom-ready' ), $version, true );
    wp_enqueue_style( 'safe-block-editor', SAFE_BLOCK_EDITOR_URL . 'css/block-editor.css', array(), $version );
    wp_localize_script( 'safe-block-editor-js', 'safeEditorSettings', safe_block_editor_get_settings() );
}


add_action( 'admin_notices', 'safe_block_editor_admin_notice' );
/**
 * Displays a warning concerning potential block validation error
 */
function safe_block_editor_admin_notice(){
    $dismissed = get_user_meta( get_current_user_id(), 'safe_editor_notice_dismissed' , true );
    if( ! $dismissed ){
        $dismiss_url = add_query_arg( array(
            'action'   => 'safe_block_editor_dismiss_notice',
            '_wpnonce' => wp_create_nonce( 'safe_block_editor_dismiss_notice' ),
        ), admin_url('admin-post.php') );
    ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e( 'Thanks&nbsp;! You just activated Safe Block Editor&nbsp;! Some blocks, styles, and settings are no longer available in the WordPress editor.', 'safe-block-editor' ); ?></p>
            <p><?php _e( 'Nothing should be changed on the front end of the site, but you <strong>may</strong> get validation errors from the editor when updating posts. If that is the case, you should be able to safely recover your content using the <em>Resolve</em> or <em>Convert to HTML</em> recovery options.', 'safe-block-editor' ); ?></p>
            <p><a href="<?php echo esc_attr( esc_url( $dismiss_url ) ); ?>"><?php _e( 'Ok, I got it !', 'safe-block-editor' ); ?></a></p>
        </div>
    <?php
    }
}


add_action( 'admin_post_safe_block_editor_dismiss_notice', 'safe_block_editor_dismiss_notice' );
/**
 * Saves the user preference to not display the notice anymore.
 */
function safe_block_editor_dismiss_notice(){
    if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'safe_block_editor_dismiss_notice' ) ){
        return;
    }
    update_user_meta( get_current_user_id(), 'safe_editor_notice_dismissed', true );
    wp_safe_redirect( wp_get_referer() );
    exit;  
}
