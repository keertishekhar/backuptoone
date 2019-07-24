<div id="mom-demos-app" class="wizard-demos" v-cloak>
<div class="loading-icon demo-loading" v-if="!loaded">
            <div style="width:100%;height:100%" class="lds-eclipse"><div></div></div> 
            <h3><?php echo __('loading Demos', 'meza'); ?></h3>   
        </div>

        <carousel class="demo-packs demo-setup" :loaded="loaded">
                <div :id="demo.id" class="pack" v-for="demo in demos" :class="{'active': currentInstalled === demo}">
                    <div class="pack-content">
                        <div class="demo-thumb" v-if="thumb(demo)">
                            <img :src="thumb(demo)" alt="">
                            <div class="bar" :class="{'installed' : isInstalled(demo)}">
                                    <span class="installed-label" v-if="isInstalled(demo)">
                                        <?php echo mom_demo_icons('check'); ?>Installed
                                    </span>
                                    <span class="progress stripped" v-if="currentInstalled === demo"
                                    :style="{width: (progress || 0) + '%'}">
                                </span>
                            </div>
                        </div>
                        <div class="pack-info">
                            <h3 class="pack-name">{{demo.title}}</h3>
                            <span class="pack-cats">{{demo.categories.join(', ')}}</span>
                        </div>
                        <div class="buttons">
                                <!-- <a :href="previewLink(demo)" v-if="previewLink(demo)" target="_blank" class="action-button preview"><?php echo mom_demo_icons('eye'); ?><?php echo __('Preview', 'meza'); ?></a> -->
                                <a href="#" class="action-button install" @click.prevent="openPopup('install', demo)" v-if="!currentDemo || !isInstalled(demo)"><?php echo mom_demo_icons('install'); ?><?php echo __('Install', 'meza'); ?></a>
                                <a href="#" class="action-button uninstall" @click.prevent="openPopup('uninstall', demo)" v-if="isInstalled(demo)"><?php echo mom_demo_icons('uninstall'); ?><?php echo __('Uninstall', 'meza'); ?></a>
                            </div>
                    </div>
                </div>
        </carousel>
    <?php include dirname(__FILE__) . '/parts/popup.php'; ?>
    </div>
