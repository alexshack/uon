<?php

class UonProgram extends UonCustomTerm
{
    public $taxonomy = 'uon_programs';

    public function __construct($post, $args = [])
    {
        parent::__construct($post, $args);
    }

    public function loadData($update = false) {
        if ($data = $this->getData()) {

            $term_name = $data->name;
            $term_id = false;
            if ($update) {
                $term_id = $this->ID;
            } else {
                $term = wp_insert_term( $term_name, $this->taxonomy, [] );
                if( is_wp_error( $term ) ){
                    //print_r(get_term( 3 ));
                    echo $term_name;
                    echo $term->get_error_message();
                }
                else {
                    $term_id = $term['term_id'];
                }
            }
            if($term_id) {
                update_term_meta($term_id, 'uon_id', $this->id);
                update_term_meta($term_id, 'uon_date_begin', $this->date_begin);
                update_term_meta($term_id, 'uon_date_end', $this->date_end);
            }

            return $term_id;
        }
    }

    public function getData() {
        $url = UOn::$url . 'catalog-package/'.$this->uon_id.'.json';
        $result = wp_remote_post( $url, ['method' => 'GET']);
        $data = json_decode($result['body'], false);
        if (isset($data->record)) {
            return $data->record;
        }
        return false;
    }
}