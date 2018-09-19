<?php 
defined( 'ABSPATH' ) OR exit;

/** 
 * Plugin Name:    Formulario Vkr
 * Description:    Este es un plugin de muestra.
 * Author:         Víctor Alegre Santos
 * Version:        1.0.0
 */


//Configuración
require_once plugin_dir_path(__FILE__) . 'PluginConfig.php';

//Cargamos la configuración del plugin
FVPluginConfig::init();

//Funciones principales
require_once plugin_dir_path(__FILE__) . 'includes/FVFormulario.php';

//Añadimos todas las acciones del plugin
$fv_plugin_handler = new FVFormulario(); 



