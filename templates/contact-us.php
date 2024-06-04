<?php
/*
* Template name: Contact us
*/
get_header();

$padeID       = get_the_ID();
$page_title   = get_the_title();
$fields       = get_fields($padeID);
$map          = $fields['google_map'] ?? [];
$contact_info = $fields['contact_info'] ?? [];
$form_ID = $fields['contact_form'] ?? '';
$contact_form = '[contact-form-7 id="' . esc_attr($form_ID) . '" html_class="contact-form"]' ?? '';
?>

<section class="contact-us">
    <div class="container">
        <h1><?php echo $page_title ?></h1>

        <div class="contact-us__row">
            <div class="contact-us__col">
                <?php echo do_shortcode($contact_form); ?>
            </div>
            <div class="contact-us__col">
                <?php if( $map ): ?>
                    <div class="contact-us__map" data-zoom="16">
                        <div class="contact-us__map-marker" data-lat="<?php echo esc_attr($map['lat']); ?>" data-lng="<?php echo esc_attr($map['lng']); ?>"></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($contact_info)) : ?>
                <div class="contact-us__info">
                    <?php foreach ($contact_info as $contact_item):
                        $icon = $contact_item['icon'] ?? '';
                        $link_option = $contact_item['link_options'] ?? '';
                        $link = $contact_item['link'] ?? [];
                        $link_url = $link['url'] ?? '';
                        $link_title = $link['title'] ?? '';
                        $text = $contact_item['text'] ?? '';
                        ?>
                        <div class="contact-us__info-item">
                            <img class="contact-us__info-icon" src="<?php echo $icon ?>"
                                 alt="<?php _e('Contact us icon', DOMAIN); ?>"
                                 title="<?php _e('Contact us icon', DOMAIN); ?>">

                            <?php if ($link_option == 'link') : ?>
                                <a class="contact-us__info-link" href="<?php echo $link_url; ?>"><?php echo $link_title; ?></a>
                            <?php endif; ?>

                            <?php if ($link_option == 'text') : ?>
                                <div class="contact-us__info-text">
                                    <?php echo $text; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="contact-us__socials">
                    <?php socials(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
?>
