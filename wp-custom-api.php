<?php
/*
* Plugin Name:  REST API Plugin
* Plugin URI:   https://github.com/Dmitryfor/rest-api-plugin
* Description:  This is plugin!!!
* Version:      1.0
* Author:       FOR
* Author URI:   http://test.com/
*/

add_action('init', function() {

	add_rewrite_endpoint('json', EP_PERMALINK | EP_PAGES);
    
});

add_filter('request', function($vars) {

	if ( isset($vars['json'] ) ) {
		$vars['json'] = true;
	}

	return $vars;

});

add_filter('template_redirect', function($template) {

	if ( ( is_singular() || is_page() ) && ( get_query_var('json') ) ) {

        $post = get_queried_object();
        $password_value = ( !empty( $post->post_password ) ) ? true : false;
        
		$response = [
            'id'            => $post->ID,
            'date'          => $post->post_date,  
            'date_gmt'      => $post->post_date_gmt,
            'guid'          => [
                'rendered'  => $post->guid
            ],
            'slug'          => $post->post_name, 
            'status'        => $post->post_status,
            'type'          => $post->post_type,   
            'title'         => [
                'rendered'  => $post->post_title
            ], 
            'content'       => [
                'rendered'  => strip_tags( $post->post_content ),
                'protected' => $password_value,
            ],
            'excerpt'       => [
                'rendered'  => strip_tags( $post->post_excerpt ),
                'protected' => $password_value,
            ],
            'author'        => $post->post_author,  
            #etc.
        ];

        header('Content-Type: text/plain');
        echo json_encode( $response );

        exit();
        
	}
	
});