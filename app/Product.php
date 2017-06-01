<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

	/**
	 * Determines the price for an agent, subagent or agent-0 (admin).
	 *
	 * Price types can be any of the following:
	 *     F = Fixed price (uses the product_prices.price field)
	 *     I = Inherit from parent agent, or product.default_price if there is no parent
	 *     N = Not available (return null)
	 *
	 * A missing product_price record is the same as N. This means if an agent
	 * is created then pricing must be set for them before they can use those
	 * products.
	 */
	public static function getPriceForAgent($agent, $product_id)
	{
		// Admin always uses products.default_price
		if (!$agent) {
			return Product::find($product_id)->default_price;
		}

		$price = ProductPrice::whereAgentId($agent->id)->whereProductId($product_id)->first();

		// Not available
		if (!$price || $price->type == 'N') {
			return null;
		}

		// Fixed
		if ($price->type == 'F') {
			return $price->price;
		}

		// Inherit
		return self::getPriceForAgent($agent->parent, $product_id);
	}

	public function price()
	{
		return $this->hasMany('App\ProductPrice');
	}

}
