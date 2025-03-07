<?php

class UonOrder extends UonCustomPost
{
    public $post_type = 'uon_orders';

    public function __construct($post, $args = [])
    {
        parent::__construct($post, $args);
    }

    public function loadData($update = false) {
        if ($data = $this->getData()) {
            $user = new UonUser($data->client_id, true);
            $service = false;
            $program = false;
            if (is_array($data->services)) {
                $service = $data->services[0];
                $program = new UonProgram(false, ['uon_id' => $service->catalog_package_id]);
            }
            $tourists_ids = [];
            foreach ($data->tourists as $tourist) {
                $tourist = new UonTourist(false, ['uon_id' => $tourist->u_id, 'user_id' => $user->ID]);
                $tourists_ids[] = $tourist->ID;
            }

            $args = [
                'post_type'   => 'uon_orders',
                'post_status' => 'publish',
                'post_title'  => $data->client_surname.' '.$data->client_name.' - '.wp_date('d.m.y', strtotime($data->dat_request)),
                'post_date'   => $data->dat_request,
                'post_author' => $user->ID,
                'post_content' => $service ? $service->description : '',
                'meta_input' => [
                    'uon_id' => $this->uon_id,
                    'uon_travel_type' => $data->travel_type,
                    'uon_status' => $data->status,
                    'uon_price' => $data->calc_price,
                    'uon_date_begin' => $service ? $service->date_begin : '',
                    'uon_date_end' => $service ? $service->date_end : '',
                    'uon_tourists' => $tourists_ids,
                ],
            ];
            if ($update && $this->ID) {
                $args['ID'] = $this->ID;
            }
            if ($program) {
                $args['post_category'] = [$program->ID];
            }

            return wp_insert_post( $args );
        }
    }

    public function getData() {
        $url = UOn::$url . 'request/'.$this->uon_id.'.json';
        $result = wp_remote_post( $url, ['method' => 'GET']);
        $data = json_decode($result['body'], false);
        if (isset($data->request)) {
            return $data->request[0];
        }
        return false;
    }
}