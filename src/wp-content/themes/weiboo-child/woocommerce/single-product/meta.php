<?php

/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 */

if (! defined('ABSPATH')) {
    exit;
}

global $product;

$brands = get_the_terms($product->get_id(), 'pwb-brand');

?>
<div class="product_meta">
    <?php do_action('woocommerce_product_meta_start'); ?>

    <table class="specifications-table">
        <tr>
            <h6 class="text-uppercase">Specification</h6>
        </tr>
        <tr>
            <th><?php esc_html_e('SKU', 'weiboo'); ?></th>
            <td> <?php echo wp_kses_post($product->get_sku() ? $product->get_sku() : esc_html__('N/A', 'weiboo')); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Category', 'weiboo'); ?></th>
            <td><?php echo wc_get_product_category_list($product->get_id(), ', '); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Brand', 'weiboo'); ?></th>
            <td><?php
                if (!empty($brands)) {
                    foreach ($brands as $brand) {
                        $brand_link = get_term_link($brand); 
                        echo '<a href="' . esc_url($brand_link) . '">' . esc_html($brand->name) . '</a>';
                    }
                }else{
                    echo "NULL";
                }
            ?></td>
        </tr>
        
    </table>
    <?php do_action('woocommerce_product_meta_end'); ?>
</div>
