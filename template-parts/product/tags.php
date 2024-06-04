<?php

if (empty($post_id)) {
    return;
}

$tags = get_the_terms($post_id, 'product_tag');

if (empty($tags)) {
    return;
}

?>

<div class="product_tags__list">
    <?php foreach ($tags as $tag): ?>
        <div class="product_tag">
            <a href="<?php echo esc_url(get_term_link($tag, 'product_tag')); ?>">
                <?php echo esc_html($tag->name); ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>

