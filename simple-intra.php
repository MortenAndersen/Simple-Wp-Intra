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
    register_post_type('simpleintra', array('labels' => array('name' => __('Intranet', 'simple-intra-domain'), 'singular_name' => __('Intranet', 'simple-intra-domain')), 'public' => true, 'exclude_from_search' => true, 'supports' => array('title', 'editor', 'thumbnail'), 'rewrite' => array('slug' => 'intranet'),));
}
add_action('init', 'hjemmesider_intra_create_posttype');

// Single.php
function get_custom_post_type_template($single_template) {
    global $post;
    if ($post->post_type == 'simpleintra') {
        $single_template = dirname(__FILE__) . '/single-simpleintra.php';
    }
    return $single_template;
}
add_filter('single_template', 'get_custom_post_type_template');

// Widget
function simpleintra_widgets_init() {
    register_sidebar(array('name' => __('INTRANET - Top', 'simpleintra_domain'), 'id' => 'intranettop', 'description' => 'Indhold vises kun hvis du er logget ind!', 'class' => '', 'before_widget' => '<section class="intra__section">', 'after_widget' => '</section>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
    register_sidebar(array('name' => __('INTRANET - Right', 'simpleintra_domain'), 'id' => 'intranetright', 'description' => 'Indhold vises kun hvis du er logget ind!', 'class' => '', 'before_widget' => '<section class="intra__section">', 'after_widget' => '</section>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
    register_sidebar(array('name' => __('INTRANET - Bottom', 'simpleintra_domain'), 'id' => 'intranetbottom', 'description' => 'Indhold vises kun hvis du er logget ind!', 'class' => '', 'before_widget' => '<section class="intra__section">', 'after_widget' => '</section>', 'before_title' => '<h4>', 'after_title' => '</h4>',));
}
add_action('widgets_init', 'simpleintra_widgets_init');
