<?php

namespace MABEL_SILITE\Code\Services
{
	class Woocommerce_Service
	{
		public static function get_product($id)
		{
			$product = wc_get_product($id);
            if($product)
                return $product;
            return null;
		}
	}
}