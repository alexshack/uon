<?php

class UonTourist extends UonCustomPost
{
    public $post_type = 'uon_tourists';
    public $user_id;
    public $name;

    public function __construct($post, $args = [])
    {
        $this->user_id = $args['user_id'];
        parent::__construct($post, $args);
        $this->name = $this->getName();

    }

    public function getName() {
        $names = [];
        if ($this->__get('surname')) {
            $names[] = $this->__get('surname');
        }
        if ($this->__get('name')) {
            $names[] = $this->__get('name');
        }
        if ($this->__get('sname')) {
            $names[] = $this->__get('sname');
        }

        return implode(' ', $names);
    }
    public function loadData($update = false) {
        if ($data = $this->getData()) {

            $args = [
                'post_type'   => $this->post_type,
                'post_status' => 'publish',
                'post_title'  => $data->u_surname.' '.$data->u_name.' '.$data->u_sname,
                'post_date'   => $data->u_date_update,
                'post_author' => $this->user_id,
                'meta_input' => [
                    'uon_id' => $this->uon_id,
                    'uon_surname' => $data->u_surname,
                    'uon_name' => $data->u_name,
                    'uon_sname' => $data->u_sname,
                    'uon_phone' => $data->u_phone,
                    'uon_sex' => $data->u_sex,
                    'uon_passport_number' => $data->u_passport_number,
                    'uon_passport_taken' => $data->u_passport_taken,
                    'uon_passport_date' => $data->u_passport_date,
                    'uon_passport_code' => $data->u_passport_code,
                    'uon_address' => $data->address,
                    'uon_birthday' => $data->u_birthday,
                    'uon_birthday_place' => $data->u_birthday_place,
                    'uon_birthday_certificate' => $data->u_birthday_certificate,
                    'uon_birthday_certificate_given' => $data->u_birthday_certificate_given,
                    'uon_birthday_certificate_organization' => $data->u_birthday_certificate_organization,
                    'uon_nationality' => $data->nationality,
                ],
            ];
            if ($update && $this->ID) {
                $args['ID'] = $this->ID;
            }
            return wp_insert_post( $args );
        }
    }

    public function getData() {
        $url = UOn::$url . 'user/'.$this->uon_id.'.json';
        $result = wp_remote_post( $url, ['method' => 'GET']);
        $data = json_decode($result['body'], false);
        if (isset($data->user)) {
            return $data->user[0];
        }
        return false;
    }
}