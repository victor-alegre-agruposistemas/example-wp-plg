<?php
   
//Incluimos las clases que necesitamos
require_once plugin_dir_path(__FILE__) . 'FVListTable.php' ;
require_once plugin_dir_path(__FILE__) . 'FVConfigPage.php' ;



/*
 *  Añade menu a la parte administrativa
 */

class FVFormulario {

    /**
     * Variables
     */
    private $acciones;
    private $gestion_usuarios;

    /**
     * Constructor
     */
    function __construct(){ 

        // Hook => función de esta clase a ejecutar
        $this->acciones = [
            'admin_menu' => 'admin_view',
            'admin_init' => 'fv_settings_init',
            'admin_enqueue_scripts' => 'admin_scripts',
            'wp_ajax_guardar_datos_ajax' => 'guardar_datos_ajax',
            'wp_ajax_eliminar_registro' => 'eliminar_registro',
            'wp_ajax_eliminar_registro_lote' => 'eliminar_registro_lote',
            'updated_option_fv_view_config' => 'actualizar_permisos_roles',
            'updated_option_fv_edit_config' => 'actualizar_permisos_roles'
        ];

        //Iniciamos
        $this->add_acciones();

    }

    /**
     * Agrega todas las acciones necesarias
     */
    private function add_acciones(){
        
        //Agregamos las acciones definidas en el constructor
        foreach($this->acciones as $gancho => $accion){
            add_action( $gancho , array( $this , $accion ) );
        }

    }

    /**
     * Actualiza los permisos de los roles
     */
    public function actualizar_permisos_roles(){
        FVPluginConfig::actualizar_capacidades();
    }

    /**
     * Crea la página principal de la administración
     */
    public function admin_view(){

        add_menu_page (
            'Formularios Vkr',                                              // Título de la página
            'Formulario',                                                   // Título del link del menu
            'fv_view_enable',                                               // Permisos para ver el link
            plugin_dir_path(__FILE__) . 'views/principal-acp-page.php',     // Vista de la página
            null,                                                           // Callback
            _PLG_URL . 'src/icons/icon.png'                                 // URL Icono
        );

        //Llamamos al submenú
        $this->subpaginas();

    }

    /**
     * Crea una página secundaria para la administración
     */
    private function subpaginas(){

        add_submenu_page( 
            plugin_dir_path(__FILE__) . 'views/principal-acp-page.php',         // Padre del subelemento
            'Configuración',                                                    // Título de la página
            'Configuración',                                                    // Título de la sección
            'manage_options',                                                   // Permisos para poder acceder a la sección
            plugin_dir_path(__FILE__) . 'views/secundaria-acp-page.php'         // Vista de la página
        );

    }


    /**
     * Lista los usuarios de la tabla 'formulario_vkr'
     */
    public function listar_usuarios(){
        //Instaciamos la clase que genera la tabla
        $this->gestion_usuarios = new FV_List_Table();
        $this->gestion_usuarios->prepare_items();

        //Desplegamos la tabla en nuestro propio contenedor
        ?>

                <div id="icon-users" class="icon32"></div>
                <h2>Listado de usuarios</h2>
                <?php $this->gestion_usuarios->display(); ?>

        <?php
    }


    /**
     * Añadir scripts, css y opciones AJAX
     */
    public function admin_scripts(){
        wp_enqueue_style( 'formulariovkr-css', _PLG_URL . 'src/css/fv_estilos.css' );
        wp_enqueue_script( 'formulariovkr-js', _PLG_URL . 'src/js/fv_scripts.js', array('jquery') );

        //Le damos a AJAX la ruta del archivo admin-ajax.php 
        wp_localize_script('formulariovkr-js','fv_form',['ajax_url'=>admin_url('admin-ajax.php')]);
    }

    /**
     * Definimos lo que debe hacer la petición AJAX que inserta nuevas filas en la base de datos
     */
    public function guardar_datos_ajax(){

        // Chequeamos los parámetros
        if( !empty($_POST["fv_nombre"]) && !empty($_POST["fv_apellidos"]) && !empty($_POST["fv_email"])){

            //Saneamos las variables
            $nombre = sanitize_text_field($_POST["fv_nombre"]);
            $apellidos = sanitize_text_field($_POST["fv_apellidos"]);
            $email = sanitize_email($_POST["fv_email"]);
            
            //Guardamos en la base de datos
            global $wpdb;

            // insert ( NOMBRE DE TABLA, [ CAMPO => VALOR, ... ] )
            $insertado = $wpdb->insert(
                $wpdb->prefix . "formulario_vkr",
                array(
                    'nombre' => $nombre,
                    'apellidos' => $apellidos,
                    'email' => $email
                )
            );

            if( $insertado ){
                //Devolvemos el listado de usuarios
                echo $this->listar_usuarios(); 
            }else{
                //Si no se ha podido insertar, devolvemos un error 500
                http_response_code(500);
            }

        }
        
        die();
    }

    /**
     * Elimina una única fila de la lista
     */
    public function eliminar_registro(){
        //Instanciamos la tabla
        $this->gestion_usuarios = new FV_List_Table();
        $id = $_POST["row_id"];

        //Si se ha podido eliminar la fila, imprimimos la tabla actualizada
        if ( $this->gestion_usuarios->process_single_delete() ){
            $this->listar_usuarios();
        }

        die();
    }

    /**
     * Elimina filas de la lista en lote
     */
    public function eliminar_registro_lote(){
        //Instanciamos la tabla
        $this->gestion_usuarios = new FV_List_Table();

        //Si se han podido eliminar las filas, imprimimos la tabla actualizada
        if ($this->gestion_usuarios->process_bulk_action() ){
            $this->listar_usuarios();
        }

        die();
    }
    
    /**
     * Carga la página de configuración
     */
    function fv_settings_init(){
        FVConfig::fv_settings_init();
    }

}

