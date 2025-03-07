<?php

abstract class UonCustomTerm {


	public $ID;

	public $term;

    public $uon_id;

    public $taxonomy;

    public function __construct($term, $args = [])
    {
        $default_args = [
            'uon_id' => false,
            'update' => false,
        ];
        $args = array_merge($default_args, $args);
        if (! $term && $args['uon_id']) {
            $this->uon_id = $args['uon_id'];
            $this->term = UOn::getTermByUonId($this->taxonomy, $this->uon_id);
            if (isset($this->term->ID)) {
                $this->ID = absint($this->term->ID);
            } else {
                $this->ID = $this->loadData();
                $this->term = get_term($this->ID);
            }
        } else {
            if ( $term instanceof WP_Term || $term instanceof UonCustomTerm ) :
                $this->ID = absint($term->ID);
                $this->term = $term;
            else :
                $this->ID = absint($term);
                $this->term = get_post($this->ID);
            endif;
            $this->uon_id = get_term_meta($this->ID, 'uon_id', true);
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
			return $this->term;
		else :
			$value = get_term_meta( $this->ID, 'uon_' . $key, true );
		endif;

		return $value;
	}

	public function get_term_data() {
		return $this->term;
	}

}
