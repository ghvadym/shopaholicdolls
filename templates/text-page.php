<?php
/** Template Name: Text page
 * The template for displaying Text page
 */
get_header();

$title = get_the_title();
$fields = get_fields();
$tabs = $fields['tabs'] ?? [];
if (empty($fields) || empty($fields['tabs'])) {
    return;
}
?>

<section class="section text-page">
    <div class="container">
        <h1><?php echo $title; ?></h1>

        <div class="text-page__row">
            <div class="text-page__col">
                <div class="nav__tabs">
                    <?php foreach ($tabs as $index => $item) :
                        $tab = $item['tab'] ?? [];
                        $tab_title = $tab['title'] ?? '';
                        $tab_icon = $tab['icon'] ?? '';
                        $tab_content = $item['tab_content'] ?? [];
                        $tab_content_title = $tab_content['title'] ?? '';
                        $tab_content_text = $tab_content['text'] ?? '';
                        ?>
                        <div class="nav__tab text-page__tab" data-tab="tab-<?php echo esc_attr($index + 1); ?>">
                            <div class="nav__tab_title text-page__tab_title">
                                <img src="<?php echo $tab_icon; ?>" alt="<?php echo $tab_title; ?>" title="<?php echo $tab_title; ?>">
                                <?php echo esc_html($tab_title); ?>
                            </div>

                            <div class="nav__content text-page__content" style="display:none;">
                                <?php echo $tab_content_text; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="text-page__col">
                <div class="nav__contents">
                    <?php foreach ($tabs as $index => $item) :
                        $tab_content = $item['tab_content'] ?? [];
                        $tab_content_title = $tab_content['title'] ?? '';
                        $tab_content_text = $tab_content['text'] ?? '';
                        $first_tab = 0;
                        ?>

                        <?php if ($index == $first_tab) : ?>
                            <div class="nav__content text-page__content" data-tab="tab-<?php echo esc_attr($index + 1); ?>">
                                <h3><?php echo $tab_content_title; ?></h3>
                                <?php echo $tab_content_text; ?>
                            </div>
                        <?php else : ?>
                            <div class="nav__content text-page__content" data-tab="tab-<?php echo esc_attr($index + 1); ?>">
                                <h3><?php echo $tab_content_title; ?></h3>
                                <?php echo $tab_content_text; ?>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>
