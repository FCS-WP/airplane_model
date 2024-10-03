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
?>
<div class="product_meta">
    <?php do_action('woocommerce_product_meta_start'); ?>

    <table class="specifications-table">
        <tr>
            <h4 class=" fs-6 text-uppercase">Specification</h4>
        </tr>
        <tr>
            <th><?php esc_html_e('SKU', 'weiboo'); ?></th>
            <td> <?php echo wp_kses_post($product->get_sku() ? $product->get_sku() : esc_html__('N/A', 'weiboo')); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Product Title', 'weiboo'); ?></th>
            <td><?php echo esc_html($product->get_name()) ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Scale', 'weiboo'); ?></th>
            <td>N/A</td>
        </tr>
        <tr>
            <th><?php esc_html_e('Base Price', 'weiboo'); ?></th>
            <td><?php echo esc_html($product->get_regular_price()); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Discount Price', 'weiboo'); ?></th>
            <td><?php echo esc_html($product->get_sale_price() ? $product->get_sale_price() : esc_html__('N/A', 'weiboo')); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Description', 'weiboo'); ?></th>
            <td><?php echo esc_html($product->get_description()); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Category', 'weiboo'); ?></th>
            <td><?php echo wc_get_product_category_list($product->get_id(), ', '); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Type', 'weiboo'); ?></th>
            <td><?php echo esc_html('N/A'); ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Date of Collection', 'weiboo'); ?></th>
            <td><?php echo esc_html('N/A'); ?></td>
        </tr>
    </table>
    <?php do_action('woocommerce_product_meta_end'); ?>
</div>