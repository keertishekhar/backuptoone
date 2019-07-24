(function() {
    let ajax = momCoreDemosAjax;

    Array.prototype.diff = function(a) {
        return this.filter(function(i) {return !a.includes(i);});
    };
/*---------------------------------
            comment
---------------------------------*/
    
Vue.component('carousel', {
  props: {
    options:  {
        type: Object,
        default: function() {
            return {
                items: 2,
                nav: true,
                navText: [ajax.angleLeft, ajax.angleRight],
                loop: false
            }
        }
      },
    loaded: Boolean
  },
  template: `
      <div class="mom-demos-carousel owl-carousel owl-theme"><slot></slot></div>
  `,

  mounted() {
      this.init();
  },
  watch: {
    loaded() {
        let c = this, 
            el = this.$el;
        (function($) {
            setTimeout(() => {
                $(el).owlCarousel('destroy');
                $(el).owlCarousel(c.options);
            }, 10);
        })(jQuery)
    }
  },
  methods: {
      init() {
        let c = this;
        (function($) {
            $(c.$el).owlCarousel(c.options);
        })(jQuery);
      }
  }
});

    let app = new Vue({
        el: '#mom-demos-app',
        data: {
            demos: '',
            currentDemo: ajax.currentDemo,
            currentInstalled: false,
            loading: false,
            loaded: false,
            progress: 0,
            popUp: false,
            plugins: ajax.plugins,
            requiredPlugins: false,
            recommendedPlugins: false
        },
        computed: {
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                axios
                .get(ajax.api_url)
                .then(response => {
                    demos = response.data.demos;
                    this.demos = demos;
                    this.loaded = true;
                })
            },
            thumb(demo) {
                return demo.general ? demo.general.settings.thumb : ''; 
            },
            previewLink(demo) {
                return demo.general ? demo.general.settings.preview_link : ''; 
            },

            getRequiredPlugins(demo) {
                plugins = demo.general ? demo.general.settings.required_plugins : ''; 
                plugins = plugins ? plugins.split('\n') : '';
                arr = [];
                if (plugins) {
                    plugins.forEach(p => {
                    p = p.split(':');
                    obj = {};
                    obj.slug = p[0];
                    if (p[1]) {
                        obj.name = p[1];
                    }
                    if (p[2]) {
                        obj.source = p[2];
                    }
                    arr.push(obj);
                    });
                }
                return arr.length ? arr : '';
            },
            getRecommendedPlugins(demo) {
                plugins = demo.general ? demo.general.settings.recommended_plugins : ''; 
                plugins = plugins ? plugins.split('\n') : '';
                arr = [];
                if (plugins) {
                    plugins.forEach(p => {
                    p = p.split(':');
                    obj = {};
                    obj.slug = p[0];
                    if (p[1]) {
                        obj.name = p[1];
                    }
                    if (p[2]) {
                        obj.source = p[2];
                    }
                    arr.push(obj);
                    });
                }
                return arr.length ? arr : '';
            },
            checkRequiredPlugins(demo) {
                let plugins = this.plugins;
                let req = this.getRequiredPlugins(demo);
                if (!req) {
                    return [];
                }
                let reqSlugs = [];
                req.forEach(p => {
                    reqSlugs.push(p.slug);
                });
                
                let reqs = plugins.diff(reqSlugs);
                //console.log(reqs);
                
                //console.log(req.filter(p => reqs.includes(p.slug)));
                return req.filter(p => reqs.includes(p.slug));
            },
            getPluginName(p, type) {    
                p = p ? p.replace(/-/g, ' '): '';
                return p;
            },
            isInstalled(demo) {
                return demo.id === this.currentDemo;
            },
            installDemo(demo) {
                demo = demo || this.currentInstalled;
                
                this.closePopup();
                let c = this;
                let media = demo.media ? demo.media.children : false;
                document.querySelector('#'+demo.id+' .action-button.install').classList.add('stripped');
                c.progress = 0;
                c.loading = true;
                if (media) {
                    var chunk_size = 5;
                    var mediaGroups = media.map( function(e,i){ 
                        return i%chunk_size===0 ? media.slice(i,i+chunk_size) : null; 
                    }).filter(e => e);

                    //console.log(mediaGroups);
                    
                    //.fiter(function(e){ return e; });
                    c.installMedia(mediaGroups, '', demo);
                } else {
                    c.installContent(demo)
                }
              return;
            },
            installMedia(media, i, demo) {
                i = i || 0;
                let c = this,
                    length = media.length,
                    step = 100 / (media.length + 1);

                (function($) {
                    if( c.insall_demo_xhr != null ) {
                            c.insall_demo_xhr.abort();
                            c.insall_demo_xhr = null;
                    }
                  c.insall_demo_xhr = $.ajax({
                    type: "post",
                    url: ajax.url,
                    data: {
                      action : 'mom_install_media',
                      nonce: ajax.nonce,
                      media: JSON.stringify(media[i])
                    },
                    beforeSend: function() {
                        i += 1;
                        c.currentDemo = '';
                        setTimeout(() => {
                            c.progress += step / 3;
                        }, 1000);
                        $('#'+demo.id+' .action-button.install').addClass('stripped');
                    },
                    success: function(data) {
                           c.progress += step / 3;
                    },
                    complete: function(data) {
                        //console.log(`length: ${length} - i: ${i}`);
                        setTimeout(() => {
                            c.progress += step / 3;
                        }, 1000);
                        if (i < length) {
                            c.installMedia(media, i, demo);
                        } else {
                            c.installContent(demo)
                        }
                    }
                  });
                })(jQuery);
            },
            installContent(demo) {
                
                let c = this;
                (function($) {
                    if( c.insall_demo_xhr != null ) {
                            c.insall_demo_xhr.abort();
                            c.insall_demo_xhr = null;
                    }
                  c.insall_demo_xhr = $.ajax({
                    type: "post",
                    url: ajax.url,
                    data: {
                      action : 'mom_install_demo',
                      nonce: ajax.nonce,
                      demo: JSON.stringify(demo)
                    },
                    beforeSend: function() {
                        c.currentDemo = '';
                        setTimeout(() => {
                            c.progress += (100 - c.progress) / 2;
                        }, 1000);
                    },
                    success: function(data) {
                      c.progress = 100;
                    },
                    complete: function(data) {
                        setTimeout(() => {
                           c.currentDemo = demo.id;
                           c.loading = false;
                        }, 600);
                    }
                  });
                })(jQuery);
            },
                
            uninstallDemo() {
                let c = this;
                let demo = this.currentInstalled;
                this.closePopup();
                (function($) {
                  $.ajax({
                    type: "post",
                    url: ajax.url,
                    data: {
                      action : 'mom_uninstall_demo',
                      nonce: ajax.nonce,
                    },
                    beforeSend: function() {
                      $('#'+demo.id+' .action-button.uninstall').addClass('stripped');
                    },
                    success: function(data) {
                      $('#'+demo.id+' .action-button.uninstall').removeClass('stripped');
                    },
                    complete: function(data) {
                        c.currentDemo = '';
                        setTimeout(() => {
                            c.progress = 0;
                         }, 1000);
                    }
                  });
            })(jQuery);
            },
            openPopup(type, demo) {
                this.requiredPlugins = false;
                this.recommendedPlugins = false;
                if (type === 'install') {
                    let req = this.checkRequiredPlugins(demo);
                    if (req.length) {
                        type = 'require';
                        this.requiredPlugins = req;
                    }

                    let rec = this.getRecommendedPlugins(demo);
                    if (rec.length) {
                        this.recommendedPlugins = rec;
                    }
                }
                this.popUp = type;
                this.currentInstalled = demo;

            },
            closePopup() {
                this.popUp = false;
            }

        }
    })
})();