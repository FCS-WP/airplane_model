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

//check after click add to cart
add_filter('woocommerce_add_to_cart_validation', 'limit_quanity_products', 10, 3);
function limit_quanity_products($passed, $product_id, $quantity)
{

    $cart_contents = WC()->cart->get_cart();

    if ($quantity > 6) {
        wc_add_notice(__('You can only buy a maximum of 6 of this product.', 'woocommerce'), 'error');
        return false;
    }


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




    return $passed;
}

//check after click update cart in cart page
add_action('woocommerce_before_calculate_totals', 'limit_quanity_products_update_cart', 10, 1);
function limit_quanity_products_update_cart($cart)
{

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $quantity = $cart_item['quantity'];

        if ($quantity > 6) {
            $cart_item['quantity'] = 6;
            WC()->cart->set_quantity($cart_item_key, 6);
            wc_add_notice(__('You can only buy a maximum of 6 of each products.', 'woocommerce'), 'error');
        }
    }
}

/**
 * Hide Product Has Status Private
 *
 */
add_filter('posts_where', 'hide_private_products');

function hide_private_products($where)
{
    if (is_admin()) return $where;
    if (is_shop() || is_archive() || is_singular()) {
        global $wpdb;
        return " $where AND {$wpdb->posts}.post_status != 'private' ";
    } else {
        return $where;
    }
}



/*
 * Customize the display of transfer information in woocommerce
 *
 */
add_filter('woocommerce_bacs_accounts', '__return_false');

add_action('woocommerce_email_before_order_table', 'zippy_email_instructions', 10, 3);
function zippy_email_instructions($order, $sent_to_admin, $plain_text = false)
{

    if (! $sent_to_admin && 'bacs' === $order->get_payment_method() && $order->has_status('on-hold')) {
        zippy_bank_details($order->get_id());
    }
}

add_action('woocommerce_thankyou_bacs', 'zippy_thankyou_page');
function zippy_thankyou_page($order_id)
{
    zippy_bank_details($order_id);
}

function zippy_bank_details($order_id = '')
{
    $bacs_accounts = get_option('woocommerce_bacs_accounts');
    if (! empty($bacs_accounts)) {
        ob_start();
?>
        <table style=" border: 1px solid #ddd; border-collapse: collapse; width: 100%; ">

            <tr>
                <td colspan="2" style="border: 1px solid #eaeaea;padding: 6px 10px;"><strong>Transfer information</strong></td>
            </tr>
            <?php
            foreach ($bacs_accounts as $bacs_account) {
                $bacs_account = (object) $bacs_account;
                $account_name = $bacs_account->account_name;
                $bank_name = $bacs_account->bank_name;
                $stk = $bacs_account->account_number;
                $icon = isset($bacs_account->iban) ? $bacs_account->iban : THEME_URL . '-child/assets/icons/paynow_client_qr.png';
            ?>
                <tr>
                    <td style="width: 200px;border: 1px solid #eaeaea;padding: 6px 10px;"><?php if ($icon): ?><img id="imageToDownload" src="<?php echo $icon; ?>" alt="" /><?php endif; ?></td>
                    <td style="border: 1px solid #eaeaea;padding: 6px 10px;">
                        <strong>Account owner:</strong> <?php echo $account_name; ?><br>
                        <strong>Transfer content:</strong> Order: <?php echo $order_id; ?>
                    </td>
                </tr>
                <table>
                    <script>
                        console.log(document.getElementById("downloadBtn"));
                        document.getElementById("downloadBtn").addEventListener("click", function() {

                            const imageUrl = document.getElementById("imageToDownload").src;
                            const link = document.createElement("a");
                            link.href = imageUrl;
                            link.download = 'airplane_model_qr.png';
                            link.click();
                        });
                    </script>
        <?php
            }

            echo ob_get_clean();;
        }
    }


    add_action('woocommerce_email_before_order_table', 'zippy_woocommerce_email_before_order_table', 5);
    add_action('woocommerce_thankyou_bacs', 'zippy_woocommerce_email_before_order_table', 5);
    function zippy_woocommerce_email_before_order_table($order)
    {
        if (is_numeric($order)) $order = wc_get_order($order);
        if ($order->get_payment_method() == 'bacs') {
            echo '<p style=" color: #3e3c3c; font-size: 14px; border: 1px dashed #ff0000; padding: 5px; background: #fffdf3; line-height: 20px; ">
        <strong style="color:red;">Note:</strong> Click <a id="downloadBtn" style=" color: #42a2cd; font-weight: 700; " href="#"> here </a> to save the QR code for payment</p>';
        }
    }

    add_filter('woocommerce_products_admin_list_table_filters', 'zippy_featured_filter');

    function zippy_featured_filter($filters)
    {
        $filters['featured_choice'] = 'zippy_filter_by_featured';
        return $filters;
    }

    function zippy_filter_by_featured()
    {
        $current_featured_choice = isset($_REQUEST['featured_choice']) ? wc_clean(wp_unslash($_REQUEST['featured_choice'])) : false;
        $output = '<select name="featured_choice" id="dropdown_featured_choice"><option value="">Filter by featured status</option>';
        $output .= '<option value="onlyfeatured" ';
        $output .= selected('onlyfeatured', $current_featured_choice, false);
        $output .= '>Featured Only</option>';
        $output .= '<option value="notfeatured" ';
        $output .= selected('notfeatured', $current_featured_choice, false);
        $output .= '>Not Featured</option>';
        $output .= '</select>';
        echo $output;
    }

    add_filter('parse_query', 'zippy_featured_products_query');

    function zippy_featured_products_query($query)
    {
        global $typenow;
        if ($typenow == 'product') {
            if (! empty($_GET['featured_choice'])) {
                if ($_GET['featured_choice'] == 'onlyfeatured') {
                    $query->query_vars['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'slug',
                        'terms' => 'featured',
                    );
                } elseif ($_GET['featured_choice'] == 'notfeatured') {
                    $query->query_vars['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'slug',
                        'terms' => 'featured',
                        'operator' => 'NOT IN',
                    );
                }
            }
        }
        return $query;
    }
