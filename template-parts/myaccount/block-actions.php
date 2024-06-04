<?php 
$value = $value ?? '';
?>

<div class="content__head_actions">
    <div class="content__head_action<?php echo $value ? ' active' : ''; ?>" data-action="edit">
        <div class="content__head_action_row">
            <?php echo get_local_img_html('myaccount/edit.svg', 'content__head_img', __('Edit', DOMAIN)); ?>
            <span>
                <?php esc_html_e('Edit', DOMAIN); ?>
            </span>
        </div>
    </div>
    <div class="content__head_action<?php echo !$value ? ' active' : ''; ?>" data-action="add">
        <div class="content__head_action_row">
            <?php echo get_local_img_html('myaccount/add.svg', 'content__head_img', __('Add', DOMAIN)); ?>
            <span>
                <?php esc_html_e('Add', DOMAIN); ?>
            </span>
        </div>
    </div>
    <div class="content__head_action red" data-action="remove">
        <div class="content__head_action_row">
            <?php echo get_local_img_html('myaccount/bin.svg', 'content__head_img', __('Remove', DOMAIN)); ?>
            <span>
                <?php esc_html_e('Remove', DOMAIN); ?>
            </span>
        </div>
    </div>
</div>