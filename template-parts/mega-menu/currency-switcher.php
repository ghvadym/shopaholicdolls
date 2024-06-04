<?php
if (!MULTI_CURRENCY_ACTIVE) {
    return;
}

$multi_currency_settings = WOOMULTI_CURRENCY_Data::get_ins();
$current_currency = $multi_currency_settings->get_current_currency();

$woo_multi_currency_params = get_option('woo_multi_currency_params');
if (empty($woo_multi_currency_params)) {
    return;
}

?>

<div class="header__lang_switcher currency-switcher">
    <div class="lang_switcher__head currency-switcher__wrapper">
        <div class="currency-switcher__icon">
            <?php echo get_local_img_html('Currency.svg', '', __('Currency switcher', DOMAIN)); ?>
        </div>
        <div class="lang_switcher__selected">
                <span class="wmc-current-currency-code">
                    <?php
                    $currency_symbol = '';
                    switch ($current_currency) {
                        case 'EUR':
                            $currency_symbol = '€';
                            break;
                        case 'PLN':
                            $currency_symbol = 'PLN';
                            break;
                        default:
                            $currency_symbol = $current_currency;
                            break;
                    }
                    echo esc_html($currency_symbol);
                    ?>
                </span>
        </div>
        <div class="lang_switcher__arrow">
            <?php echo get_local_img_html('Arrow.svg', '', __('Currency switcher', DOMAIN)); ?>
        </div>
    </div>
    <div class="lang_switcher__dropdown">
        <div class="lang_switcher__list">
            <?php foreach ($woo_multi_currency_params['currency'] as $index => $currency): ?>
                <?php if ($currency != $current_currency): ?>
                    <div class="lang_switcher__item" data-currency="<?php echo esc_attr($currency); ?>">
                        <a rel="nofollow" class="wmc-currency-redirect" href="#" data-currency="<?php echo esc_attr($currency); ?>">
                            <?php
                            $currency_name = '';
                            $currency_symbol = '';
                            switch ($currency) {
                                case 'EUR':
                                    $currency_name = 'EURO';
                                    $currency_symbol = '€';
                                    break;
                                case 'PLN':
                                    $currency_name = 'ZŁOTY';
                                    $currency_symbol = 'PLN';
                                    break;
                                default:
                                    $currency_name = $currency;
                                    $currency_symbol = $currency;
                                    break;
                            }
                            echo esc_html($currency_name) . " ($currency_symbol)";
                            ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>