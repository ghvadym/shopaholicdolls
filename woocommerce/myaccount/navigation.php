<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_dashboard = !is_wc_endpoint_url();

$add_payment_method = is_wc_endpoint_url('add-payment-method');

global $wp;
$request = explode('/', $wp->request);
$is_dashboard_page = end($request) == 'konto' || end($request) == 'account' && is_account_page();
?>

<div class="mobile_version">
    <?php get_template_part_var('myaccount/nav-items'); ?>
</div>
<div class="account__nav_tabs">
    <?php foreach (wc_get_account_menu_items() as $endpoint => $label):
        $active_tab = is_wc_endpoint_url($endpoint);

        if ($add_payment_method && $endpoint === 'payment-methods'):
            $active_tab = true;
            $endpoint = 'add-payment-method';
        endif;

        if ($is_dashboard_page && $endpoint === 'edit-account'):
            $active_tab = true;
        endif;

        $is_active_tab_mob = ($is_dashboard && $endpoint === 'edit-account') || is_wc_endpoint_url($endpoint) || ($add_payment_method && $endpoint === 'payment-methods');
        ?>

        <div class="account__nav_tab account-tab-<?php echo $endpoint; ?><?php echo $active_tab || ($is_active_tab_mob) ? ' tab-active' : ''; ?>">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)) ?>"
               class="nav__tab_title">
                <?php echo esc_html($label); ?>
            </a>

            <?php if ($is_active_tab_mob):
                if ($add_payment_method):
                    $endpoint = 'add-payment-method';
                endif;

                if ($add_payment_method && !IS_MOB):
                    echo '</div>';
                    continue;
                endif;

                $endpoint = str_replace(['add-', 'edit-'], ['form-add-', 'form-edit-'], $endpoint);
                ?>
                <div class="account__tab_content mobile_version">
                    <div class="content__blocks">
                        <?php 
                        if ($endpoint == 'backinstock') {
                            wc_get_template("myaccount/back-in-stock.php", [
                                'navigation' => true
                            ]);
                        } else {
                            wc_get_template("myaccount/{$endpoint}.php", [
                                'navigation' => true
                            ]);
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>
</div>

<?php echo '<div class="desktop_version">'; ?>
    <div class="content-divider"></div>
    <?php get_template_part_var('myaccount/nav-items'); ?>
<?php echo '</div>'; ?>
