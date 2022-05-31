<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Learts_Vendor_Woocommerce {
	public function getCategoryChildsFull( $parent_id, $pos, $array, $level, &$dropdown ) {

		for ( $i = $pos; $i < count( $array ); $i ++ ) {
			if ( $array[ $i ]->category_parent == $parent_id ) {
				$name       = str_repeat( '- ', $level ) . $array[ $i ]->name;
				$value      = $array[ $i ]->slug;
				$dropdown[] = array(
					'label' => $name,
					'value' => $value,
				);
				$this->getCategoryChildsFull( $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
			}
		}
	}
}
