/**
 * Free Price Label for Zero-Priced Products
 * This code modifies the displayed price for a WooCommerce product. If the price is set to zero or is empty, 
 * it displays "FREE" instead of the price amount.
 * This works for both single products and variable products.
 */

add_filter('woocommerce_get_price_html', 'gbx_price_free_zero', 9999, 2);

function gbx_price_free_zero($price, $product) {
    // Handle variable products
    if ($product->is_type('variable')) {
        $prices = $product->get_variation_prices(true);
        $min_price = current($prices['price']);

        // If the minimum price is zero
        if (0 == $min_price) {
            $max_price = end($prices['price']);
            $min_reg_price = current($prices['regular_price']);
            $max_reg_price = end($prices['regular_price']);

            if ($min_price !== $max_price) {
                $price = wc_format_price_range('FREE', $max_price);
                $price .= $product->get_price_suffix();
            } elseif ($product->is_on_sale() && $min_reg_price === $max_reg_price) {
                $price = wc_format_sale_price(wc_price($max_reg_price), 'FREE');
                $price .= $product->get_price_suffix();
            } else {
                $price = 'FREE';
            }
        }
    // Handle single products with a price of zero
    } elseif (0 == $product->get_price()) {
        $price = '<span class="woocommerce-Price-amount amount">FREE</span>';
    }
    
    return $price;
}
