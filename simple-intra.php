<?php

/*
Plugin Name: Simple Intra
Plugin URI: <a href="http://www.hjemmesider.dk"<br />
Description:</a> Simple Intra - Tilføjer intranet funktion til WordPress. Sider der kræver at du er logget ind for at se indhold.
Version: 1.0
Author: Hjemmesider.dk
Author URI: http://www.hjemmesider.dk.dk
*/

// Intranet Posttype
function hjemmesider_intra_create_posttype() {
    register_post_type('simpleintra', array('labels' => array('name' => __('Intranet', 'simple-intra-domain'), 'singular_name' => __('Intranet', 'simple-intra-domain')), 'public' => true, 'menu_icon' => 'dashicons-lock', 'exclude_from_search' => true, 'supports' => array('title', 'editor', 'thumbnail', 'comments'), 'rewrite' => array('slug' => 'intranet'),));
}
add_action('init', 'hjemmesider_intra_create_posttype');

// Single.php
function get_simple_intra_template($single_template) {
    global $post;
    if ($post->post_type == 'simpleintra') {
        $single_template = dirname(__FILE__) . '/single-simpleintra.php';
    }
    return $single_template;
}
add_filter('single_template', 'get_simple_intra_template');

// Widget
function simpleintra_widgets_init() {
    register_sidebar(array('name' => __('INTRANET - Top', 'simpleintra_domain'), 'id' => 'intranettop', 'description' => 'Indhold vises kun hvis du er logget ind!', 'class' => '', 'before_widget' => '<section class="intra__section">', 'after_widget' => '</section>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
    register_sidebar(array('name' => __('INTRANET - Right', 'simpleintra_domain'), 'id' => 'intranetright', 'description' => 'Indhold vises kun hvis du er logget ind!', 'class' => '', 'before_widget' => '<section class="intra__section">', 'after_widget' => '</section>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
    register_sidebar(array('name' => __('INTRANET - Bottom', 'simpleintra_domain'), 'id' => 'intranetbottom', 'description' => 'Indhold vises kun hvis du er logget ind!', 'class' => '', 'before_widget' => '<section class="intra__section">', 'after_widget' => '</section>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
}
add_action('widgets_init', 'simpleintra_widgets_init');

// Loop Shortcode

add_shortcode('intralist', 'hjemmesider_intralist');
function hjemmesider_intralist($atts) {
    global $post;
    ob_start();



    // define query parameters based on attributes
    $options = array('post_type' => 'simpleintra', 'order' => 'DESC' );
    $query = new WP_Query($options);

    // run the loop based on the query
if ($query->have_posts()) { ?>

<ul class="intranet-liste nul">
<?php while ($query->have_posts()): $query->the_post(); ?>
<li>
<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
<p class="dato"><b>Udgivet:</b> <?php the_date(); ?></p>
<p class="forfatter"><b>Forfatter:</b> <?php the_author() ?></p>
<p class="comment-count"><b>Kommentarer: </b> <?php comments_number('0', '1', '%'); ?></p>
</li>
<?php endwhile; wp_reset_postdata(); ?>
</ul>
    <?php
        $myvariable = ob_get_clean();
        return $myvariable;
    }
}





// Widget



/**
 * Adds Hjemmesider_intranet_widget widget.
 */
class Hjemmesider_intranet_widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct('Hjemmesider_intranet_widget',

        // Base ID
        __('Intranet', 'intranetdomain'),

        // Name
        array('description' => __('List Intranet sider', 'intranetdomain'),)

        // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        global $post;
        $query_args = array('post_type' => 'simpleintra', 'posts_per_page' => 5, 'post__not_in' => array($post->ID));

        // The Query
        $the_query = new WP_Query($query_args);

        // The Loop
        if ($the_query->have_posts()) {
            echo "\r\n" . '<ul class="intranet-liste">' . "\r\n";
            while ($the_query->have_posts()) {
                $the_query->the_post();
                echo '<li>'. "\r\n";
                echo '<strong><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></strong>' . "\r\n";
                echo '<p class="dato"><b>Udgivet: </b>' . get_the_date() . '</p>' . "\r\n";
                echo '<p class="forfatter"><b>Forfatter: </b>' . get_the_author() . '</p>' . "\r\n";
                echo '<p class="comment-count"><b>Kommentarer: </b>' . get_comments_number('0', '1', '%') . '</p>' . "\r\n";
                echo '</li>' . "\r\n";
            }
            echo '</ul>' . "\r\n";
        }
        else {

            echo "\r\n" . '<p><strong>' . __('No News found', 'intranetdomain') . '</strong></p>' . "\r\n";
        }

        /* Restore original Post Data */
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('De 5 seneste', 'intranetdomain');
?>
        <p>
        <label for="<?php
        echo $this->get_field_id('title'); ?>"><?php
        _e('Title:'); ?></label>
        <input class="widefat" id="<?php
        echo $this->get_field_id('title'); ?>" name="<?php
        echo $this->get_field_name('title'); ?>" type="text" value="<?php
        echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
}

// register Hjemmesider_news_Widget widget
function register_Hjemmesider_intranet_widget() {
    register_widget('Hjemmesider_intranet_widget');
}
add_action('widgets_init', 'register_Hjemmesider_intranet_widget');

