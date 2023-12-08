<?php
/*
Plugin Name: 3rd Party Website Post Slider
Description: A Gutenberg block for displaying a slider of posts from a 3rd party REST API.
Version: 1.0
Author: Kishan Sarodiya
*/

// Enqueue block script and style
function enqueue_post_slider_block_assets() {
    wp_enqueue_script(
        'post-slider-block',
        plugin_dir_url(__FILE__) . 'js/post-slider-block.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-api-fetch'),
        true
    );
    wp_enqueue_script(
      'post-slider-block',
      plugin_dir_url(__FILE__) . 'js/custom-slider.js',
      array(),
      true
  	);
    wp_enqueue_style(
        'post-slider-block-style-admin',
        plugin_dir_url(__FILE__) . 'css/post-slider-block-admin.css',
        array('wp-edit-blocks'),
        true
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_post_slider_block_assets');

// Enqueue block script and style
function enqueue_post_slider_block_assets_front() {
  	wp_enqueue_script(
		'post-slider-block',
		plugin_dir_url(__FILE__) . 'js/custom-slider.js',
		array(),
		true
	);
	wp_enqueue_style(
		'post-slider-block-style',
		plugin_dir_url(__FILE__) . 'css/post-slider-block.css',
		array('wp-edit-blocks'),
		true
	);
}
add_action('wp_footer', 'enqueue_post_slider_block_assets_front');

// Register block
function register_post_slider_block() {
    register_block_type('post-slider-block/post-slider', array(
        'editor_script' => 'post-slider-block',
        'editor_style'  => 'post-slider-block-style',
        'render_callback' => 'render_post_slider_block',
        'attributes' => array(
          'apiEndpoint' => array(
              'type' => 'text',
              'default' => 5001,
          ),
          'sliderSpeed' => array(
            'type' => 'number',
            'default' => 5000,
          ),
          'showArrows' => array(
              'type' => 'boolean',
              'default' => true,
          ),
		  'showMeta' => array(
              'type' => 'boolean',
              'default' => true,
          ),
          'autoSlide' => array(
              'type' => 'boolean',
              'default' => true,
          ),
          'displayPosts' => array(
              'type' => 'number',
              'default' => 5,
          ),
      ),
    ));
}
add_action('init', 'register_post_slider_block');

// Render callback for the block
function render_post_slider_block($attributes) {
    // Fetch and display posts from 3rd party REST API here
    $api_url_data = esc_attr($attributes['apiEndpoint']).'/wp-json/wp/v2/posts?_embed';
    $posts = fetch_posts_from_third_party_api($api_url_data);
    $post_data = '';
    
    $post_data.= '<div class="slider-container" id="slider-container"><div class="post-slider slides" id="slides" data-slider-speed="'.esc_attr($attributes['sliderSpeed']).'" data-slider-auto="'.esc_attr($attributes['autoSlide']).'">';
		$i = 0;
        foreach ($posts as $post) :
		if($i==$attributes['displayPosts']){
			break;
		}
		  $post_date_display = strtotime($post->date);
          $post_data.= '<div class="post-slide slide">';
		  $post_data.= '<div class="inner-slide">';
		  $post_data.= '<div class="slide-content">';
		  $post_data.='<h2><a href="'.esc_html($post->link).'" target="_blank">'.esc_html($post->title->rendered).'</a></h2>';
		  if(!empty($attributes['showMeta']) && $attributes['showMeta'] == true){
		  $post_data.='<div class="post-meta"><ul><li>'.__('By ','post-slider-block').$post->_embedded->author[0]->name.'</li><li>'.__('Publish By ','post-slider-block').date("m/d/Y",$post_date_display).'</li></ul></div>';
		  }
		  $post_data.='<div class="post-excerpt">'.$post->excerpt->rendered.'</div>';
		  $post_data.= '</div>';
		  $post_data.= '<div class="slide-img">';
		  $post_data.='<a href="'.esc_html($post->link).'" target="_blank"><img src="'.esc_html($post->episode_featured_image).'"/><a>';
		  $post_data.= '</div>';
	  	  $post_data.='</div>';
	      $post_data.='</div>';
		  $i++;
	endforeach;
	   if(!empty($attributes['showArrows']) && $attributes['showArrows'] == true){
			$post_data.= '</div><div class="arrow left" onclick="prevSlide()">&#9665;</div><div class="arrow right" onclick="nextSlide()">&#9655;</div></div>';
		  }
      return $post_data;
}

// Fetch posts from 3rd party REST API
function fetch_posts_from_third_party_api($api_url_data) {
    $api_url = $api_url_data; // Replace with the actual API endpoint

    $response = wp_safe_remote_get($api_url);

    if (is_wp_error($response)) {
        return array(); // Return an empty array on error
    }

    $body = wp_remote_retrieve_body($response);
    $posts = json_decode($body);

    return is_array($posts) ? $posts : array();
}
