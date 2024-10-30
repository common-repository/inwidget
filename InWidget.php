<?php
/*
Plugin Name: Insta Widget - We`re on Instagram
Plugin URI: https://ru.wordpress.org/plugins/inwidget/
Description: This plugin will quickly display the Instagram profile on the site. For display "We are on Instagram" using shortcode [instagram_widget user_name = "insta_user_name" /] or installation widget in WordPress `Appearance > Widgets`.
Version: 1.2
Author: Ruslan Heorhiiev, Alexandr Kazarmshchikov
Text Domain: inwidget
Domain Path: /assets/languages/

Copyright © 2017 Ruslan Heorhiiev
*/

if ( ! ABSPATH ) exit;

/**
 * Функция инициализации плагина
 * Function init plugin
**/
function inwidget_plugin_init() {
	// установка констант плагина & set define constants.
    define( 'INSTAGRAM_WIDGET_BASE', plugin_basename( __FILE__ ) );
    define( 'INSTAGRAM_WIDGET_URL', plugin_dir_url( __FILE__ ) );

	// загрузка language файла & load language file.
	load_plugin_textdomain( 'inwidget', false, dirname( INSTAGRAM_WIDGET_BASE ) . '/assets/languages/' );
}
add_action( 'plugins_loaded', 'inwidget_plugin_init' );


/**
 * Функция возвращает iframe с instagram данными
 * Function return iframe of instagram data
 * $user_name - Instagram user_name
 * $width - Width Iframe Wind
 * $inline - Count Images In line
 * $title - Title Widget
 * @version 1.1
**/
function get_iframe_inwidget( $attrs = array() ) {
    // проверка переменных & check vars.
    if ( !isset( $attrs['user_name'] ) OR empty( $attrs['user_name'] ) )
        return '';   

    if ( !isset( $attrs['width'] ) OR (int)$attrs['width'] === 0 )
        $attrs['width'] = 320;
        
    if ( !isset( $attrs['height'] ) OR (int)$attrs['height'] === 0 )
        $attrs['height'] = 320;        

    if ( !isset( $attrs['inline'] ) OR (int)$attrs['inline'] === 0 )
        $inline = 3;        

    if ( isset( $attrs['title'] ) AND !empty( $attrs['title'] ) )
        $attrs['title'] = esc_attr( $attrs['title'] );
    
    // возвращение instagram данных & return instagram data
    return '<iframe src="'.INSTAGRAM_WIDGET_URL.'inwidget/index.php?width='.($attrs['width']-4).'&inline='.$attrs['inline'].'&title='.$attrs['title'].'&login='.$attrs['user_name'].'" scrolling="no" frameborder="no" style="border:none;width:'.$attrs['width'].'px;height:'.$attrs['height'].'px;overflow:hidden;"></iframe>';    
}

/**
 * Функция шорткода instagram_widget
 * Function form shortcode instagram_widget
 * Example: [instagram_widget user_name="user_name" width="320" height="600" inline="3" title="Instagram" /]
 * @version 1.1
**/
function inwidget_plugin_shortcode( $attrs ){
    $attrs = shortcode_atts( array(
        'user_name' => '',        
        'width'     => 320,
        'height'    => 600,
        'inline'    => 3,
        'title'     => ''
    ), $attrs );

    return get_iframe_inwidget( $attrs );
}
add_shortcode( 'instagram_widget', 'inwidget_plugin_shortcode' );

/**
 * Класс WordPress виджета
 * Class WordPress widget
 * @version 1.1
**/
class inwidget_plugin_widget extends WP_Widget {

	function __construct() {
		parent::__construct ( 
            'instagram-widget', 
            __( 'Instagram widget', 'inwidget' ), 
            array( 
                'description' => __( 'Display Instagram widget.', 'inwidget' ), 
                'classname' => 'instagram-widget' 
            ) 
        );
	}

	function widget( $args, $instance ) {
        
        $title = !empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        
        // check user_name
        if ( empty( $instance['inwidget_user_name'] ) ) return;                                   
                		                
		$out = $args['before_widget'];
        
        if ( !empty( $title ) ) { $out .= $args['before_title'] . wp_kses_post( $title ) . $args['after_title']; };                
        
        // widget attrs
        $attrs = array(
            'user_name' => $instance['inwidget_user_name'],
            'title' => $instance['inwidget_title'],
            'width' => absint( $instance['inwidget_width'] ),
            'height' => absint( $instance['inwidget_height'] ),
            'inline' => absint( $instance['inwidget_inline'] )
        );
        // out widget             
        $out .= get_iframe_inwidget( $attrs );
        
        $out .= $args['after_widget'];

        return print $out;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'inwidget_title' => __( 'We are in Instagram', 'inwidget' ), 'inwidget_user_name' => 'instagram', 'inwidget_width' => 320, 'inwidget_height' => 600, 'inwidget_inline' => 3 ) );
		$title = $instance['title'];
        $inwidget_title = $instance['inwidget_title'];
        $inwidget_user_name = $instance['inwidget_user_name'];
        $inwidget_width = absint( $instance['inwidget_width'] );
        $inwidget_height = absint( $instance['inwidget_height'] );
        $inwidget_inline = absint( $instance['inwidget_inline'] );
        //$inwidget_count_photos = absint( $instance['inwidget_count_photos'] );
	?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'inwidget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'inwidget_title' ) ); ?>"><?php esc_html_e( 'Title Instagram widget', 'inwidget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'inwidget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'inwidget_title' ) ); ?>" type="text" value="<?php echo esc_attr( $inwidget_title ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'inwidget_user_name' ) ); ?>"><?php esc_html_e( 'Username Instagram profile', 'inwidget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'inwidget_login' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'inwidget_user_name' ) ); ?>" type="text" value="<?php echo esc_attr( $inwidget_user_name ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'inwidget_width' ) ); ?>"><?php esc_html_e( 'Width Instagram widget (px)', 'inwidget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'inwidget_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'inwidget_width' ) ); ?>" type="number" value="<?php echo $inwidget_width; ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'inwidget_height' ) ); ?>"><?php esc_html_e( 'Height Instagram widget (px)', 'inwidget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'inwidget_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'inwidget_height' ) ); ?>" type="number" value="<?php echo $inwidget_height; ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'inwidget_inline' ) ); ?>"><?php esc_html_e( 'Photos in the Instagram widget string', 'inwidget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'inwidget_inline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'inwidget_inline' ) ); ?>" type="number" value="<?php echo $inwidget_inline; ?>" /></label></p>               
	<?php
	}
    
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['inwidget_title'] = trim( strip_tags( $new_instance['inwidget_title'] ) );
        $instance['inwidget_user_name'] = trim( strip_tags( $new_instance['inwidget_user_name'] ) );
		$instance['inwidget_width'] = absint( $new_instance['inwidget_width'] ) ? $new_instance['inwidget_width'] : 320;
        $instance['inwidget_height'] = absint( $new_instance['inwidget_height'] ) ? $new_instance['inwidget_height'] : 600;
        $instance['inwidget_inline'] = absint( $new_instance['inwidget_inline'] ) ? $new_instance['inwidget_inline'] : 3;
        //$instance['inwidget_count_photos'] = absint( $new_instance['inwidget_count_photos'] ) ? $new_instance['inwidget_count_photos'] : 30;
        
		return $instance;
	}    
}

/**
 * Функция регистрации видежта плагина
 * Function registration widget plugin
**/
function inwidget_plugin_widgets_init() {
	register_widget( 'inwidget_plugin_widget' );
}
add_action( 'widgets_init', 'inwidget_plugin_widgets_init' );