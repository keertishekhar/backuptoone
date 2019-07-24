<h3 class="title"><?php echo __('Install Demo', 'momizat-page-builder'); ?></h3>
<div class="content">
    <p>
    <?php echo __('This will import posts, pages, products, headers, menus, categories, images, and theme options.', 'momizat-page-builder'); ?>
    <strong class="blue"><?php echo __('Are you sure?', 'momizat-page-builder'); ?></strong>
    </p>
    <p>
    <strong class="green"><?php echo __('Good news:'); ?></strong> <?php echo __('You can completely uninstall this demo from your website, and roll back to your initial state before installing any demo via uninstall button.', 'momizat-page-builder'); ?>
    </p>
    <template v-if="recommendedPlugins">
            <p><?php echo __('This plugins is recommended in order to make demo better:', 'momizat-page-builder'); ?></p>
            <ul class="plugins">
                <li v-for="p in recommendedPlugins">
                    {{p.name || getPluginName(p.slug)}}
                </li>
            </ul>
        </template>

    <p class="note warning" v-if="currentDemo">
        <?php echo __('You already has another demo installed, it will completely uninstalled before install this demo', 'momizat-page-builder'); ?>
    </p>
    <div class="buttons">
        <a href="#" class="yes" @click.prevent="installDemo()"><?php echo __('Yes', 'momizat-page-builder'); ?></a>
        <a href="#" class="no" @click.prevent="closePopup"><?php echo __('No', 'momizat-page-builder'); ?></a>
    </div>
</div>
