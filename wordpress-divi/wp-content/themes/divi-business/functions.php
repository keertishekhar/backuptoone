<?php require_once( get_stylesheet_directory().'/jedi-apprentice/jedi-apprentice-import.php' ); ?><?php
	function divibusiness_enqueue_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'divibusiness_enqueue_scripts' );

/*
add_action('admin_head', 'dl_hide_import_menu');
 
function dl_hide_import_menu() {
  echo '<style>
    #toplevel_page_jedi_apprentice_menu {
    display: none !important;
}
  </style>';
}
*/