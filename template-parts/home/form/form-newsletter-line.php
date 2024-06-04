<?php

if (empty($options)) {
    return;
}

$title = $options['form_newsletter_top_line_text'] ?? '';

if (!$title) {
    return;
}

$top_line_styles = '';

if (!empty($options['form_newsletter_top_line_bg'])) {
    $top_line_styles = 'background-color: ' . $options['form_newsletter_top_line_bg'];
}

if (!empty($options['form_newsletter_top_line_text_color'])) {
    $top_line_styles = 'color: ' . $options['form_newsletter_top_line_text_color'];
}

if ($top_line_styles) {
    $top_line_styles = 'style="' . $top_line_styles . '"';
}

$link = !empty($options['form_newsletter_top_line_link']['url']) ? $options['form_newsletter_top_line_link']['url'] : '';
?>

<?php if ($link): ?>
    <a class="full-width-line" href="<?php echo esc_url($link); ?>" <?php echo $top_line_styles; ?>>
        <?php echo $title; ?>
    </a>
<?php else: ?>
    <div class="full-width-line" <?php echo $top_line_styles; ?>>
        <?php echo $title; ?>
    </div>
<?php endif; ?>