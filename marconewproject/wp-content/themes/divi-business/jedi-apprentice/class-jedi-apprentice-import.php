<?php
/**
 * J.E.D.I. | Jerry's Easy Demo Import
 *
 * Class: JEDI_Apprentice_Import
 * This class provides the import features and functions.
 *
 * @package		jedi-apprentice
 * @author		Jerry Simmons <jerry@montereypremier.com>
 * @copyright	2017 Jerry Simmons
 * @license		GPL-2.0+
 **/

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'JEDI_Apprentice_Import' ) ) :
class JEDI_Apprentice_Import {

	public	$jedi_beacon;
	public	$jedi_apprentice_settings,
			$installer_name,
			$installer_slug,
			$export_folder,
			$export_data_folder,
			$export_media_folder,
			$installer_code_folder,
			$child_theme_style,
			$jedi_import_options;

	public	$jedi_import_media_status = array(),
			$jedi_imported_media = array(),			//
			$jedi_imported_posts = array(),  		//jedi_post_ids
			$jedi_imported_categories = array();	//jedi_category_ids

	public $jedi_apprentice_data_import = array();

	/**
	 * Checks if previous import failed before starting a new export
	 * Sets Export Directory Variables
	 **/
	public function __construct() {

		$this->jedi_beacon = new JEDI_Beacon;
		$this->jedi_import_options = get_option('jedi_import_options');
		$this->jedi_import_options['jedi_import_sysinfo'] = $this->jedi_beacon->get_system_info();

		/**
		 * Set Import Directory Variables
		 * All paths & urls include trailing slash
		 **/
		$this->jedi_apprentice_settings = get_option('jedi_apprentice_settings');
		$this->installer_name = $this->jedi_apprentice_settings['installer_name'];
		$this->installer_slug = $this->jedi_apprentice_settings['installer_slug'];
		$this->import_folder = JEDI_APPRENTICE_PATH . $this->installer_slug . '/';
		$this->import_data_folder = $this->import_folder . 'demo-data/';
		$this->child_theme_style = $this->jedi_apprentice_settings['installer_style'];

		if( $this->child_theme_style ) {
			$this->import_media_folder = get_stylesheet_directory_uri() . '/jedi-apprentice/demo-data/media/';
		} else {
			$this->import_media_folder = JEDI_APPRENTICE_URL . 'demo-data/media/';
		}

		/**
		 * Read Import Data from file & store in array
		 * $this->jedi_apprentice_data_import
		 **/
		$jedi_import_file = JEDI_APPRENTICE_PATH .'demo-data/jedi_data_export.dat';
		if( file_exists( $jedi_import_file ) ) {

			$this->jedi_apprentice_data_import = unserialize( file_get_contents( $jedi_import_file ) );

			if( false === $this->jedi_apprentice_data_import ) {
				$this->jedi_beacon->order_66( 'Unable to load import data' );
			}
		} else {
			$this->jedi_beacon->order_66( 'Unable to load settings file', $jedi_import_file );
		}

		/**
		 * Make Sure Divi Library Is Initiated
		 *
		 * @since 1.1-beta22, Divi 3.0.99
		 **/
		if( is_wp_error(get_terms( array( 'taxonomy' => 'layout_category', 'hide_empty' => true ) ) ) ) {
			if( function_exists( 'et_builder_register_layouts' ) ) {
				// For versions of Divi prior to 3.0.99
				et_builder_register_layouts();
			} else if( file_exists( get_template_directory() . '/includes/builder/feature/Library.php' ) ) {
				// For Divi 3.0.99 and later
				require_once( get_template_directory() . '/includes/builder/feature/Library.php' );
				ET_Builder_Library::instance();
			}
		}

		$this->do_import();

	} // END __construct()

	public function __destruct() {
	}


	/**
	 * Runs the full import process
	 **/
	public function do_import() {

		$jedi_status = get_option('jedi_status');
		if( 'Importing' != $jedi_status ) {
			update_option('jedi_status', 'Importing');

			$this->jedi_beacon->jedi_report('<h2>Running Import... </h2>', '', 0);
			$this->jedi_beacon->jedi_log( 'JEDI Apprentice Settings', $this->jedi_apprentice_settings );
			$this->jedi_beacon->jedi_log( 'JEDI Import Settings', $this->jedi_import_options );
		} else {
			$this->jedi_beacon->jedi_report('<h2>Warning: Previous Import Attempt Failed</h2>', '', 0);
			$this->jedi_beacon->jedi_report('Resetting import values.');
			$this->jedi_beacon->jedi_report('Suggestion: Enable Logging from the JEDI Master Support Page and try again.');
			update_option('jedi_status', 'Previous Import Failed');
			return;
		}

		/**
		 * Hook: jedi_before_import
		 **/
		do_action( 'jedi_before_import' );


		/**
		 * Run individual imports according to available Export Data
		 * and JEDI Apprentice options selected
		 **/
		if( $this->jedi_import_options['include_media'] ) {
			do_action( 'jedi_before_media_import' );

			// Prevent Thumbnails If Option Is Selected
			if( $this->jedi_import_options['prevent_thumbnails'] ) {
				add_filter( 'intermediate_image_sizes', array($this, 'kill_image_younglings' ), 999 );
			}

			if( $this->jedi_import_options['include_thumbnails'] ) {
				$this->import_media_with_thumbnails();
			} else {
				$this->import_media_without_thumbnails();
			}
			do_action( 'jedi_after_media_import', $this->jedi_imported_media['ids'] );
		}

		if( $this->jedi_import_options['include_posts'] ) {
			do_action( 'jedi_before_post_import' );
			$this->import_categories();
			$this->import_posts();
			$this->import_layout_terms();
			$this->update_divi_global_module_shortcodes();
			$this->update_phpfile_shortcodes();
			do_action( 'jedi_after_post_import', $this->jedi_imported_posts );
		}

		if( $this->jedi_import_options['include_homepage'] ) {
			if( $this->jedi_apprentice_settings['homepage_ID'] ) {
				$new_homepage = $this->jedi_imported_posts[$this->jedi_apprentice_settings['homepage_ID']];

				update_option( 'page_on_front', $new_homepage );
				update_option( 'show_on_front', 'page' );

				$this->jedi_beacon->jedi_report(
					'Homepage Set',
					get_the_title($new_homepage) . ' [Page ID: ' . $new_homepage . ']' );
			}
		}

		if( $this->jedi_import_options['include_options'] ) {
			$this->import_options();
		}

		if( $this->jedi_import_options['include_css'] ) {
			$this->import_css();
		}

		if( $this->jedi_import_options['include_menus'] ) {
			$this->import_menus();
		}

		if( $this->jedi_import_options['include_widgets'] ) {
			$this->import_widgets();
		}

		/**
		 * Set status as Imported
		 **/
		update_option('jedi_status', 'Imported');
		$this->jedi_beacon->jedi_report('<p>Import Complete!</p>', '', 0);

		do_action( 'jedi_after_import' );

	} // END do_import()


	/**
	 * Function to bypass thumbnail sizes
	 *
	 * @return empty array
	 **/
	public function kill_image_younglings( $data ) {
		return array();
	}


	/**
	 * Imports Each Media File To The Media Library
	 * (Keeps a record of imported media for updating links in posts)
	 *
	 * @uses media_sideload_image($file, $post_id, $desc, $return)
	 **/
	private function import_media_without_thumbnails() {

		$jedi_imported_media = array(
			'urls'	=> array(),
			'ids'	=> array() );
		$images_per_cycle = 5;
		$total_images = count( $this->jedi_apprentice_data_import['media'] );
		$imported_images = 0;
		ini_set( 'max_execution_time', 300 );

		$this->jedi_beacon->jedi_log( 'Total Images To Import', $total_images );
		$this->jedi_beacon->jedi_log( 'Batch Size', $images_per_cycle );

		/**
		 * Split Media Data into batches
		 **/
		do {
			set_time_limit(60);
			$media_batch = array_slice (
				$this->jedi_apprentice_data_import['media'],	// Array
				$imported_images,								// Offset
				$images_per_cycle,								// Length
				true );											// Preserve Keys

			/**
			 * Loop through Media Data & import each image
			 **/
			foreach ( $media_batch as $image ) {

				$new_url = media_sideload_image(
					$this->import_media_folder . basename( $image->guid ),	// File
					0,														// Post ID
					$image->post_title,										// Title
					'src' );												// Return

				if( is_wp_error( $new_url ) ) {
					$this->jedi_beacon->jedi_log( 'Unable to import media file', basename( $image->guid ) );
					continue;
				}

				$jedi_imported_media['urls'][] = array(
					'oldURL'	=> $image->guid,
					'newURL'	=> $new_url
				);
				$jedi_imported_media['ids'][$image->ID] = $this->get_attachment_id_from_src( $new_url );

				$this->jedi_beacon->jedi_log( 'Media Imported', basename( $image->guid ) );

			} // END foreach( $media_batch)

			$imported_images = $imported_images + count( $media_batch );

		} while( $imported_images < $total_images );

		/**
		 * Save Imported Image Info
		 **/
		$this->jedi_imported_media = $jedi_imported_media;
		$this->jedi_beacon->jedi_report( 'Media Files Imported',  count( $jedi_imported_media['ids'] ) );

	} // END import_media_without_thumbnails()


	/**
	 * Gets Post ID from an attacment URL
	 *
	 * @param string $image_src The URL of the image
	 * @return int $id
	 **/
	private function get_attachment_id_from_src( $image_src ) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;
	}

	private function import_media_with_thumbnails() {

		$jedi_imported_media = array(
			'urls'	=> array(),
			'ids'	=> array() );
		$images_per_cycle = 5;
		$total_images = count( $this->jedi_apprentice_data_import['media'] );
		$imported_images = 0;
		ini_set( 'max_execution_time', 300 );

		$this->jedi_beacon->jedi_log( 'Total Images To Import', $total_images );
		$this->jedi_beacon->jedi_log( 'Batch Size', $images_per_cycle );

		$wp_upload_dir = wp_upload_dir();
		$upload_dir = $wp_upload_dir['path'] . '/';

		$import_media_dir = JEDI_APPRENTICE_PATH . 'demo-data/media/';

		/**
		 * Split Media Data into batches
		 **/
		do {
			set_time_limit(60);
			$media_batch = array_slice (
				$this->jedi_apprentice_data_import['media'],	// Array
				$imported_images,								// Offset
				$images_per_cycle,								// Length
				true );											// Preserve Keys

			/**
			 * Loop through Media Data & import each image
			 **/
			foreach ( $media_batch as $image ) {

				// Copy Image
				$safe_jedi = copy( $import_media_dir . basename( $image->guid ),
					$upload_dir . basename( $image->guid ) );

				// Import Attachment Database Info
				$attachment = array(
					'post_title'	=> $image->post_title,
					'post_content'	=> '',
					'post_status'	=> $image->post_status,
					'post_mime_type'	=> $image->post_mime_type,
				);
				$newID = wp_insert_attachment(
					$attachment, 							// Attachment Info Array
					$upload_dir . basename( $image->guid ), // Filename
					0 										// Post Parent
				);

				// Import All Thumbnail Sizes
				$thumbnail_meta = $this->jedi_apprentice_data_import['thumbnails'][$image->ID];
				if( isset( $thumbnail_meta['sizes'] ) ) {
					foreach( $thumbnail_meta['sizes'] as $thumbnail_size ) {
						if( file_exists( $import_media_dir . $thumbnail_size['file'] ) ) {
							$safe_jedi = copy( $import_media_dir . $thumbnail_size['file'],
								$upload_dir . $thumbnail_size['file'] );
						}
					}
				}
				wp_update_attachment_metadata( $newID, $thumbnail_meta );


				$jedi_imported_media['urls'][] = array(
					'oldURL'	=> $image->guid,
					'newURL'	=> wp_get_attachment_url( $newID )
				);
				$jedi_imported_media['ids'][$image->ID] = $newID;

				$this->jedi_beacon->jedi_log( 'Media Imported', basename( $image->guid ) );

			} // END foreach( $media_batch)

			$imported_images = $imported_images + count( $media_batch );

		} while( $imported_images < $total_images );

		/**
		 * Save Imported Image Info
		 **/
		$this->jedi_imported_media = $jedi_imported_media;
		$this->jedi_beacon->jedi_report( 'Media Files Imported',  count( $jedi_imported_media['ids'] ) );

	} // END import_media_with_thumbnails()


	/**
	 * Loads the categories from the file and imports them
	 **/
	private function import_categories() {

		$jedi_category_ids = array();

		foreach( $this->jedi_apprentice_data_import['categories']['categories'] as $key=>$jedi_category ) {
			foreach ($jedi_category as $category) {
				$new_term = array (
					'description'	=> $category->description,
					'slug'			=> $category->slug,
				);
				$new_cat_id = wp_insert_term($category->name, $key, $new_term);
				if( true == is_wp_error($new_cat_id) ) {
					$jedi_category_ids[$category->term_id] = $new_cat_id->error_data['term_exists'];
				} else {
					$jedi_category_ids[$category->term_id] = $new_cat_id['term_id'];
				}
			}

			/**
			 * Assign Updated Category Parent
			 **/
			foreach ($jedi_category as $category) {
				if( 0 < $category->parent ) {
					$args = array( 'parent' => $jedi_category_ids[$category->parent] );
					wp_update_term( $jedi_category_ids[$category->term_id], $category->taxonomy, $args );
				}
			}

		} // END foreach jedi_apprentice_data_import['categories']['categories']

		$this->jedi_imported_categories = $jedi_category_ids;
		$this->jedi_beacon->jedi_log('Imported Categories: ' . count( $jedi_category_ids ), $jedi_category_ids );

	} // END import_categories()


	/**
	 * Loads the posts from the file and imports them
	 **/
	private function import_posts() {

		/**
		 * If Media were imported, load imported data
		 **/
		$process_media = false;
		if( count( $this->jedi_imported_media ) > 0 ) {
			$process_media = true;
			$jedi_imported_media = $this->jedi_imported_media;
			$jedi_update_image_urls = $jedi_imported_media['urls'];
			$jedi_update_image_ids = $jedi_imported_media['ids'];
		}

		$postmeta = $this->jedi_apprentice_data_import['postmeta'];
		$posts = $this->jedi_apprentice_data_import['posts'];

		$jedi_category_ids = $this->jedi_imported_categories;
		$jedi_post_categories = $this->jedi_apprentice_data_import['categories']['posts'];
		$jedi_post_ids = array();

		foreach($posts as $post) {
			$old_postID = $post->ID;
			$post->ID = 0;

			/**
			 * Skip this post of Post Type does not exist
			 **/
			if( !post_type_exists( $post->post_type ) && 'et_pb_layout' != $post->post_type ) {
				$this->jedi_beacon->jedi_log( 'Post Not Imported (Post Type Does Not Exist)',
					'Post ID: ' . $old_postID . ' - Post-Type: ' . $post->post_type );
				continue;
			}

			/**
			 * Update Image URLs In Content
			 *
			 * First checks if any Media were imported
			 **/
			if( $process_media ) {
				foreach($jedi_update_image_urls as $jedi_update_image_url) {
					$post->post_content = str_replace(
						$jedi_update_image_url['oldURL'],
						$jedi_update_image_url['newURL'],
						$post->post_content );
				}
			}


			/**
			 * Update Categories Referenced In Content ( ie: Portfolio Modules, Blog Modules, Etc. )
			 **/
			$element_start = strpos( $post->post_content, 'include_categories="' );

			while( false !== $element_start ) {
				$element_end = strpos( $post->post_content, '"', $element_start + 20 );
				$original_element = substr( $post->post_content, $element_start, $element_end - $element_start + 1 );

				$ref_categories = substr( $original_element, 20, strlen( $original_element ) - 21 );
				$ref_categories = explode( ',', $ref_categories );

				$new_categories = array();
				foreach( $ref_categories as $ref_category ) {
					if( isset( $jedi_category_ids[$ref_category] ) ) {
						$new_categories[] = $jedi_category_ids[$ref_category];
					}
				}
				$new_element = 'include_categories="' . implode( ',', $new_categories ) . '"';

				$post->post_content = substr_replace( $post->post_content, $new_element, $element_start, strlen($original_element) );

				$element_start = strpos( $post->post_content, 'include_categories="', $element_end + strlen( $new_element ) - strlen( $original_element ) );
			} // END while

/*			foreach( $jedi_category_ids as $key => $jedi_category_id ) {
				$post->post_content = str_replace(
					'include_categories="' . $key . '"',
					'include_categories="' . $jedi_category_id . '"',
					$post->post_content );
			}
*/

			/**
			 * Update Image IDs Referenced In Gallery Module
			 **/
			if( $process_media ) {
				$begin_search = 0;
				while( strpos( $post->post_content, 'gallery_ids="', $begin_search ) ) {
					$begin_search = strpos( $post->post_content, 'gallery_ids="', $begin_search );
					$gallery_module = array(
						'start'	=> $begin_search + 13,
						'end'	=> strpos( $post->post_content, '"', $begin_search+14 )
					);

					$module_oldIDs = substr(
						$post->post_content,
						$gallery_module['start'],
						$gallery_module['end'] - $gallery_module['start'] );

					$exploded_gallery = explode( ',', $module_oldIDs );

					foreach( $exploded_gallery as $key => $old_image_id ) {
						$exploded_gallery[$key] = strval($jedi_update_image_ids[intval($old_image_id)]);
					}

					$module_newIDs = implode( ',', $exploded_gallery );

					$post->post_content = str_replace( $module_oldIDs, $module_newIDs, $post->post_content );

					$begin_search = $gallery_module['end'] + 1;
				}
			}


			/**
			 * Filter: jedi_modify_post_content
			 **/
			if(has_filter('jedi_modify_post_content')) {
				$post->post_content = apply_filters( 'jedi_modify_post_content', $post->post_content );
			}

			/**
			 * Insert New Post from Import Data
			 **/
			$new_postID = wp_insert_post( $post, true );
			if( is_wp_error( $new_postID ) ) {
				$this->jedi_beacon->jedi_log( 'Failed to import post', $new_postID );
			}
			$jedi_post_ids[$old_postID] = $new_postID;

			/**
			 * Import Post Meta
			 **/
			if ( is_array( $postmeta[$old_postID] ) ) {
				foreach( $postmeta[$old_postID] as $key => $meta_value ) {
					if (null != $meta_value[0]) {
						update_post_meta( $new_postID, $key, $meta_value[0] );
					}
				}
			}

			/**
			 * Set Post Thumbnail
			 **/
			if( $process_media ) {
				if( isset( $jedi_update_image_ids[get_post_thumbnail_id($new_postID)] ) ) {
					set_post_thumbnail($new_postID, $jedi_update_image_ids[get_post_thumbnail_id($new_postID)]);
				}
			}

		} // foreach($posts as $post)

		/**
		 * Reassign Post Parent With Updated Post IDs
		 **/
		foreach($jedi_post_ids as $key => $jedi_post_id) {
			$post_parent = wp_get_post_parent_id( $jedi_post_id );
			if( $post_parent ) {
				$post_info = array(
					'ID'	=> $jedi_post_id,
					'post_parent'	=> $jedi_post_ids[$post_parent]
				);
				wp_update_post( $post_info );
			}
		}

		/**
		 * Reassign Post Categories Using Updated Post IDs
		 *
		 * Post_Tags Handling - Uses Term Name instead of ID
		 * Codex: If you want to add non-hierarchical terms like tags, then use names.
		 *
		 * @uses wp_set_post_terms()
		 **/
		foreach( $this->jedi_apprentice_data_import['categories']['posts'] as $oldID => $post_terms_info ) {

			wp_remove_object_terms( $jedi_post_ids[$oldID], 1, 'category' ); // Remove Uncategorized Category

			foreach( $post_terms_info['terms'] as $post_term ) {
				if( 'post_tag' == $post_term['taxonomy'] ) {
					$term_info = get_term( $jedi_category_ids[$post_term['term_id']], $post_term['taxonomy'] );
					wp_set_post_terms(
						$jedi_post_ids[$oldID], 					// Post ID
						$term_info->name,							// Term ID
						$post_term['taxonomy'], 					// Taxonomy
						true );										// Append?
				} else {
					wp_set_post_terms(
						$jedi_post_ids[$oldID], 					// Post ID
						$jedi_category_ids[$post_term['term_id']],	// Term ID
						$post_term['taxonomy'], 					// Taxonomy
						true );										// Append?
				}
			}
		}


		/**
		 * Store Old/New Post IDs
		 **/
		$this->jedi_imported_posts = $jedi_post_ids;

		$this->jedi_beacon->jedi_report( 'Posts & Pages Imported', count( $posts ) );

	} //END import_posts()


	/**
	 * Import and assign the terms for Divi Library Items
	 **/
	private function import_layout_terms() {

		$jedi_post_ids = $this->jedi_imported_posts;
		$layout_posts = $this->jedi_apprentice_data_import['layout_terms'];

		foreach( $layout_posts as $postID => $layout_post ) {
			foreach( $layout_post as $term_name => $layout_post_term ) {
				wp_set_post_terms( $jedi_post_ids[$postID], $layout_post_term, $term_name );
			}
		}
	}


	/**
	 * Update Global Module IDs In Posts
	 *
	 * Checks each post for global module shortcodes & global parent shortcodes
	 * Replaces exported Post IDs with imported Post IDs
	 **/
	function update_divi_global_module_shortcodes() {
		$jedi_post_ids = $this->jedi_imported_posts;

		 foreach( $jedi_post_ids as $jedi_post_id ) {
			$dgm_post = get_post($jedi_post_id);

			/**
			 * Global Module - Search & Replace
			 **/
			$hay_global = strpos( $dgm_post->post_content, 'global_module=' );
			while( false !== $hay_global ) {

				$dgm_post_id_start = strpos( $dgm_post->post_content, 'global_module="', $hay_global ) + 15;
				$dgm_post_id_end = strpos( $dgm_post->post_content, '"', $dgm_post_id_start );
				if( $dgm_post_id_start !== $dgm_post_id_end ) {

					$dgm_post_id = substr(
						$dgm_post->post_content,
						$dgm_post_id_start,
						$dgm_post_id_end - $dgm_post_id_start );

					if( isset( $jedi_post_ids[$dgm_post_id] ) ) {
						$old_shortcode = 'global_module="'.$dgm_post_id.'"';
						$new_shortcode = 'global_module="'.$jedi_post_ids[$dgm_post_id].'"';
						$dgm_post->post_content = str_replace($old_shortcode, $new_shortcode, $dgm_post->post_content);

						$this->jedi_beacon->jedi_log( 'Global Module Update: ',
							'[ '.$dgm_post_id.' ]--->[ '.$jedi_post_ids[$dgm_post_id].' ]' );
					} // END If

				} // END If

				// Search Rest Of Post?
				$hay_global = strpos( $dgm_post->post_content, 'global_module=', $dgm_post_id_end + 1 );

			} // END while


			/**
			 * Global Parent - Search & Replace
			 **/
			$hay_global = strpos( $dgm_post->post_content, 'global_parent=' );
			while( false !== $hay_global ) {

				$dgm_post_id_start = strpos( $dgm_post->post_content, 'global_parent="', $hay_global ) + 15;
				$dgm_post_id_end = strpos( $dgm_post->post_content, '"', $dgm_post_id_start );
				if( $dgm_post_id_start !== $dgm_post_id_end ) {

					$dgm_post_id = substr(
						$dgm_post->post_content,
						$dgm_post_id_start,
						$dgm_post_id_end - $dgm_post_id_start );

					if( isset( $jedi_post_ids[$dgm_post_id] ) ) {
						$old_shortcode = 'global_parent="'.$dgm_post_id.'"';
						$new_shortcode = 'global_parent="'.$jedi_post_ids[$dgm_post_id].'"';

						$dgm_post->post_content = str_replace($old_shortcode, $new_shortcode, $dgm_post->post_content);

						$this->jedi_beacon->jedi_log( 'Global Parent Update: ',
							'[ '.$dgm_post_id.' ]--->[ '.$jedi_post_ids[$dgm_post_id].' ]' );
					}

				}

				// Search Rest Of Post?
				$hay_global = strpos( $dgm_post->post_content, 'global_parent=', $dgm_post_id_end + 1 );

			} // END while


			wp_update_post($dgm_post);

		 } // END foreach $jedi_post_ids

	} // END update_divi_global_module_shortcodes()


	/**
	 * update_phpfile_shortcodes()
	 *
	 * Checks Child Theme PHP Files for Divi global module shortcodes
	 * Replaces exported Post IDs with imported Post IDs
	 **/
	private function update_phpfile_shortcodes() {

		// Update PHP File Shortcodes
		if( $this->child_theme_style ) {

			/**
			 * Get list of Child Theme PHP Files
			 **/
			$php_files = glob( get_stylesheet_directory() . "/*.php" );
			$this->jedi_beacon->jedi_log('Child Theme PHP Files', $php_files );

			/**
			 * Loop through PHP Files
			 **/
			foreach( $php_files as $php_file ) {
				$php_file_contents = file_get_contents( $php_file );

				$this->jedi_beacon->jedi_log( 'PHP File: '.$php_file.PHP_EOL.'BEFORE: ',
					preg_replace( '/\s*/m', '', $php_file_contents ) );

				/**
				 * Loop through post IDs to replace shortcode references to each ID
				 **/
				foreach( $this->jedi_imported_posts as $old_id => $jedi_post_id ) {
					$old_shortcode = 'global_module="' . $old_id . '"';
					$new_shortcode = 'global_module="' . $jedi_post_id . '"';
					$php_file_contents = str_replace($old_shortcode, $new_shortcode, $php_file_contents);
				}

				/**
				 * Write PHP File & check for errors
				 **/
				$safe_jedi = file_put_contents( $php_file, $php_file_contents );
				if( false === $safe_jedi ) {
					$this->jedi_beacon->jedi_log('Error writing footer.php file', $php_file );
				}
				$this->jedi_beacon->jedi_log( 'PHP File: '.$php_file.PHP_EOL.'AFTER: ',
					preg_replace( '/\s*/m', '', $php_file_contents ) );

			} // END foreach( $php_files )

		} // END IF ChildTheme

	} // END update_phpfile_shortcodes()


	/**
	 * Loads the Divi Theme Options & Settings from the file and imports them
	 **/
	private function import_options() {
		$jedi_import_options = $this->jedi_apprentice_data_import['options'];

		/**
		 * Loops through Divi Options
		 * Uses Divi's function to update values from import
		 **/
		foreach($jedi_import_options as $divi_key=>$jedi_import_option) {
			et_update_option($divi_key, $jedi_import_option); }

		/**
		 * Update Image URLs in Divi Options
		 **/
		if( $this->jedi_imported_media ) {
			$divi_logo = et_get_option('divi_logo');
			$divi_favicon = et_get_option('divi_favicon');
			$divi_rss_url = et_get_option('divi_rss_url');

			/**
			 * Loops through imported Image URLs
			 **/
			foreach( $this->jedi_imported_media['urls'] as $jedi_url ) {
				if( $divi_logo == $jedi_url['oldURL'] ) { $divi_logo = $jedi_url['newURL']; }
				if( $divi_favicon == $jedi_url['oldURL'] ) { $divi_favicon = $jedi_url['newURL']; }
				if( $divi_rss_url == $jedi_url['oldURL'] ) { $divi_rss_url = $jedi_url['newURL']; }
			}

			/**
			 * Uses Divi's function to update values from import
			 **/
			et_update_option('divi_logo', $divi_logo);
			et_update_option('divi_favicon', $divi_favicon);
			et_update_option('divi_rss_url', $divi_rss_url);
		}

		$this->jedi_beacon->jedi_report( 'Divi Settings & Options Imported' );

	} // END import_options()

	/**
	 * Loads the Additional CSS from the file and imports them
	 *
	 * Appends imported CSS to current Customizer Additional CSS
	 **/
	private function import_css() {
		$custom_css_post = wp_get_custom_css_post();

		if( $custom_css_post ) {
			$jedi_import_css = $custom_css_post->post_content . "\n\n"
				.$this->jedi_apprentice_data_import['css'];
		} else {
			$jedi_import_css = $this->jedi_apprentice_data_import['css'];
		}

		wp_update_custom_css_post( $jedi_import_css );

		$this->jedi_beacon->jedi_report( 'Additional CSS Imported' );

	} // END import_css()


	/**
	 * Loads the menus from the file and imports them
	 *
	 * @uses wp_update_nav_menu_item( int $menu_id, int $menu_item_db_id, array $menu_item_data )
	 **/
	private function import_menus() {
		/**
		 * Parse imported menu data
		 **/
		$jedi_import_menus = $this->jedi_apprentice_data_import['menus'];
		$jedi_menu_names = $jedi_import_menus[0];
		$jedi_menu_items = $jedi_import_menus[1];
		$jedi_menu_locations = $jedi_import_menus[2];
		$jedi_menu_ids = array();

		/**
		 * Loop through data to Create Menus
		 **/
		foreach($jedi_menu_names as $jedi_menu_name) {
			$this->jedi_beacon->jedi_log( 'Creating Menu', $jedi_menu_name->name );
			$new_menu_id = wp_create_nav_menu( $jedi_menu_name->name );

			if( is_wp_error( $new_menu_id ) ) {
				$this->jedi_beacon->jedi_log( 'Error creating menu: ' . $jedi_menu_name->name, $new_menu_id );
			} else {
				$jedi_menu_ids[$jedi_menu_name->term_id] = $new_menu_id;
			}
		}

		/**
		 * Load Menu Items
		 **/
		$jedi_post_ids = $this->jedi_imported_posts;
		$jedi_imported_categories = $this->jedi_imported_categories;
		$new_menu_id = array();

		foreach( $jedi_menu_names as $key1=>$jedi_import_menu ) {
			$menu_id = get_term_by( 'name', $jedi_menu_names[$key1]->name, 'nav_menu' );
			foreach( $jedi_menu_items[$key1] as $key2=>$jedi_import_menu_item ) {
				if( isset( $jedi_import_menu_item->_invalid ) ) {
					continue;
				}

				$item_array = array(
					'menu-item-title'	=> $jedi_import_menu_item->title,
					'menu-item-object'	=> $jedi_import_menu_item->object,
					'menu-item-type'	=> $jedi_import_menu_item->type,
					'menu-item-post-type'	=> $jedi_import_menu_item->post_type,
					'menu-item-post_type'	=> $jedi_import_menu_item->post_type,
					'menu-item-classes'	=> $jedi_import_menu_item->classes[0],
					'menu-item-status'	=> $jedi_import_menu_item->post_status );

				/**
				 * Handle Custom and Page Menu Items
				 **/
				if( 'custom' == $jedi_import_menu_item->object ) {
					$item_array['menu-item-url'] = $jedi_import_menu_item->url;
				} else if( 'category' == $jedi_import_menu_item->object ) {
					$item_array['menu-item-object-id'] = $jedi_imported_categories[$jedi_import_menu_item->object_id];
				} else {
					if( isset( $jedi_post_ids[$jedi_import_menu_item->object_id] ) ) {
						$item_url = get_page_link( $jedi_post_ids[$jedi_import_menu_item->object_id] );
						$item_array['menu-item-url'] = $item_url;
						$item_array['menu-item-object-id'] = $jedi_post_ids[$jedi_import_menu_item->object_id];
					}
				}

				/**
				 * Handle Child Menu Items
				 **/
				 if ( '0' != $jedi_import_menu_item->menu_item_parent ) {
					$item_array['menu-item-parent-id'] = intval(
						$new_menu_id[$jedi_import_menu_item->menu_item_parent] );
				}

				/**
				 * Create Menu Item
				 **/
				$new_menu_id[$jedi_import_menu_item->ID] = wp_update_nav_menu_item(
					$menu_id->term_id,	// Menu ID
					0, 					// Menu Item DB ID
					$item_array );		// Menu Item Data

				$this->jedi_beacon->jedi_log( 'Creating Menu Item', $item_array['menu-item-title'] );

			} // END foreach $jedi_menu_items[$key1]

		} // END foreach $jedi_menu_names

		/**
		 * Set Menu Locations
		 **/
		$jedi_primary_menu = get_term_by('name',$jedi_menu_locations['primary-menu'], 'nav_menu');
		$jedi_secondary_menu = get_term_by('name',$jedi_menu_locations['secondary-menu'], 'nav_menu');
		$jedi_footer_menu = get_term_by('name',$jedi_menu_locations['footer-menu'], 'nav_menu');
		set_theme_mod( 'nav_menu_locations', array(
			'primary-menu'		=> $jedi_primary_menu ? $jedi_primary_menu->term_id : '',
			'secondary-menu'	=> $jedi_secondary_menu ? $jedi_secondary_menu->term_id : '',
			'footer-menu'		=> $jedi_footer_menu ? $jedi_footer_menu->term_id : '' ) );

		$this->jedi_beacon->jedi_report( 'Menus Imported', count($jedi_import_menus[0]) );

		/**
		 * Update Posts That Call Menus By ID
		 **/
		foreach($jedi_post_ids as $jedi_post_id) {
			$menu_post = get_post($jedi_post_id);

			if( false !== strpos($menu_post->post_content, 'menu_id="') ) {
				$menu_id_start = strpos($menu_post->post_content, 'menu_id="') + 9;
				$menu_id_end = strpos($menu_post->post_content, '"', $menu_id_start);
				$content_menu_id = substr($menu_post->post_content, $menu_id_start, $menu_id_end - $menu_id_start);
				$old_shortcode = 'menu_id="'.$content_menu_id.'"';
				$new_shortcode = 'menu_id="'.$jedi_menu_ids[$content_menu_id].'"';
				$menu_post->post_content = str_replace($old_shortcode, $new_shortcode, $menu_post->post_content);
				wp_update_post($menu_post);
			}
		} // END foreach jedi_post_ids

	} // END import_menus()


	/**
	 * Loads the Widgets from the file and imports them
	 **/
	private function import_widgets() {

		$jedi_apprentice_widget_import = $this->jedi_apprentice_data_import['widgets'];
		update_option( 'sidebars_widgets', $jedi_apprentice_widget_import['sidebars_widgets'] );
		set_theme_mod( 'et_pb_widgets', $jedi_apprentice_widget_import['et_pb_widgets'] );

		$jedi_widget_options = $jedi_apprentice_widget_import['widget_options'];

		if( is_array( $jedi_widget_options ) ) {
			foreach( $jedi_widget_options as $option_key => $jedi_widget_option ) {
				$widget_content = unserialize( $jedi_widget_option );

				/**
				 * Filter: jedi_modify_widget_content
				 **/
				if( has_filter('jedi_modify_widget_content' ) ) {
					$widget_content = apply_filters(
						'jedi_modify_widget_content',
						$widget_content,
						$this->jedi_imported_posts
					);
				}

				update_option( $option_key, $widget_content );
			}
		}

		$this->jedi_beacon->jedi_report( 'Widget Data Imported' );

	} // END import_widgets()


}  // END JEDI_Apprentice_Import

endif;