<?php 
if (empty($providers)) {
    return;
}
?>

<div class="auth__social_links">
    <?php foreach ($providers as $provider):
        if (empty($provider['url'])) {
            continue;
        } ?>
        <a href="<?php echo $provider['url']; ?>"
           class="auth__social_link">
            <img src="<?php echo get_local_img_url('social-' . $provider['id'] . '.svg'); ?>"
                 alt="<?php echo $provider['id']; ?>"
                 title="<?php echo $provider['id']; ?>">
        </a>
    <?php endforeach; ?>
</div>