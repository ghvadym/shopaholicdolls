<?php

if (!is_top_header_available()) {
    return;
}

$top_header_text = get_field('top_header_text', 'options');

if (!$top_header_text) {
    return;
}

$header_top_text_color = get_field('top_header_text_color', 'options');
$header_top_bg = get_field('top_header_bg', 'options');
$header_top_link = get_field('top_header_link', 'options');

$selectors = '';
$style = '';

if ($header_top_text_color) {
    $selectors .= sprintf('color: %s;', $header_top_text_color);
}

if ($header_top_bg) {
    $selectors .= sprintf('background-color: %s;', $header_top_bg);
}

if ($selectors) {
    $style = sprintf('style="%s"', $selectors);
}

if (!empty($header_top_link)): ?>
    <a class="full-width-line" href="<?php echo $header_top_link['url'] ?? ''; ?>" <?php echo $style; ?>>
        <?php echo $top_header_text; ?>
    </a>
<?php else: ?>
    <div class="full-width-line" <?php echo $style; ?>>
        <?php echo $top_header_text; ?>
    </div>
<?php endif;