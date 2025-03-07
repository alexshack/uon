<?php

class UonUser
{

    public $ID, $user, $uon_id;

    public function __construct($user, $uon = false)
    {
        if ($uon) {
            $this->uon_id = $user;
            $this->user = UOn::getUserByUonId($this->uon_id);
            if (isset($this->user->ID)) {
                $this->ID = absint($this->user->ID);
            } else {
                $this->ID = $this->loadUser();
                $this->user = get_user($this->ID);
            }
        } else {
            if ($user instanceof WP_User) :
                $this->ID = absint($user->ID);
                $this->user = $user;
            else :
                $this->ID = absint($user);
                $this->user = get_post($this->ID);
            endif;
            $this->uon_id = get_user_meta($this->ID, 'uon_id', true);
        }
    }

    public function __get( $key ) {
        if ( ! isset( $key ) ) :
            return $this->user;
        else :
            $value = get_user_meta( $this->ID, 'uon_' . $key, true );
        endif;

        return $value;
    }

    public function loadUser($update = false) {
        if ($data = $this->getData()) {
            $args = [
                'user_login'   => $data->u_email,
                'user_email' => $data->u_email,
                'role'  => 'subscriber',
                'first_name'   => $data->u_name,
                'last_name'   => $data->u_surname,
                'meta_input' => [
                    'uon_id' => $this->uon_id,
                    'uon_sname' => $data->u_sname,
                    'uon_phone' => $data->u_phone,
                    'uon_passport_number' => $data->u_passport_number,
                    'uon_passport_taken' => $data->u_passport_taken,
                    'uon_passport_date' => $data->u_passport_date,
                    'uon_passport_code' => $data->u_passport_code,
                    'uon_address' => $data->address,
                ]
            ];
            if ($update && $this->ID) {
                $args['ID'] = $this->ID;
            }
            return wp_insert_user( $args );
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

    public function loadOrders($update = false) {
        $url = UOn::$url . 'request/search.json';
        $data = array(
            'client_ids' => $this->uon_id
        );
        $result = wp_remote_post( $url, ['body' => $data]);
        $data = json_decode($result['body'], false);
        $orders = [];
        if (isset($data->requests)) {
            foreach ($data->requests as $request) {
                $orders[] = new UonOrder(false, ['uon_id' => $request->id, 'update' => $update]);
            };
        }
        return $orders;
    }
}