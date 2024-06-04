<?php

defined( 'ABSPATH' ) or exit;

global $product;
global $pw_gift_cards;
global $pw_gift_cards_email_designer;

$pwgc_custom_amount = isset( $_REQUEST[ PWGC_GIFT_CARD_CUSTOM_AMOUNT_META_KEY ] ) && !empty( $_REQUEST[ PWGC_GIFT_CARD_CUSTOM_AMOUNT_META_KEY ] ) && is_numeric( $_REQUEST[ PWGC_GIFT_CARD_CUSTOM_AMOUNT_META_KEY ] ) ? number_format( $_REQUEST[ PWGC_GIFT_CARD_CUSTOM_AMOUNT_META_KEY ], wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() ) : '';
$pwgc_to = isset( $_REQUEST[ PWGC_TO_META_KEY ] ) ? stripslashes( htmlentities( $_REQUEST[ PWGC_TO_META_KEY ] ) ) : '';
$pwgc_recipient_name = isset( $_REQUEST[ PWGC_RECIPIENT_NAME_META_KEY ] ) ? stripslashes( htmlentities( $_REQUEST[ PWGC_RECIPIENT_NAME_META_KEY ] ) ) : '';
$pwgc_message = isset( $_REQUEST[ PWGC_MESSAGE_META_KEY ] ) ? stripslashes( htmlentities( str_replace( '<br />', "\n", $_REQUEST[ PWGC_MESSAGE_META_KEY ] ) ) ) : '';
$pwgc_delivery_date = isset( $_REQUEST[ PWGC_DELIVERY_DATE_META_KEY ] ) ? stripslashes( htmlentities( $_REQUEST[ PWGC_DELIVERY_DATE_META_KEY ] ) ) : '';
$pwgc_physical_card = $product->get_pwgc_is_physical_card();
$pwgc_show_recipient_name = $product->get_pwgc_show_recipient_name();
$pwgc_show_email_preview = $product->get_pwgc_show_email_preview();
$pwgc_email_design_id = isset( $_REQUEST[ PWGC_EMAIL_DESIGN_ID_META_KEY ] ) ? absint( $_REQUEST[ PWGC_EMAIL_DESIGN_ID_META_KEY ] ) : '';
$pwgc_email_design_ids = $product->get_pwgc_email_design_ids();

$pwgc_from = '';
if ( isset( $_REQUEST[ PWGC_FROM_META_KEY ] ) ) {
    $pwgc_from = stripslashes( htmlentities( $_REQUEST[ PWGC_FROM_META_KEY ] ) );
} else if ( 'yes' === get_option( 'pwgc_current_user_populates_from_field', 'yes' ) ) {
    $current_user = wp_get_current_user();
    $pwgc_from = $current_user->display_name;
}

if ( isset( $_REQUEST[ 'attribute_' . PWGC_DENOMINATION_ATTRIBUTE_SLUG ] ) ) {
    $selected = true;
} else {
    $default_attribute = $product->get_variation_default_attribute( PWGC_DENOMINATION_ATTRIBUTE_NAME );
    $selected = !empty( $default_attribute );
}

if ( $pw_gift_cards->use_default_currency_in_cart() ) {
    $pwgc_custom_amount = apply_filters( 'pwgc_to_current_currency', $pwgc_custom_amount );
}

?>
<style>
    .pwgc-field-container {
        margin-bottom: 14px;
    }

    .pwgc-label {
        font-weight: 600;
        display: block;
    }

    .pwgc-subtitle {
        font-size: 11px;
        line-height: 1.465;
        color: #767676;
    }

    .pwgc-input-text {
        width: 100%;
    }

    #pwgc-recipient-count {
        font-weight: 600;
    }

    #pwgc-quantity-one-per-recipient {
        display: none;
    }

    #pwgc-message {
        display: block;
        height: 100px;
        width: 100%;
    }

    #pwgc-form-email-design {
        position: absolute;
    }

    .pwgc-hidden {
        display: none;
    }

    <?php
        if ( is_a( $product, 'WC_Product_PW_Gift_Card' ) && ! apply_filters( 'pwgc_show_amount_price', $product->has_amount_on_sale() ) ) {
            // Don't really need to repeat the amount on the Product Page unless there is a sale.
            ?>
            .woocommerce-variation-price {
                display: none !important;
            }
            <?php
        }
    ?>

    .add_to_cart_wrapper {
        flex-wrap: wrap;
    }

    #pwgc-purchase-container {
        width: 100%;
        flex-basis: 100% !important;
        display: block;
    }

    /* Support for the Neve Theme */
    .woocommerce.single .single_variation_wrap > style, .woocommerce.single .woocommerce-variation-add-to-cart > style {
        display: none;
    }

    .single_add_to_cart_button {
        flex: 1;
    }

    .woocommerce-variation-add-to-cart {
        flex-wrap: wrap !important;
    }

    #pwgc-email-design-id {
        margin-right: 1em;
    }

    #pwgc-email-preview-container {
        margin-top: 1em;
        display: none;
        width: 100%;
        min-height: 500px;
        border-width: 1px;
    }

    #pwgc-form-email-design {
        padding-bottom: 1em;
    }

    #pwgc-email-preview-button, #pwgc-email-design-id {
        float: initial !important;
    }
</style>

<?php add_action('wp_footer', 'autofill_gift_card_email_field_script'); ?>

<?php function autofill_gift_card_email_field_script() {
    if (is_user_logged_in()) :
        $user_info = wp_get_current_user();
        $user_email = $user_info->user_email;

        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                const emailInputRow = document.querySelector('#pwgc-form-to');
                const emailInput = document.querySelector('#pwgc-to');
                const checkbox = document.querySelector('.gift-card__email-checkbox');
                const checkboxWrap = document.querySelector('.gift-cards__checkbox-wrap');
                const inputLabel = document.querySelector('.gift-card__email-checkbox-label');

                if (checkboxWrap.classList.contains('user-not-logged-in')) {
                    checkboxWrap.classList.remove('user-not-logged-in');
                }

                function handleCheckboxChange() {
                    if (checkbox.checked) {
                        emailInputRow.classList.remove('user-logged-in');
                        inputLabel.classList.add('checked');
                    } else {
                        emailInputRow.classList.add('user-logged-in');
                        inputLabel.classList.remove('checked');
                    }
                }

                checkbox.addEventListener('change', handleCheckboxChange);

                if (emailInput && checkbox && !checkbox.checked) {
                    emailInput.value = '<?php echo esc_js($user_email); ?>';
                }
            });
        </script>
        <?php else : ?>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    const emailInputRow = document.querySelector('#pwgc-form-to');
                    const checkboxWrap = document.querySelector('.gift-cards__checkbox-wrap');

                    emailInputRow.classList.remove('user-logged-in');
                    if (!checkboxWrap.classlist.contains('user-not-logged-in')) {
                        checkboxWrap.classList.add('user-not-logged-in')
                    }
            });
            </script>
        <?php endif; ?>
<?php } ?>
<div id="pwgc-purchase-container" style="<?php echo $selected ? '' : 'display: none;'; ?>" data-min-amount="<?php echo apply_filters( 'pwgc_to_current_currency', $product->get_pwgc_custom_amount_min() ); ?>" data-max-amount="<?php echo apply_filters( 'pwgc_to_current_currency', $product->get_pwgc_custom_amount_max() ); ?>">
    <?php if ( $pwgc_physical_card ) : ?>
            <div id="pwgc-form-shipping-address-message" class="pwgc-field-container">
                <?php _e( 'Gift card will be delivered to the Shipping Address entered during checkout.', 'pw-woocommerce-gift-cards' ); ?>
            </div>

        <?php else : ?>
            <div class="gift-cards__checkbox-wrap user-not-logged-in">
                <label for="gift-card" class="gift-card__email-checkbox-label">
                    <input type="checkbox" name="gift-card" class="gift-card__email-checkbox">
                    <span><?php echo esc_html__('Deliver eGift to Recepient’s Email', DOMAIN); ?></span>
                </label>
            </div>
            <div id="pwgc-form-to" class="pwgc-field-container form-row user-logged-in">
                <label for="pwgc-to" class="pwgc-label"><?php echo __( 'Recepient’s Email', 'pw-woocommerce-gift-cards' ); ?></label>
                <?php
                    $placeholder = __( 'Recepient’s Email', 'pw-woocommerce-gift-cards' );
//                    $subtitle = __( 'Separate multiple email addresses with a comma.', 'pw-woocommerce-gift-cards' );

                    if ( 'yes' !== get_option( 'pwgc_allow_multiple_recipients', 'yes' ) ) {
                        $placeholder = __( 'Recipient email address', 'pw-woocommerce-gift-cards' );
//                        $subtitle = '';
                    }

                    ?>
                    <input type="text" id="pwgc-to" name="<?php echo PWGC_TO_META_KEY; ?>" class="pwgc-input-text" placeholder="<?php echo $placeholder; ?>" value="<?php echo $pwgc_to; ?>" required>
<!--                    <div class="pwgc-subtitle">--><?php //echo $subtitle; ?><!--</div>-->
                    <?php
                ?>
            </div>
    <?php endif; ?>

    <?php
        if ( $pwgc_show_recipient_name ) {
            ?>
            <div id="pwgc-form-recipient-name" class="pwgc-field-container">
                <label for="pwgc-recipient-name" class="pwgc-label"><?php echo __( PWGC_RECIPIENT_NAME_META_DISPLAY_NAME, 'pw-woocommerce-gift-cards' ); ?></label>
                <input type="text" id="pwgc-recipient-name" name="<?php echo PWGC_RECIPIENT_NAME_META_KEY; ?>" class="pwgc-input-text" placeholder="<?php _e( 'Enter a friendly name for the recipient (optional).', 'pw-woocommerce-gift-cards' ); ?>" value="<?php echo $pwgc_recipient_name; ?>">
            </div>
            <?php
        }
    ?>

    <?php
        if ( !$pwgc_physical_card && 'yes' === get_option( 'pwgc_allow_scheduled_delivery', 'yes' ) ) {
            ?>
            <div class="gift-cards__columns-row">
                <div id="pwgc-custom-amount-form" class="pwgc-field-container <?php echo !empty( $pwgc_custom_amount ) ? '' : 'pwgc-hidden'; ?> form-row">
                    <label for="pwgc-custom-amount" class="pwgc-label"><?php echo __( 'Value', 'pw-woocommerce-gift-cards' ); ?></label>
                    <input type="text" id="pwgc-custom-amount" class="short wc_input_price" name="<?php echo PWGC_GIFT_CARD_CUSTOM_AMOUNT_META_KEY; ?>" value="<?php echo esc_attr( $pwgc_custom_amount ); ?>" />
                    <div id="pwgc-custom-amount-error" class="pwgc-subtitle" style="color: red !important;"></div>
                </div>

                <div id="pwgc-form-delivery-date" class="pwgc-field-container gift-card-date-row">
                    <label for="pwgc-delivery-date" class="pwgc-label"><?php echo __( PWGC_DELIVERY_DATE_META_DISPLAY_NAME, 'pw-woocommerce-gift-cards' ); ?></label>
                    <input type="text" id="pwgc-delivery-date" name="<?php echo PWGC_DELIVERY_DATE_META_KEY; ?>" class="pwgc-input-text" placeholder="<?php _e( 'Now', 'pw-woocommerce-gift-cards' ); ?>" value="<?php echo $pwgc_delivery_date; ?>" autocomplete="off">
                    <!--                <div class="pwgc-subtitle">--><?php //_e( 'Up to a year from today', 'pw-woocommerce-gift-cards' ); ?><!--</div>-->
                </div>
            </div>
            <?php
        }
    ?>

    <div id="pwgc-form-message" class="pwgc-field-container form-row">
        <label for="pwgc-message" class="pwgc-label"><?php echo __( 'Message', 'pw-woocommerce-gift-cards' ); ?> <?php _e( '(Optional)', 'pw-woocommerce-gift-cards' ); ?></label>
        <textarea id="pwgc-message" name="<?php echo PWGC_MESSAGE_META_KEY; ?>" placeholder="<?php _e( 'Add a message', 'pw-woocommerce-gift-cards' ); ?>" maxlength="<?php echo PWGC_MAX_MESSAGE_CHARACTERS; ?>"><?php echo $pwgc_message; ?></textarea>
        <div class="pwgc-subtitle"><span id="pwgc-message-characters-remaining"><?php echo PWGC_MAX_MESSAGE_CHARACTERS; ?></span> <?php _e( 'characters remaining', 'pw-woocommerce-gift-cards' ); ?></div>
    </div>

    <div id="pwgc-form-from" class="pwgc-field-container form-row">
        <label for="pwgc-from" class="pwgc-label"><?php echo __( 'Your signature', 'pw-woocommerce-gift-cards' ); ?></label>
        <input type="text" id="pwgc-from" name="<?php echo PWGC_FROM_META_KEY; ?>" class="pwgc-input-text" placeholder="<?php _e( 'Your signature', 'pw-woocommerce-gift-cards' ); ?>" value="<?php echo $pwgc_from; ?>" required>
    </div>

    <?php

        // Display design options.
        $design_options = array();

        if ( count( $pwgc_email_design_ids ) > 1 ) {
            $designs = $pw_gift_cards_email_designer->get_designs();

            foreach ( $designs as $id => $design ) {
                if ( in_array( $id, $pwgc_email_design_ids ) ) {
                    $design_options[ $id ] = $design['name'];
                }
            }
        }

    ?>
    <div id="pwgc-form-email-design" class="pwgc-field-container">
        <?php
            if ( count( $design_options ) > 0 ) {
                ?>
                <label for="pwgc-email-design" class="pwgc-label"><?php echo __( PWGC_EMAIL_DESIGN_META_DISPLAY_NAME, 'pw-woocommerce-gift-cards' ); ?></label>
                <select id="pwgc-email-design-id" name="<?php echo PWGC_EMAIL_DESIGN_ID_META_KEY; ?>">
                    <?php
                        foreach ( $design_options as $id => $name ) {
                            ?>
                            <option value="<?php echo absint( $id ); ?>" <?php echo selected( $pwgc_email_design_id, $id ); ?>><?php echo esc_html( $name ); ?></option>
                            <?php
                        }
                    ?>
                </select>
                <?php
            } else {
                $design_id = count( $pwgc_email_design_ids ) > 0 ? $pwgc_email_design_ids[0] : '0';
                ?>
                <input id="pwgc-email-design-id" type="hidden" name="<?php echo PWGC_EMAIL_DESIGN_ID_META_KEY; ?>" value="<?php echo $design_id; ?>">
                <?php
            }

            if ( $pwgc_show_email_preview && ! $pwgc_physical_card ) {
                ?>
                <button id="pwgc-email-preview-button" class="button pwgc-email-preview-button"><?php _e( 'Preview', 'pw-woocommerce-gift-cards' ); ?></button>
                <iframe id="pwgc-email-preview-container"></iframe>
                <?php
            }
        ?>
    </div>

    <div id="pwgc-quantity-one-per-recipient" class="pwgc-field-container">
        <div class="pwgc-label"><?php _e( 'Quantity', 'pw-woocommerce-gift-cards' ); ?>: <span id="pwgc-recipient-count">1</span></div>
        <div class="pwgc-subtitle"><?php _e( '1 to each recipient', 'pw-woocommerce-gift-cards' ); ?></div>
    </div>
</div>
<?php
