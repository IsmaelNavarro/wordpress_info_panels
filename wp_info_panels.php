<?php
/*
Plugin Name: Wordpress Info Panels
Description: Un plugin para añadir un banner con información personalizada de posts de la página web
Version: 1.0
Author: Ismael Navarro
Text Domain: wp_info_panels
License: GNU General Public License v2 or later
*/

function myplugin_custom_scripts(){
    wp_enqueue_script( 'wp_info_panels_admin_script', plugins_url("js/wp_info_panels_admin.js", __FILE__));
    wp_enqueue_style( 'wp_info_panels_admin_script', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css");
    wp_enqueue_style( 'wp_info_panels_admin_style', plugins_url("css/wp_info_panels_admin.css", __FILE__) );
}

add_action('admin_enqueue_scripts', 'myplugin_custom_scripts');

function add_menu_wp_info_panels(){
	add_management_page(
		"Info panels",
		"Info panels",
		"manage_options",
		"front_banner",
		"front_banner_config"
	);
}

function front_banner_config(){
	echo <<<EOF
	<div class="container container-full">
		<h2 class="page-header">Wordpress Info Panels</h2>
		<div class="row">
			<div class="col-sm-12">
				<p> Esta herramienta permite configurar los paneles de información de la aplicación </p>
			</div>
			<div class="col-sm-12">
				<h2 class="page-header">Paneles <button class="btn btn-success wip_new_group_panel"> <span class="glyphicon glyphicon-plus"></span> </button> <small style="font-size: .4em;">Pulsa para crear un nuevo panel</small></h2>
				<div class="wip_new_group_panel_form panel panel-primary">
					<div class="panel-heading">Creación de paneles</div>
					<form class="form-inline panel-body" id="wip_new_group">
						<div class="form-group">
							<label for="wip_panel_name"> Nombre </label>
							<input type="text" class="form-control" id="wip_panel_name" name="wip_panel_name" placeholder="Nombre del panel"/> 
						</div>
						<div class="form-group">
							<label for="wip_panel_sizex"> Tamaño horizontal </label>
							<input type="text" class="form-control" id="wip_panel_sizex" name="wip_panel_sizex" placeholder="(pixeles o porcentaje)"/> 
						</div>
						<div class="form-group">
							<label for="wip_panel_sizey"> Tamaño vertical </label>
							<input type="text" class="form-control" id="wip_panel_sizey" name="wip_panel_sizey" placeholder="(pixeles o porcentaje)"/> 
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success wip_new_panel_add">Añadir</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
EOF;
}

add_action('admin_menu', 'add_menu_wp_info_panels');

function wip_install(){
	global $wpdb;
	$table_name1 = $wpdb->prefix.'wp_info_panels';
	$table_name2 = $wpdb->prefix.'wp_info_panels_groups';
	$table_name3 = $wpdb->prefix.'wp_info_panels_info_panels_groups';
	$charset_collate = $wpdb->get_charset_collate();
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql = "CREATE TABLE IF NOT EXISTS $table_name1 (
				id int(9) AUTO_INCREMENT PRIMARY KEY,
				cols int(1),
				rows int(1),
				relation_post bigint(20),
				title VARCHAR(200),
				thumbnail VARCHAR(200)
			) $charset_collate;";
	dbDelta($sql);

	$sql = "CREATE TABLE IF NOT EXISTS $table_name2 (
				id int(9) AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(200),
				sizex VARCHAR(200),
				sizey VARCHAR(200)
			) $charset_collate;";
	dbDelta($sql);

	$sql = "CREATE TABLE IF NOT EXISTS $table_name3 (
				id int(9) AUTO_INCREMENT PRIMARY KEY,
				panel_id int(9) NOT NULL,
				group_id int(9) NOT NULL,
				FOREIGN KEY (panel_id) 
					REFERENCES $table_name1(id)
					ON DELETE NO ACTION ON UPDATE CASCADE,
				FOREIGN KEY (group_id) 
					REFERENCES $table_name2(id)
					ON DELETE NO ACTION ON UPDATE CASCADE
			) $charset_collate;";
	dbDelta($sql);
}

register_activation_hook( __FILE__, 'wip_install' );


//AJAX functions
add_action("wp_ajax_get_panels", "wip_get_panels");

function wip_get_panels(){
	echo $_POST["whatever"];
	wp_die();
}



