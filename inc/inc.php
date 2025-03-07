<?php

require_once 'uon/index.php';

add_filter( 'use_block_editor_for_post_type', function( $use, $post_type ){

    if( 'page' === $post_type ) {
        $use = false; // отключаем
    }
    return $use;

}, 9999, 2 );

if (!is_admin()) {
    echo '<pre>';
//$order = new UonOrder(14252, true);
//print_r(Uon::uonUserOrders(2));
//$user = new UonUser(2);
//$tourist = new UonTourist(false, ['uon_id' => 11862, 'user_id' => 2]);
//print_r($tourist);
//print_r($user->loadOrders(true));
//print_r(new UonProgram(false, ['uon_id' => 144]));
    //new UonProgram(false, ['uon_id' => 144]);
    /*$url = UOn::$url . 'request/search.json';
    $data = array(
        'client_ids' => 11861
    );
    $result = wp_remote_post( $url, ['body' => $data]);
    */

    /*$url = UOn::$url . 'catalog-package/156.json';
    $result = wp_remote_post( $url, ['method' => 'GET']);

    $data = json_decode($result['body'], false);
    print_r($data);
    //print_r(Uon::uonUserByEmail('pirogova-sgmu@yandex.ru'));
    /*$url = UOn::$url . 'request/14252.json';
    $result = wp_remote_post( $url, ['method' => 'GET']);
    $data = json_decode($result['body'], false);
    print_r($data);*/


    echo '</pre>';
}