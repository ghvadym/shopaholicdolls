<?php

$brands_taxonomy = get_taxonomy('pwb-brand');

if (empty($brands_taxonomy)) {
    return;
}

if (empty($most_wanted_brands)) {
    return;
}
?>

<ul class="sub-menu brands-mobile">
    <?php $most_wanted_brands = array_chunk($most_wanted_brands, 4, true);
    foreach ($most_wanted_brands as $brand): ?>
        <div class="menu-item">
            <?php foreach ($brand as $brand_id => $total_sales):
                $brand_term = get_term_by('id', $brand_id, 'pwb-brand'); ?>
                <a href="<?php echo get_term_link($brand_id); ?>">
                    <?php echo $brand_term->name; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</ul>