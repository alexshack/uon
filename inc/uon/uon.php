<?php

class Uon {

    static $key = '41HtbHEOuN3xp8Ti8l021740391076';
    static $main_url = 'https://api.u-on.ru/';
    static $url = '';

    static function init() {
        self::$url = self::$main_url . self::$key . '/';
    }

    static function getUserByUonId($uon_id) {
        if ( !$uon_id ) return false;

        $args = array(
            'meta_query' => [
                [
                    'key' => 'uon_id',
                    'value' => $uon_id,
                ],
            ],
        );
        $users = get_users($args);

        if ( $users ) {
            return $users[0];
        }
        return false;
    }

    static function getPostByUonId($post_type, $uon_id) {
        if ( !$uon_id || !$post_type ) return false;

        $args = [
            'post_type'   => $post_type,
            'post_status' => ['publish', 'future'],
            'suppress_filters' => true,
            'meta_query' => [
                [
                    'key'   => 'uon_id',
                    'value' => $uon_id,
                ],
            ]
        ];
        $posts = get_posts($args);

        if ( $posts ) {
            return $posts[0];
        }
        return false;
    }

    static function getTermByUonId($taxonomy, $uon_id) {
        if ( !$uon_id || !$taxonomy ) return false;

        $term_args = [
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'meta_query' => [
                [
                    'key'   => 'uon_id',
                    'value' => $uon_id,
                ],
            ]
        ];
        $terms = get_terms($term_args);

        if (!is_wp_error($terms) && is_array($terms) && count($terms)) {
            return $terms[0];
        }

        return false;
    }
    static function uonUserByEmail($email) {
        $url = self::$url . 'user/email.json';
        $data = array(
            'email' => $email
        );
        $result = wp_remote_post( $url, ['body' => $data]);
        $data = json_decode($result['body'], false);
        if (is_array($data)) {
            return $data[0];
        }
        return false;
    }

    static function uonUserOrders($user_id) {
        $user = get_user_by('id', $user_id);
        $uon_id = get_user_meta($user->ID, 'uon_id', true);
        $url = self::$url . 'request/search.json';
        $data = array(
            'client_ids' => $uon_id
        );
        $result = wp_remote_post( $url, ['body' => $data]);
        $data = json_decode($result['body'], false);
        if (isset($data->requests)) {
            return $data->requests;
        }
        return false;
    }

    static function wpUsers($uon = true) {
        if ($uon) {
            $args = array(
                'role' => 'contributor',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'uon_id',
                        'compare' => 'EXISTS',
                    ],
                    [
                        'key' => 'uon_id',
                        'compare' => '!=',
                        'value' => '',
                    ],
                ],
            );

        } else {
            $args = array(
                'role' => 'contributor',
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'key' => 'uon_id',
                        'compare' => 'NOT EXISTS',
                    ],
                    [
                        'key' => 'uon_id',
                        'value' => '',
                    ],
                ],
            );
        }
        return get_users($args);
    }

    static function wpOrder($uon_id) {
        $order = new WP_Query([
            'post_type' => 'orders',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'uon_id',
                    'value' => $uon_id,
                ],
            ],
        ]);
        if ($order->have_posts()) {
            return $order->posts[0];
        }
        return false;
    }
    static function wpSetOrder($order) {
        $wp_order = self::wpOrder($order->id);
        if ($wp_order) {

        } else {

            $order_args = array(
                'post_type' => 'orders',
                'post_title' => $order->id,
                'post_status' => 'publish',
                'meta_input' => [
                    'uon_id' => $order->id,
                ],
            );
            $wp_order = wp_insert_post($order_args);
        }

    }

    static function updateAll() {

        //Получаем u_id для всех пользователей
        $users = self::wpUsers(false);
        foreach ($users as $user) {
            $uon_data = self::uonUserByEmail($user->user_email);
            if (isset($uon_data->u_id)) {
                update_user_meta( $user->ID, 'uon_id', $uon_data->u_id );
            }
        }

        //Обновляем все заказы
        $users = self::wpUsers();
        foreach ($users as $user) {
            $u_data = self::uonUserOrders($user->ID);
            foreach ($u_data as $order) {
                self::wpSetOrder($order);
            }
        }

    }



}
Uon::init();