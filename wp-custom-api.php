<?php
/*
* Plugin Name:  REST API Plugin
* Plugin URI:   https://github.com/Dmitryfor/rest-api-plugin
* Description:  This is plugin!!!
* Version:      1.0
* Author:       FOR
* Author URI:   http://test.com/
*/

/**
 * GET /wp-json/for/v1/posts/{slug}/json
 */
add_action( 'rest_api_init', function() {

    register_rest_route( 'for/v1', '/posts/(?P<slug>[-\w]+)/json', [
            'methods'  => 'GET',
            'callback' => 'get_post_endpoint_cb',
        ]
    );

} );

/**
 * GET /wp-json/for/v1/{slug}/json
 */
add_action( 'rest_api_init', function() {

    register_rest_route( 'for/v1', '/(?P<slug>[-\w]+)/json', [
            'methods'  => 'GET',
            'callback' => 'get_page_endpoint_cb',
        ]
    );

} );

/**
 * Callback
 */
function get_post_endpoint_cb( $request ) {

    $slug = (string)$request['slug'];
    $response = [];

    $args = [
        'name'      => $slug,
        'post_type' => 'post',
        'status'    => 'publish',
    ];

    $post = array_shift( get_posts( $args ) );

    if ( $slug && !empty( $post ) ) {

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

    } else {

        $response['message'] = "No published post was found with such an slug.";

    }

    return $response;

}

/**
 * Callback
 */
function get_page_endpoint_cb( $request ) {

    $slug = (string)$request['slug'];
    $response = [];

    $args = [
        'name'      => $slug,
        'post_type' => 'page',
        'status'    => 'publish',
    ];

    $page = array_shift( get_posts( $args ) );

    if ( $slug && !empty( $page ) ) {

        $password_value = ( !empty( $page->post_password ) ) ? true : false;

        $response = [
            'id'            => $page->ID,
            'date'          => $page->post_date,  
            'date_gmt'      => $page->post_date_gmt,
            'guid'          => [
                'rendered'  => $page->guid
            ],
            'slug'          => $page->post_name, 
            'status'        => $page->post_status,
            'type'          => $page->post_type,   
            'title'         => [
                'rendered'  => $page->post_title
            ], 
            'content'       => [
                'rendered'  => strip_tags( $page->post_content ),
                'protected' => $password_value,
            ],
            'excerpt'       => [
                'rendered'  => strip_tags( $page->post_excerpt ),
                'protected' => $password_value,
            ],
            'author'        => $page->post_author,  
            #etc.
        ];

    } else {

        $response['message'] = "No published post was found with such an slug.";

    }

    return $response;

}