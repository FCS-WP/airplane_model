<?php
// Remove default tabs
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{
    unset($tabs['description']);          // Remove the description tab
    unset($tabs['reviews']);          // Remove the reviews tab
    unset($tabs['additional_information']);   // Remove the additional information tab
    return $tabs;
}

// Remove related products
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// Remove product meta from default location
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Add product meta below short description and above add to cart button
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 21);


if (! function_exists('shin_change_currency_symbol')) {


    function shin_change_currency_symbol($currency_symbol, $currency)
    {
        switch ($currency) {
            case 'SGD':
                $currency_symbol = 'S$';
                break;
        }

        return $currency_symbol;
    }
    add_filter('woocommerce_currency_symbol', 'shin_change_currency_symbol', 10, 2);
}


add_filter('woocommerce_add_to_cart_validation', 'limit_quanity_products', 10, 3);
function limit_quanity_products($passed, $product_id, $quantity) {

    $cart_contents = WC()->cart->get_cart();
    

    foreach ($cart_contents as $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $current_quantity = $cart_item['quantity']; 
            $new_quantity = $current_quantity + $quantity;
            
            
            if ($new_quantity > 6) {
                wc_add_notice(__('You can only buy a maximum of 6 of this product.', 'woocommerce'), 'error');
                return false;
            }
        }
    }
    

    if ($quantity > 6) {
        wc_add_notice(__('You can only buy a maximum of 6 of this product.', 'woocommerce'), 'error');
        return false; 
    }

    return $passed; 
}

add_action('woocommerce_before_calculate_totals', 'limit_quanity_products_update_cart', 10, 1);
function limit_quanity_products_update_cart($cart) {
    
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $quantity = $cart_item['quantity'];

        if ($quantity > 6) {
            $cart_item['quantity'] = 6; 
            WC()->cart->set_quantity($cart_item_key, 6); 
            wc_add_notice(__('You can only buy a maximum of 6 of each products.', 'woocommerce'), 'error');
        }
    }
}