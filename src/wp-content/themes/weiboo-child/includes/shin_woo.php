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


