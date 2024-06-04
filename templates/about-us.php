<?php
/*
* Template name: About us
*/
get_header();
$page_title   = get_the_title();
$blocks       = get_field('blocks') ?? [];
?>
<section class="about-title">
    <div class="container">
        <h1><?php echo $page_title; ?></h1>
    </div>
</section>
    <?php if(!empty($blocks)) : 
        foreach($blocks as $block): ?>
            <section class="about-us" <?php if(!empty($block['backgorund_color'])) :?> style="background-color: <?php echo $block['backgorund_color'];?>;" <?php endif;?>>
                <div class="container">
                    <div class="about-us__block <?php if($block['inverted_block']){ echo 'inverted'; } ;?>">
                        <?php if(!empty($block['image'] && isset($block['image']['url']))) : ?>
                        <div class="about-us__block--img">
                            <img src="<?php echo esc_url($block['image']['url']); ?>"
                                 alt="<?php echo isset($block['image']['alt']) ? esc_attr($block['image']['alt']) : '';?>"
                                 title="<?php echo isset($block['image']['alt']) ? esc_attr($block['image']['alt']) : '';?>">
                        </div>
                        <?php endif;?>
                        <div class="about-us__block--content">
                        <?php if (!empty($block['title'])) : ?>
                            <div class="about-us__block--title"><?php echo $block['title'];?></div>
                        <?php endif;?>
                        <?php if (!empty($block['content'])) : ?>
                            <div class="about-us__block--text"><?php echo $block['content'];?></div>
                        <?php endif;?>
                        <?php if(!empty($block['button'])) : ?>
                            <a class="btn-transparent" href="<?php echo esc_url($block['button']['url']); ?>"><?php echo esc_html($block['button']['title']); ?></a>
                        <?php endif;?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endforeach;
    endif; ?> 
<?php
get_footer();
?>
