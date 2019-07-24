<h3 class="title"><?php echo __('Uninstall Demo', 'momizat-page-builder'); ?></h3>
<div class="content">
    <p>
    <?php echo __('This will remove the demo content completely, including current theme options and roll back to initial state before you install any demo.', 'momizat-page-builder'); ?>
    <strong class="blue"><?php echo __('Are you sure?', 'momizat-page-builder'); ?></strong>
    </p>
    <p>

    <p class="note warning">
        <?php echo __('if you duplicate any demo page, post or product with Duplicate Post plugin or any similar plugin, it will be removed too.', 'momizat-page-builder'); ?><a href="http://bit.ly/2TcOBxC" class="blue"><?php echo __('Why and how avoid this?', 'momizat-page-builder'); ?></a>
    </p>

    <div class="buttons">
        <a href="#" class="yes" @click.prevent="uninstallDemo()"><?php echo __('Yes', 'momizat-page-builder'); ?></a>
        <a href="#" class="no" @click.prevent="closePopup"><?php echo __('No', 'momizat-page-builder'); ?></a>
    </div>
</div>
