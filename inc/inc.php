<?php

require_once 'uon/index.php';

add_filter( 'use_block_editor_for_post_type', function( $use, $post_type ){

    if( 'page' === $post_type ) {
        $use = false; // отключаем
    }
    return $use;

}, 9999, 2 );

