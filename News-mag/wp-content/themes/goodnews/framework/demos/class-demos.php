<?php
if(! class_exists('mom_core_demos')) {
    class mom_core_demos
    {
        public function __construct()
        {
    		add_action('wp_ajax_mom_install_media', array(&$this, 'install_media'));
    		add_action('wp_ajax_mom_install_demo', array(&$this, 'install'));
            add_action('wp_ajax_mom_uninstall_demo', array(&$this, 'uninstall_ajax'));
            
            add_action('admin_enqueue_scripts', array(&$this,'assets'));
            
        }
        public function load_assets() {
            wp_enqueue_style('Momizat-demos-css', get_template_directory_uri() . '/framework/demos/css/demos.css');
            wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/framework/demos/css/owl.carousel.min.css');

            wp_enqueue_script( 'vue', get_template_directory_uri() . '/framework/demos/js/vue.js', false, '1.0', true );
            wp_enqueue_script( 'axios', get_template_directory_uri() . '/framework/demos/js/axios.min.js', false, '1.0', true );
            wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/framework/demos/js/owl.carousel.min.js', false, '1.0', true );
            wp_register_script( 'Momizat-demos-js', get_template_directory_uri() . '/framework/demos/js/demos.js', array('jquery'), '1.0', true );
            wp_localize_script( 'Momizat-demos-js', 'momCoreDemosAjax', array(
                'url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'demos_ajax-nonce' ),
                'api_url' => mom_demos_api_url(),
                'currentDemo' => get_theme_mod('mom_current_installed_demo', false),
                'plugins' => $this->get_active_plugins(),
                'angleRight' => mom_demo_icons('angle-right'),
                'angleLeft' => mom_demo_icons('angle-left'),
            ));
            wp_enqueue_script('Momizat-demos-js');
        }
        public function assets() {
            //demos scripts & styles
            if ( isset($_GET['page']) && $_GET['page'] == 'momizat-demos') {
                    $this->load_assets();
            }
    }
    public function get_active_plugins() {
        $plugins = get_option('active_plugins', array());
        $p = array();
        foreach ($plugins as $plugin) {
            $plugin_slug = explode('/', $plugin);
            if(isset($plugin_slug[0])) {
                $p[] = $plugin_slug[0];
            }
        }

        return $p;
    }
            
    public function set_current_demo($id) {
        set_theme_mod('mom_current_installed_demo', $id);
    }
    
    /*---------------------------------
                utils
    ---------------------------------*/
    public function is_serial($string) {
        return (@unserialize($string) !== false || $string == 'b:0;');
    }
    /*---------------------------------
                Options
    ---------------------------------*/
    public function decode_options($options) {
        if (!$options) {
            return;
        }
        $options = unserialize(base64_decode($options));
        return $options;
    }
    public function add_options($options, $restore = false) {
        $options = $this->decode_options($options);
        if (!$options) {
            return;
        }
        //print_r($options);
        //import theme mods
        if (isset($options['mods']) && is_array($options['mods']) ) {
            foreach ( $options['mods'] as $key => $val ) {
                //don't use theme menu locations id's
                if ($key == 'nav_menu_locations') {
                    $menus = get_theme_mod('mom_demo_menus', array());
                    $new_val = array();
                    if (is_array($val)) {
                        foreach($val as $k => $id) {
                            $new_menu = array_filter($menus, function($value) use ($id){
                                            return $value['demo_id'] == $id;
                                        });
                            reset($new_menu);
                            $first_key = key($new_menu);
                            $new_val[$k] = $first_key;	
                        }
                        set_theme_mod( $key, $new_val );
                    } else {
                        set_theme_mod( $key, array());
                    }
                    continue;
                }
                set_theme_mod( $key, $val );
            }
        }
        //import custom options
        if (isset($options['options']) && is_array($options['options']) ) {
            foreach ( $options['options'] as $key => $val ) {
                update_option($key, $val);
            }
        }
    }
    public function add_custom_options($options) {
        if (!$options) {
            return;
        }

        $options = isset($options['children']) ? $options['children'] : array();

        foreach ($options as $option) {
            $settings = isset($option['settings']) ? $option['settings'] : '';
            $name = isset($settings['name']) ? $settings['name'] : '';
            $type = isset($settings['type']) ? $settings['type'] : '';
            $value = isset($settings['value']) ? base64_decode($settings['value']) : '';
            if ($type == 'json') {
                $value = $value ? json_decode( $value, true ) : '';
            }
            update_option($name, $value);
        }
    }

    /*---------------------------------
                Media
    ---------------------------------*/
    public function remove_media() {
        $args = array(
            'post_type' => array('attachment'),
            'meta_key' => 'mom_imported_with_demo',
            'post_status' => 'inherit',
            'posts_per_page' => -1
        );
        $query = new WP_Query( $args );
        if (!empty($query->posts)) {
            foreach ($query->posts as $post) {
                wp_delete_post($post->ID, true);
            }
       }            
    }

    public function add_media($media) {
        if ($media) {
            foreach ($media as $image) {
                $this->add_image($image);
            }
        }        
    } 
    public function add_image($image, $parent_post_id = null) {
        if( !class_exists( 'WP_Http' ) ) {
            include_once( ABSPATH . WPINC . '/class-http.php' );
        }
        $settings = isset($image['settings']) ? $image['settings'] : '';
        $url = isset($settings['image']) ? $settings['image'] : '';
        $sample = isset($settings['sample_image']) ? $settings['sample_image'] : '';
        $id = $this->get_image_id_by_url($url);
        if ($sample) {
            return;
        }
        if (!$url) {
            return;
        }
        //check if image exist 
        $attachment_args = array(
            'posts_per_page' => 1,
            'post_type'=> 'attachment',
            'post_status' => 'inherit',
            'meta_key' => 'mom_import_image_id',
            'meta_value' => $id,
        );
        $attachment_check = new Wp_Query( $attachment_args );
    
        // if attachment exists, reuse and update data
        if ( $attachment_check->have_posts() ) {
            return;
        }
        $http = new WP_Http();
        $response = $http->request( $url );
        if( $response['response']['code'] != 200 ) {
            return false;
        }
        $upload = wp_upload_bits( basename($url), null, $response['body'] );
        if( !empty( $upload['error'] ) ) {
            return false;
        }
        $file_path = $upload['file'];
        $file_name = basename( $file_path );
        $file_type = wp_check_filetype( $file_name, null );
        $attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
        $wp_upload_dir = wp_upload_dir();
        $post_info = array(
            'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title'     => $attachment_title,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        // Create the attachment
        $attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );
        // Include image.php
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id,  $attach_data );
        update_post_meta($attach_id, 'mom_import_image_id', $id);
        update_post_meta($attach_id, 'mom_imported_with_demo', true);
        return $attach_id;
    }
    public function get_image_id_by_url($url) {
        preg_match('/uploads\/(.*)/', $url, $id);
        $id = isset($id[1]) ? $id[1] : 'NA';
        $id = str_replace('/', '_',$id);
        return $id;
    }
    public function get_image_by_mom_id($id) {
        if (!$id) {
            return;
        }
        $type = 'id';
        if (is_array($id)) {
            $id = isset($id[1]) ? $id[1] : '';
        }
        if (strpos($id, '(url)') !== false) {
            $type = 'url';
            $id = str_replace('(url)', '', $id);
        }
        if (strpos($id, '|') !== false) {
            $id = explode('|', $id);
            $id = isset($id[1]) ? $id[1] : $id[0];
        }
        $args = array(
            'post_type' => array('attachment'),
            'meta_key' => 'mom_import_image_id',
            'meta_value' => $id,
            'post_status' => 'inherit',
            'posts_per_page' => 1
        );
        $query = new WP_Query( $args );
        if (!empty($query->posts)) {
            if ($type == 'url') {
                return wp_get_attachment_image_url($query->posts[0]->ID, 'full');
            } else {
                return $query->posts[0]->ID;
            }
       }
    }

    /*---------------------------------
                meta
    ---------------------------------*/
    public function add_meta($meta) {
        if (!$meta || !is_array($meta)) {
            return;
        }
        $meta_input = array();
        $prefix = '';
        foreach ($meta as $m) {
            $name = isset($m['settings']['name']) ? $m['settings']['name'] : '';
            $mom = isset($m['settings']['mom']) ? $m['settings']['mom'] : '';
            $value = isset($m['content']) ? $m['content'] : '';
            if($mom && defined('MOMIZAT_PREFIX')) {
                $prefix = MOMIZAT_PREFIX;
            }
    
            if ($name) {
                $meta_input[$prefix.$name] = $value;
            }
        }
        return $meta_input;
    }
    /*---------------------------------
                Add categories
    ---------------------------------*/
        public function sluglize($str) {
            $str = strtolower($str);
            $str = str_replace(' ', '-', $str);
            return $str;
        }
        public function get_tax($type) {
            $taxonomy = 'category';
            if ($type == 'products' || $type == 'product') {
                $taxonomy = 'product_cat';
            }

            return $taxonomy;
        }
        public function remove_terms() {
            $terms = get_theme_mod('mom_demo_terms', array());
            foreach ($terms as $tax => $ids) {
                if (is_array($ids)) {
                    foreach ($ids as $id) {
                        wp_delete_term( $id, $tax );
                    }
                }
            }
        }        
    public function add_terms($terms_group) {
        if (!$terms_group) {return;}
            $terms_group = isset($terms_group['children']) ? $terms_group['children'] : '';
            $demo_terms = get_theme_mod('mom_demo_terms', array());
            $this_terms = array();
            // if ($default_category) {
            //     $default_cat = get_term_by('slug', 'uncategorized', $taxonomy);
            //     $default_cat_id = $default_cat->term_id;
            //     wp_update_term($default_cat_id, $taxonomy, array(
            //         'name' => $default_category,
            //         'slug' => $this->sluglize($default_category)
            //     ));
            // }
            if (is_array($terms_group)) {
                foreach($terms_group as $tax) {
                    $settings = isset($tax['settings']) ? $tax['settings'] : '';
                    $taxonomy = isset($settings['type']) ? $settings['type'] : '';
                    $terms = isset($tax['children']) ? $tax['children'] : '';
                    $this_terms[$taxonomy] = array();
                    if (is_array($terms)) {
                        foreach($terms as $term) {
                            $name = isset($term['settings']['name']) ? $term['settings']['name'] : '';
                            $slug = isset($term['settings']['slug']) ? $term['settings']['slug'] : '';
                            $parent = isset($term['settings']['parent']) ? $term['settings']['parent'] : '';
                            $desc = isset($term['settings']['desc']) ? $term['settings']['desc'] : '';
                            $options = isset($term['settings']['options']) ? base64_decode($term['settings']['options']) : '';
                            $new_term = wp_insert_term($name, $taxonomy, array(
                                            'slug' => $slug ? $slug : $this->sluglize($name),
                                            'parent' => $parent,
                                            'description' => $desc
                                        ));
                            $new_term_id = is_array($new_term) && isset($new_term['term_id']) ? $new_term['term_id'] : '';
                            if ($new_term_id) {
                                $this_terms[$taxonomy][] = $new_term_id;
                                if ($options) {
                                    $options = unserialize($options);
                                    if (is_array($options)) {
                                        //print_r($options);
                                        foreach ($options as $k => $v) {
                                            update_term_meta($new_term_id, $k, $v);
                                        }
                                    }
                                }
                            }                            
                        }
                    }
                }
            }
            if (!empty($this_terms)) {
                $demo_terms = $this_terms;
                set_theme_mod('mom_demo_terms', $demo_terms);   
            }
        }
    /*---------------------------------
    Add post (post, page, product)
    ---------------------------------*/
    public function add_post($post, $default_content = '', $default_excerpt = '', $post_type = '') {
        $settings = isset($post['settings']) ? $post['settings'] : '';
        $type = isset($post['type']) ? $post['type'] : '';
        $type = $post_type ? $post_type : $type;
        $title = isset($settings['name']) ? $settings['name'] : '';
        $content = isset($settings['the_content']) ? base64_decode($settings['the_content']) : $default_content;
        $excerpt = isset($settings['excerpt']) ? base64_decode($settings['excerpt']) : $default_excerpt;
        $category = isset($settings['category']) ? $settings['category'] : '';
        $meta = isset($post['children']) ? $post['children'] : '';
        $options = isset($post['settings']['options']) ? base64_decode($post['settings']['options']) : '';
        //check content for image
        $content = preg_replace_callback("/!___(.*?)___!/", array(&$this, 'get_image_by_mom_id'), $content);
        if (!post_type_exists($type)) {
            return;
        }
        //if category
        $cats = array();
        if ($category) {
            $category = explode(',', $category);
            $taxonomy = $this->get_tax($type);
            foreach ($category as $cat) {
                $term = get_term_by('slug', $cat, $taxonomy);
                if ($term) {
                    $cats[] = $term->term_id;
                }
            }
        }
        
        $args = array(
            'post_title'    => wp_strip_all_tags( $title ),
            'post_content'  => $content,
            'post_excerpt'  => $excerpt,
            'post_type'     => $type,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_category' => $cats
          );
          $meta_input = array();
          if ($meta) {
            $meta_input = $this->add_meta($meta);
           }          
        $meta_input['mom_imported_with_demo'] = 1;
        $args['meta_input'] = $meta_input;
        $new_post = wp_insert_post( $args );
        //if page 
        if ($type == 'page') {
            $is_home = isset($settings['home_page']) ? $settings['home_page'] : false;
            $template = isset($settings['template']) ? $settings['template'] : false;
            if ($template) {
                update_post_meta($new_post, '_wp_page_template', $template);
            }
            if ($is_home) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $new_post);
            }
        }

        //if has feature image
        $feature_image = isset($settings['feature_image']) ? $settings['feature_image'] : false;
        if ($feature_image) {
            $feature_image_id = $this->get_image_id_by_url($feature_image);
            set_post_thumbnail( $new_post, $this->get_image_by_mom_id($feature_image_id) );
        }

        //gallery for products
        if ($type == 'product') {            
            $product = function_exists('wc_get_product') ? wc_get_product( $new_post ) : '';
            $featured = isset($settings['featured']) ? $settings['featured'] : false;
            $out_of_stock = isset($settings['out_of_stock']) ? $settings['out_of_stock'] : false;
            $price = isset($settings['price']) ? $settings['price'] : '99.00';
            $sale_price = isset($settings['sale_price']) ? $settings['sale_price'] : '';
            $gallery = isset($settings['gallery']) ? $settings['gallery'] : false;
            if ($product) {
                if ($cats) {
                    $product->set_category_ids($cats);
                }
                if ($featured) {
                    $product->set_featured($featured);
                }
                if ($out_of_stock) {
                    $product->set_stock_status('outofstock');
                }
                if ($price) {
                    $product->set_regular_price($price);
                }
                if ($sale_price) {
                    $product->set_sale_price($sale_price);
                }
                if ($gallery) {
                    $gallery_ids = array();
                    $gallery = explode(',', $gallery);
                    foreach ($gallery as $mom_id) {
                        $mom_id = $this->get_image_id_by_url($mom_id);
                        $gallery_ids[] = $this->get_image_by_mom_id($mom_id);
                    }
                    $product->set_gallery_image_ids($gallery_ids);
                }



                $product->save();
            }

        }

        //if has options
        if ($options) {
            $options = @unserialize($options);
            if (is_array($options)) {
                //  print_r($options);
                foreach ($options as $k => $v) {
                   if ($this->is_serial($v)) {
                        $v = unserialize($v);
                    }
                    update_post_meta($new_post, $k, $v);
                }
            }
        }
    }
    /*---------------------------------
     content (post, page, product)
    ---------------------------------*/
    public function remove_content() {
        $post_type = array('post','page', 'product', 'header', 'block');
        $custom_post_types = get_theme_mod('mom_demo_post_types', array());
        $post_type = array_merge($post_type, $custom_post_types);
        $args = array(
            'post_type' => $post_type,
            'meta_key' => 'mom_imported_with_demo',
            'posts_per_page' => -1
        );
        $query = new WP_Query( $args );
        if (!empty($query->posts)) {
            foreach ($query->posts as $post) {
                wp_delete_post($post->ID, true);
            }
       }
    }
    public function add_content($posts) {
        if (!$posts) {
            return;
        }
        $children = isset($posts['children']) ? array_reverse($posts['children']) :array();
        $settings = isset($posts['settings']) ? $posts['settings'] : '';
        $type = isset($settings['type']) ? strtolower($settings['type']) : '';
        $content = isset($settings['the_content']) ? base64_decode($settings['the_content']) : '';
        $excerpt = isset($settings['excerpt']) ? base64_decode($settings['excerpt']) : '';
        //add post type
        if ($type) {
            $post_types = get_theme_mod('mom_demo_post_types', array());
            $post_types[] = $type;
            set_theme_mod('mom_demo_post_types', $post_types);
        }

        if (!empty($children)) {
            foreach ($children as $post) {
                $this->add_post($post, $content, $excerpt, $type);
            }
        }
    }
    /*---------------------------------
                Menus
    ---------------------------------*/
    public function remove_menus() {
        $menus = get_theme_mod('mom_demo_menus', array());
        //print_r($menus);
        foreach ($menus as $id => $name) {
            wp_delete_nav_menu($id);
        }
    }
    public function add_menus($menus) {
        if (!$menus) {
            return;
        }
        $type = isset($menus['type']) ? $menus['type'] :'';
        $children = isset($menus['children']) ? $menus['children'] :array();
        if (!empty($children)) {
            foreach ($children as $menu) {
                $this->add_menu($menu);
            }
        }
    }
    public function add_menu($menu) {
        $settings = isset($menu['settings']) ? $menu['settings'] : '';
        $name = isset($settings['name']) ? $settings['name'] : '';
        $id = isset($settings['id']) ? $settings['id'] : '';
        $children = isset($menu['children']) ? $menu['children'] :array();
        $menus = get_theme_mod('mom_demo_menus', array());
        $new_menu = wp_create_nav_menu($name);
        if (!empty($children) && $new_menu) {
            foreach ($children as $menu_item) {
                $this->add_menu_item($menu_item, $new_menu);
            }
        }
        if (!is_wp_error($new_menu) && $new_menu) {
            $menus[$new_menu] = array('id' => $new_menu, 'name' => $name, 'demo_id' => $id);
            set_theme_mod('mom_demo_menus', $menus);
        }
    }
    public function add_menu_item($menu_item, $menu, $parent = 0) {
        $children = isset($menu_item['children']) ? $menu_item['children'] :array();
        $settings = isset($menu_item['settings']) ? $menu_item['settings'] : '';
        $link_for = isset($settings['link_for']) ? $settings['link_for'] : 'custom';
        $page = isset($settings['page']) ? $settings['page'] : '';
        $post = isset($settings['post']) ? $settings['post'] : '';
        $product = isset($settings['product']) ? $settings['product'] : '';
        $category = isset($settings['category']) ? $settings['category'] : '';
        $product_cat = isset($settings['product_cat']) ? $settings['product_cat'] : '';
        $custom = isset($settings['custom']) ? $settings['custom'] : '#';
        $label = isset($settings['label']) ? $settings['label'] : '';
        $title = isset($settings['title']) ? $settings['title'] : '';
        $target = isset($settings['target']) ? $settings['target'] : '';
        $class = isset($settings['class']) ? $settings['class'] : '';
        $desc = isset($settings['desc']) ? $settings['desc'] : '';
        $options = isset($settings['options']) ? base64_decode($settings['options']) : '';
        $args = array();
        $args['menu-item-title'] = $label;
        $args['menu-item-status'] = 'publish';
        $args['menu-item-parent-id'] = $parent;
        $args['menu-item-description'] = $desc;
        $args['menu-item-attr-title'] = $title;
        $args['menu-item-target'] = $target;
        $args['menu-item-classes'] = $class;

        //get real data 
        if ($link_for == 'page' && $page) {
            $page_obj = get_page_by_title($page);
            if ($page_obj) {
                $args['menu-item-object-id'] = $page_obj->ID;
                $args['menu-item-object'] = 'page';
                $args['menu-item-type'] = 'post_type';
            }
        }
        if ($link_for == 'post' && $post) {
            $post_obj = get_page_by_title($post, OBJECT, 'post');
            if ($post_obj) {
                $args['menu-item-object-id'] = $post_obj->ID;
                $args['menu-item-object'] = 'post';
                $args['menu-item-type'] = 'post_type';
            }
        }
        if ($link_for == 'product' && $product) {
            $product_obj = get_page_by_title($product, OBJECT, 'product');
            if ($product_obj) {
                $args['menu-item-object-id'] = $product_obj->ID;
                $args['menu-item-object'] = 'product';
                $args['menu-item-type'] = 'post_type';
            }
        }
        if ($link_for == 'category' && $category) {
            $category_obj = get_term_by('slug', $this->sluglize($category), 'category');
            if ($category_obj) {
                $args['menu-item-object-id'] = $category_obj->term_id;
                $args['menu-item-object'] = 'category';
                $args['menu-item-type'] = 'taxonomy';
            }
        }
        if ($link_for == 'product_cat' && $product_cat) {
            $product_cat_obj = get_term_by('slug', $this->sluglize($product_cat), 'product_cat');
            if ($product_cat_obj) {
                $args['menu-item-object-id'] = $product_cat_obj->term_id;
                $args['menu-item-object'] = 'product_cat';
                $args['menu-item-type'] = 'taxonomy';
            }
        }

        if ($link_for == 'custom') {
                $args['menu-item-url'] = $custom;
                $args['menu-item-type'] = 'custom';
                $args['menu-item-title'] = $label ? $label : __('Menu item title', 'momizat-core');
            }
        if ($link_for == 'home') {
            $args['menu-item-url'] = home_url('/');
            $args['menu-item-type'] = 'custom';
            $args['menu-item-title'] = $label ? $label : __('Home', 'momizat-core');
        }

        //print_r($args);
        $new_menu_item = wp_update_nav_menu_item($menu, 0, $args);

        //handle item options
        if ($options) {
            $options = array_filter(json_decode(stripslashes($options), true), function($value) {return ($value !== null && $value !== false && $value !== '');});
            //print_r($options);
            if (is_array($options)) {
                foreach ($options as $k => $v) {
                    update_post_meta($new_menu_item, '_menu_item_'.$k, $v);
                }
            }
        }
        //handle childrens
        if (!empty($children) && $new_menu_item) {
            foreach ($children as $child_menu_item) {
                $this->add_menu_item($child_menu_item, $menu, $new_menu_item);
            }
        }
    }   
    /*---------------------------------
                Import Widgets
    ---------------------------------*/
    public function get_widget_id($widget, $b = false) {
        if ($b) {
            preg_match('/\[([0-9])\]+$/', $widget, $output);
        } else {
            preg_match('/-([0-9])+$/', $widget, $output);
        }
        return isset($output[1]) ? $output[1] : '';
    }

    public function get_widget_name($widget, $b = false) {
        if ($b) {
            return preg_replace( '/\[[0-9]\]+$/', '', $widget );
        }
        return preg_replace( '/-[0-9]+$/', '', $widget );
    }
    public function get_used_widgets($sidebars_widgets) {
        $used_widgets = array();
        if (is_array($sidebars_widgets) && !empty($sidebars_widgets)) {
            foreach ($sidebars_widgets as $sidebar) {
                if (is_array($sidebar) && !empty($sidebar)) {
                    foreach ($sidebar as $widget) {
                        $name = $this->get_widget_name($widget);
                        $used_widgets[$name] = get_option('widget_' . $name);
                    }
                }
            }
        }
        return $used_widgets;
    }
    public function add_custom_sidebars($sidebars) {
        update_option('mom_custom_sidebars', $sidebars);
    }
    public function import_widgets($options) {
        $options = $this->decode_options($options);
        $widgets = isset($options['widgets']) ? $options['widgets'] : '';
        $sidebars = isset($options['sidebars']) ? $options['sidebars'] : '';
        $custom_sidebars = isset($options['custom_sidebars']) ? $options['custom_sidebars'] : '';

        if ($custom_sidebars) {
            $this->add_custom_sidebars($custom_sidebars);
        }

        //add sideabrs widgets
        update_option('sidebars_widgets', $sidebars);

        if ($widgets) {
            //print_r($widgets);
            $widgets_arr = array();
            $demo_widgets = get_theme_mod('mom_demo_widgets', array());
            foreach ($widgets as $k => $v) {
                $name = $this->get_widget_name($k, true);
                $id = $this->get_widget_id($k, true);
                $widgets_arr[$name][$id] = $v;
            }
            //print_r($widgets_arr);
            foreach ($widgets_arr as $key => $val) {
              update_option($key, $val);
                $demo_widgets[] = $key;
            }
            set_theme_mod('mom_demo_widgets', $demo_widgets);
        }
    }
    public function remove_widgets() {
        $demo_widgets = get_theme_mod('mom_demo_widgets', array());
        foreach ($demo_widgets as $widget) {
            delete_option($widget);
        }
    }
    /*---------------------------------
                save histroy 
    ---------------------------------*/
    public function save_history($options, $custom_options = '') {
        $history = get_option('mom_demo_history', array());
        if (!empty($history)) {
            //echo 'history is full';
            return;
        }
        $history['time'] = time();
        $options = $this->decode_options($options);

        //get theme mods & options
        $mods = get_theme_mods();
        $moded_options = array();
        if (isset($options['options']) && !empty($options['options'])) {
            foreach ($options['options'] as $key => $value) {
                $moded_options[$key] = get_option($key);
            }
        }
        //get core options
        if (isset($options['core_options']) && !empty($options['core_options'])) {
            foreach ($options['core_options'] as $key => $value) {
                $moded_options[$key] = get_option($key);
            }
        }
        //get_custom_options 
        if ($custom_options) {
            $custom_options = isset($custom_options['children']) ? $custom_options['children'] : array();
            if (!empty($custom_options)) {
                foreach ($custom_options as $option) {
                    $st = isset($option['settings']) ? $option['settings'] : '';
                    $name = isset($st['name']) ? $st['name'] : '';
                    $option_val = get_option($name, '');
                    $moded_options[$name] = $option_val;
                }
            }
        }
        $history['mods'] = $mods;
        $history['options'] = $moded_options;

        //get widgets
        $sidebars_widgets = get_option('sidebars_widgets');
        $history['sidebars_widgets'] = $sidebars_widgets;
        $history['widgets'] = $this->get_used_widgets($sidebars_widgets);
        $custom_sidebars = get_option('mom_custom_sidebars', array());
        if (!empty($custom_sidebars)) {
            $history['custom_sidebars'] = $custom_sidebars;
        }

        //print_r($history);
        update_option('mom_demo_history', $history);
    }
    public function restore_history() {
        $history = get_option('mom_demo_history', array());
        $mods = isset($history['mods']) ? $history['mods'] :array();
        $options = isset($history['options']) ? $history['options'] :array();
        $sidebars_widgets = isset($history['sidebars_widgets']) ? $history['sidebars_widgets'] :array();
        $widgets = isset($history['widgets']) ? $history['widgets'] :array();
        //theme mods
        if (!empty($mods)) {
            foreach ($mods as $k => $v) {
                set_theme_mod($k, $v);
            }
        }

        //moded options
        if (!empty($options)) {
            foreach ($options as $k => $v) {
                if ($v == '') {
                    delete_option($k, $v);
                } else {
                    update_option($k, $v);
                }
            }
        }

        //sidebars map
        if (!empty($sidebars)) {
            update_option('sidebars_widgets', $sidebars);
        }
        //widgets 
        if (!empty($widgets)) {
            foreach ($widgets as $k => $v) {
                update_option('widget_'.$k, $v);
            }
        }

    }

    /*---------------------------------
                Install
    ---------------------------------*/
    public function install_media() {
        $nonce = $_POST['nonce'];
        if ( ! wp_verify_nonce( $nonce, 'demos_ajax-nonce' ) )
        die ( '' );
        $media = json_decode(stripslashes($_POST['media']), true);
        $current_demo = get_theme_mod('mom_current_installed_demo');
        if ($current_demo) {
            $this-> uninstall();
        }

        $this->add_media($media);

        $response = array(
            'code' => '200',
            'message' => 'ok',
        );

        die(json_encode( $response ));        
    }
    public function install() {
        $nonce = $_POST['nonce'];
        if ( ! wp_verify_nonce( $nonce, 'demos_ajax-nonce' ) )
        die ( '' );
        $demo = json_decode(stripslashes($_POST['demo']), true);
        //get demo data
        $id = isset($demo['id']) ? $demo['id'] : '';
        $media = isset($demo['media']) ? $demo['media'] : '';
        $headers = isset($demo['headers']) ? $demo['headers'] : '';
        $blocks = isset($demo['blocks']) ? $demo['blocks'] : '';
        $pages = isset($demo['pages']) ? $demo['pages'] : '';
        $posts = isset($demo['posts']) ? $demo['posts'] : '';
        $products = isset($demo['products']) ? $demo['products'] : '';
        $post_types = isset($demo['post_types']) ? $demo['post_types'] : '';
        $menus = isset($demo['menus']) ? $demo['menus'] : '';
        $terms_group = isset($demo['terms_group']) ? $demo['terms_group'] : '';
        $widgets = isset($demo['widgets_import']) ? $demo['widgets_import'] : '';

        //get options from general
        $general = isset($demo['general']) ? $demo['general'] : '';
        $settings = isset($general['settings']) ? $general['settings'] : '';
        $options = isset($settings['options']) ? $settings['options'] : '';
        
        //custom options
        $custom_options = isset($demo['options']) ? $demo['options'] : '';
        
        $current_demo = get_theme_mod('mom_current_installed_demo');
        if ($current_demo) {
            $this-> uninstall();
        }

        $this->save_history($options, $custom_options);

        //demo methods
        $this->set_current_demo($id);
        set_theme_mod('mom_demo_terms', array());
        set_theme_mod('mom_demo_menus', array());
        set_theme_mod('mom_demo_post_types', array());
        

        $this->add_terms($terms_group);
        $this->add_content($pages);
        $this->add_content($posts);
        $this->add_content($products);
        $this->add_content($headers);
        $this->add_content($blocks);
        $this->add_menus($menus);

        //add post types
        if ($post_types && isset($post_types['children']) && is_array($post_types['children'])) {
            foreach ($post_types['children'] as $post_type) {
                $this->add_content($post_type);
            }
        }
        
        $this->add_options($options);
        //custom options
        $this->add_custom_options($custom_options);
        
        $this->import_widgets($options);

        $response = array(
            'code' => '200',
            'message' => 'ok',
        );

        die(json_encode( $response ));
    }

    /*---------------------------------
                Uninstall
    ---------------------------------*/
    public function uninstall() {
        //demo methods
        $this->set_current_demo('');
        $this->remove_terms();
        $this->remove_menus();
        $this->remove_widgets();
        $this->remove_content();
        $this->remove_media();

        //last thing remove options
        remove_theme_mods();

        //restore history
        $this->restore_history();

    }
    public function uninstall_ajax() {
        $nonce = $_POST['nonce'];
        if ( ! wp_verify_nonce( $nonce, 'demos_ajax-nonce' ) )
        die ( '' );
        
        $this->uninstall();

        $response = array(
            'code' => '200',
            'message' => 'ok',
        );
        die(json_encode( $response ));
    }

}
    new mom_core_demos();
}
if ( !function_exists('mpb_demos_page') ) {
    function mpb_demos_page() {
        include dirname(__FILE__) . '/demos.php';
    }
}
if ( !function_exists('mom_demo_icons') ) {
    function mom_demo_icons($icon) {
        $icons = array();
        $icons['qm'] = '<svg width="22px" height="22px" viewBox="0 0 22 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Design" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Momizat-Page-Builder-Library-Copy-3" transform="translate(-1347.000000, -97.000000)" fill="#A4ABB6" fill-rule="nonzero">
                    <g id="noun_help_1459310" transform="translate(1347.000000, 97.000000)">
                        <path d="M11,0 C4.92486775,1.20616822e-15 1.20616822e-15,4.92486775 0,11 C-1.20616822e-15,17.0751322 4.92486775,22 11,22 C17.0751322,22 22,17.0751322 22,11 C22,8.08261861 20.8410748,5.28472557 18.7781746,3.22182541 C16.7152744,1.15892524 13.9173814,1.20616822e-15 11,1.20616822e-15 Z M12.0864198,16.1944444 L10.0154321,16.1944444 L10.0154321,14.1574074 L12.1203704,14.1574074 L12.0864198,16.1944444 Z M14.0895062,10.3209877 C13.793928,10.6818781 13.4513813,11.0015883 13.0709877,11.2716049 L12.5617284,11.6790123 C12.3063089,11.8643551 12.1161478,12.1258266 12.0185185,12.4259259 C11.9484636,12.691789 11.9142105,12.9658138 11.9166667,13.2407407 L10.0493827,13.2407407 C10.0463642,12.6886821 10.1264802,12.1393151 10.287037,11.6111111 C10.5114281,11.1866744 10.8381672,10.8249275 11.2376543,10.558642 L11.7469136,10.1512346 C11.9037176,10.0381967 12.0412831,9.90063114 12.154321,9.74382716 C12.3332104,9.49718876 12.4283956,9.19973505 12.4259259,8.89506173 C12.4332974,8.54245481 12.3260788,8.1969725 12.1203704,7.91049383 C11.9166667,7.61625514 11.5432099,7.4691358 11,7.4691358 C10.545071,7.42680737 10.1030322,7.6348256 9.84567901,8.01234568 C9.62711556,8.34537898 9.50923653,8.73437979 9.50617284,9.13271605 L7.4691358,9.13271605 C7.51440329,7.7973251 7.97839506,6.85802469 8.86111111,6.31481481 C9.47230496,5.93823438 10.1805997,5.74935578 10.8981481,5.77160494 C11.8267181,5.73694762 12.7422468,5.99852723 13.5123457,6.51851852 C14.2219686,7.03737905 14.6186031,7.88182679 14.5648148,8.75925926 C14.5820678,9.31789161 14.4150444,9.86668307 14.0895062,10.3209877 Z" id="Shape"></path>
                    </g>
                </g>
            </g>
        </svg>';
        $icons['eye'] = '<svg width="21px" height="13px" viewBox="0 0 21 13" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Design" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Momizat-Page-Builder-Library-Copy-3" transform="translate(-271.000000, -474.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g id="Layout" transform="translate(250.000000, 178.000000)">
                        <g id="Group" transform="translate(21.000000, 296.000000)">
                            <path d="M10.5,0 C5.46559378,0 1.38679245,4.46034483 0.24472808,5.82758621 C-0.0815760266,6.20862069 -0.0815760266,6.76896552 0.24472808,7.17241379 C1.38679245,8.53965517 5.46559378,13 10.5,13 C15.5344062,13 19.6132075,8.53965517 20.7552719,7.17241379 C21.081576,6.79137931 21.081576,6.23103448 20.7552719,5.82758621 C19.6132075,4.46034483 15.5344062,0 10.5,0 Z M10.5,11.6551724 C6.21143174,11.6551724 2.50554939,7.68793103 1.4800222,6.5 C2.48224195,5.31206897 6.21143174,1.34482759 10.5,1.34482759 C14.7885683,1.34482759 18.4944506,5.31206897 19.5199778,6.5 C18.4944506,7.68793103 14.7885683,11.6551724 10.5,11.6551724 Z" id="Shape"></path>
                            <path d="M10.5,3 C8.56578947,3 7,4.56578947 7,6.5 C7,8.43421053 8.56578947,10 10.5,10 C12.4342105,10 14,8.43421053 14,6.5 C14,4.56578947 12.4342105,3 10.5,3 Z" id="Shape"></path>
                        </g>
                    </g>
                </g>
            </g>
        </svg>';

        $icons['install'] = '<svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Design" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Momizat-Page-Builder-Library-Copy-3" transform="translate(-408.000000, -472.000000)" fill="#FFFFFF">
                    <g id="noun_Download_464" transform="translate(408.000000, 472.000000)">
                        <path d="M5.22393,9.84555 C6.2339,11.05901 7.24404,12.27247 8.25401,13.48593 C8.32847,13.57501 8.41568,13.60748 8.5,13.6017 C8.58432,13.60748 8.67187,13.57518 8.74599,13.48593 C9.75596,12.27247 10.7661,11.05901 11.77607,9.84555 C11.96307,9.62132 11.85444,9.25157 11.53025,9.25157 C11.10627,9.25157 10.68263,9.25157 10.25882,9.25157 C10.25882,7.41642 10.25882,5.58127 10.25882,3.74595 C10.25882,3.55623 10.10055,3.39796 9.91083,3.39796 C8.97056,3.39796 8.02995,3.39796 7.08917,3.39796 C6.89945,3.39796 6.74118,3.55623 6.74118,3.74595 C6.74118,5.58127 6.74118,7.41642 6.74118,9.25157 C6.31737,9.25157 5.89373,9.25157 5.46975,9.25157 C5.14556,9.25157 5.03693,9.62132 5.22393,9.84555 Z" id="Path"></path>
                        <path d="M8.5,17 C13.19438,17 17,13.19438 17,8.5 C17,3.80562 13.19438,0 8.5,0 C3.80562,0 0,3.80562 0,8.5 C0,13.19438 3.80562,17 8.5,17 Z M8.5,1.36 C12.44332,1.36 15.64,4.55668 15.64,8.5 C15.64,12.44315 12.44332,15.64 8.5,15.64 C4.55685,15.64 1.36,12.44315 1.36,8.5 C1.36,4.55668 4.55685,1.36 8.5,1.36 Z" id="Shape" fill-rule="nonzero"></path>
                    </g>
                </g>
            </g>
        </svg>';
        $icons['uninstall'] = '<svg width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Design" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Momizat-Page-Builder-Library-Copy-3" transform="translate(-679.000000, -472.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g id="noun_Remove_1644582" transform="translate(679.000000, 472.000000)">
                        <path d="M9,0.4 C4.25783019,0.4 0.4,4.25783019 0.4,9 C0.4,13.7421698 4.25783019,17.6 9,17.6 C13.7421698,17.6 17.6,13.7421698 17.6,9 C17.6,4.25783019 13.7421698,0.4 9,0.4 Z M8.99999991,15.6264149 C5.34630746,15.6264149 2.37358491,12.6536923 2.37358491,8.99999991 C2.37358491,5.34630746 5.34630746,2.37358491 8.99999991,2.37358491 C12.6543973,2.37358491 15.6264149,5.34630746 15.6264149,8.99999991 C15.6264149,12.6536923 12.6543973,15.6264149 8.99999991,15.6264149 Z M13.2679245,9.26320755 C13.2679245,9.67695755 13.0340115,10.0132075 12.7461854,10.0132075 L5.78966366,10.0132075 C5.50096801,10.0132075 5.26792453,9.67695755 5.26792453,9.26320755 C5.26792453,8.84945755 5.50183757,8.51320755 5.78966366,8.51320755 L12.7461854,8.51320755 C13.0340115,8.51320755 13.2679245,8.84945755 13.2679245,9.26320755 Z" id="Shape"></path>
                    </g>
                </g>
            </g>
        </svg>';
        $icons['check'] = '<svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Design" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Momizat-Page-Builder-Library-Copy-3" transform="translate(-618.000000, -377.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g id="noun_Check_1808440" transform="translate(618.000000, 377.000000)">
                        <g id="Group">
                            <g id="a-link">
                                <path d="M8.5,15.3 C12.2555363,15.3 15.3,12.2555363 15.3,8.5 C15.3,4.7444637 12.2555363,1.7 8.5,1.7 C4.7444637,1.7 1.7,4.7444637 1.7,8.5 C1.7,12.2555363 4.7444637,15.3 8.5,15.3 Z M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z M7.65,9.84791844 L12.1489592,5.34895924 L13.3510408,6.55104076 L7.65,12.2520816 L3.64895924,8.25104078 L4.85104076,7.04895922 L7.65,9.84791844 Z" id="a"></path>
                            </g>
                        </g>
                    </g>
                </g>
            </g>
        </svg>';
        $icons['angle-left'] = '<svg width="8px" height="17px" viewBox="0 0 8 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Setup-Wizard-" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Demo-Content-Import-" transform="translate(-357.000000, -517.000000)" fill="#9CA1A9" fill-rule="nonzero">
                    <path d="M368.836292,522.135266 C368.615769,521.954911 368.257419,521.954911 368.036897,522.135266 L361.007733,527.89533 L353.964787,522.135266 C353.744264,521.954911 353.385915,521.954911 353.165392,522.135266 C352.944869,522.31562 352.944869,522.608696 353.165392,522.78905 L360.594253,528.864734 C360.704514,528.954911 360.842341,529 360.99395,529 C361.131777,529 361.283387,528.954911 361.393648,528.864734 L368.822509,522.78905 C369.056814,522.608696 369.056814,522.31562 368.836292,522.135266 Z" id="Shape-Copy" transform="translate(361.000000, 525.500000) scale(-1, 1) rotate(-90.000000) translate(-361.000000, -525.500000) "></path>
                </g>
            </g>
        </svg>';
        $icons['angle-right'] = '<svg width="8px" height="17px" viewBox="0 0 8 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Setup-Wizard-" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Demo-Content-Import-" transform="translate(-829.000000, -517.000000)" fill="#9CA1A9" fill-rule="nonzero">
                    <path d="M840.836292,522.135266 C840.615769,521.954911 840.257419,521.954911 840.036897,522.135266 L833.007733,527.89533 L825.964787,522.135266 C825.744264,521.954911 825.385915,521.954911 825.165392,522.135266 C824.944869,522.31562 824.944869,522.608696 825.165392,522.78905 L832.594253,528.864734 C832.704514,528.954911 832.842341,529 832.99395,529 C833.131777,529 833.283387,528.954911 833.393648,528.864734 L840.822509,522.78905 C841.056814,522.608696 841.056814,522.31562 840.836292,522.135266 Z" id="Shape" transform="translate(833.000000, 525.500000) rotate(-90.000000) translate(-833.000000, -525.500000) "></path>
                </g>
            </g>
        </svg>';
        
        //$icons['eye'] = '';

        return $icons[$icon] ? $icons[$icon] : '';
    }
}