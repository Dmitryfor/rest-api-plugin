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

add_action('template_redirect', function($template) {

	if ( is_singular( [ 'post', 'page' ] ) && ( get_query_var('json') ) ) {

        $post = get_queried_object();        
        $post_type = $post->post_type == 'post' ? 'posts' : 'pages';
		$request = new WP_REST_Request( 'GET', "/wp/v2/{$post_type}/$post->ID" );
        $response = rest_do_request( $request );
        $data = rest_get_server()->response_to_data( $response, false );

        wp_send_json($data);
        exit();
        
	}
	
});