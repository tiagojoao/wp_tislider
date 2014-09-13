<?php
/*
   Plugin Name: TI Slider Plugin
   Description: Simple implementation of a slideshow into WordPress
   Author: Tiago Inácio
   Version: 1.0
 */

function np_init() {
    add_shortcode('np-shortcode', 'np_function');
    $args = array(
        'public' => true,
        'label' => 'TISlider Images',
        'supports' => array(
            'title',
            'thumbnail'
        )
    );
    register_post_type('np_images', $args);
}
add_action('init', 'np_init');

add_action('wp_print_scripts', 'np_register_scripts');
add_action('wp_print_styles', 'np_register_styles');


function np_register_scripts() {
    if (!is_admin()) {
        // register
        wp_register_script('np_nivo-script', plugins_url('nivo-slider/jquery.nivo.slider.js', __FILE__), array( 'jquery' ));
        wp_register_script('np_script', plugins_url('script.js', __FILE__));
 
        wp_enqueue_script('np_nivo-script');
        wp_enqueue_script('np_script');
    }
}
 
function np_register_styles() {
    // register
    wp_register_style('np_styles', plugins_url('nivo-slider/nivo-slider.css', __FILE__));
    wp_register_style('np_styles_theme', plugins_url('nivo-slider/themes/default/default.css', __FILE__));
 
    wp_enqueue_style('np_styles');
    wp_enqueue_style('np_styles_theme');
}

add_image_size('np_widget', 180, 100, true);
add_image_size('np_function', 600, 280, true);
add_theme_support( 'post-thumbnails' );

function np_function($type='np_function') {
    $args = array(
        'post_type' => 'np_images',
        'posts_per_page' => 5
    );
    $result = '<div class="slider-wrapper tislideshow theme-default">';
    $result .= '<div id="slider" class="nivoSlider">';
 
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();
 
        $the_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $type);
        $result .='<div class="aboveImages"><img title="'.get_the_title().'" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt=""/></div>';
    }
    $result .= '</div>';
    $result .='<div id = "htmlcaption" class = "nivo-html-caption">';
    $result .='<strong>This</strong> is an example of a <em>HTML</em> caption with <a href = "#">a link</a>.';
    $result .='</div>';
    $result .='</div>';
    return $result;
}

function np_widgets_init() {
    register_widget('np_Widget');
}
 
add_action('widgets_init', 'np_widgets_init');

class np_Widget extends WP_Widget {
 
    public function __construct() {
        parent::__construct('np_Widget', 'TI Slideshow', array('description' => __('A TI Slideshow Widget', 'text_domain')));
    }
    
    public function form($instance) {
    if (isset($instance['title'])) {
        $title = $instance['title'];
    }
    else {
        $title = __('Widget Slideshow', 'text_domain');
    }
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
    <?php
    }
    public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
 
    return $instance;
    }
    public function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    if (!empty($title))
        echo $before_title . $title . $after_title;
    echo np_function('np_widget');
    echo $after_widget;
}

}

?>
