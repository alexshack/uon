<?php

abstract class UonCustomPost {


	public $ID;

	public $post;

    public $uon_id;

    public $post_type;

    public function __construct($post, $args = [])
    {
        $default_args = [
            'uon_id' => false,
            'update' => false,
        ];
        $args = array_merge($default_args, $args);
        if (! $post && $args['uon_id']) {
            $this->uon_id = $args['uon_id'];
            $this->post = UOn::getPostByUonId($this->post_type, $this->uon_id);
            if (isset($this->post->ID)) {
                $this->ID = absint($this->post->ID);
            } else {
                $this->ID = $this->loadData();
                $this->post = get_post($this->ID);
            }
        } else {
            if ( $post instanceof WP_Post || $post instanceof UonCustomPost ) :
                $this->ID = absint($post->ID);
                $this->post = $post;
            else :
                $this->ID = absint($post);
                $this->post = get_post($this->ID);
            endif;
            $this->uon_id = get_post_meta($this->ID, 'uon_id', true);
            if ($args['update']) {
                $this->loadData(true);
            }
        }

    }

	public function __isset( $key ) {
		return metadata_exists( 'post', $this->ID, 'uon_' . $key );
	}

	public function __get( $key ) {
		if ( ! isset( $key ) ) :
			return $this->post;
		else :
			$value = get_post_meta( $this->ID, 'uon_' . $key, true );
		endif;

		return $value;
	}

	public function get_post_data() {
		return $this->post;
	}

	public function get_terms_sorted_by_sp_order( $taxonomy ) {
		$terms = get_the_terms( $this->ID, $taxonomy );
		if ( $terms ) {
			usort( $terms, 'sp_sort_terms' );
		}
		return $terms;
	}
}
