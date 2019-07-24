<h3 class="title required"><?php echo __('Plugins Required', 'momizat-page-builder'); ?></h3>
    <div class="content">
        <p>
        <?php echo __('You need the following plugins to make this demo work properly:', 'momizat-page-builder'); ?>
        </p>
        <ul class="plugins">
            <li v-for="p in requiredPlugins">
                {{p.name || getPluginName(p.slug)}}
            </li>
        </ul>
        <div class="buttons">
        <a href="#" class="no" @click.prevent="closePopup"><?php echo __('Close', 'momizat-page-builder'); ?></a>
    </div>
    </div>