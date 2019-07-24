<div class="demos-backdrop" v-if="popUp" @click="closePopup"></div>
    <div class="demos-popup" v-if="popUp" :class="popUp">
        <template v-if="popUp === 'require'">
            <?php include dirname(__FILE__) . '/require.php'; ?>
        </template>

        <template v-if="popUp === 'install'">
                    <?php include dirname(__FILE__) . '/install.php'; ?>
        </template>
        
        <template v-if="popUp === 'uninstall'">
            <?php include dirname(__FILE__) . '/uninstall.php'; ?>
        </template>
    </div>