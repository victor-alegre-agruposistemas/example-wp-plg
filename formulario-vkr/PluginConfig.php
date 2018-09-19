<?php 

/**
 * 
 */
class FVPluginConfig {

    /**
     * Capacidades
     */
    private static $view_cap = 'fv_view_enable';
    private static $edit_cap = 'fv_edit_enable';

    /**
     * Define la configuración
     */
    static function init ( ) {
        // Para poder acceder a la raiz del plugin desde cualquier subcarpeta
        define( '_PLG_ROOT', plugin_dir_path(__FILE__) );
        define( '_PLG_URL', plugin_dir_url(__FILE__) );

        // Registramos los hooks que ejecutarán las funciones de activar y desactivar
        // capacidades al activarse o desactivarse el plugin, respectivamente.
        register_activation_hook(__FILE__, array(__CLASS__, 'activar_capacidades'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'desactivar_capacidades'));

    }

    /**
     * Añade la capacidad de ver o editar dependiendo de la configuración
     */
    public static function activar_capacidades( ) {
        //Variables
        $wp_roles = wp_roles();
        $ver = get_option( 'fv_view_config' );
        $editar = get_option( 'fv_edit_config' );

        //Añadimos las capacidades si están en la configuración
        foreach ($wp_roles->role_objects as $key => $rol) {
            //Capacidad de ver el plugin
            if ( isset($ver[$key]) && $ver[$key] == 1) {                
                $rol->add_cap( self::$view_cap, true );
            }

            //Capacidad de editar el plugin
            if ( isset($editar[$key]) && $editar[$key] == 1) {                
                $rol->add_cap( self::$edit_cap, true );
            }
        }

        //Añadimos manualmente los permisos al admin
        $wp_roles->get_role("administrator")->add_cap( self::$view_cap, true );
        $wp_roles->get_role("administrator")->add_cap( self::$edit_cap, true );
    }


    /**
     * Elimina la capacidad de ver y editar
     */
    public static function desactivar_capacidades() {
        //Variables
        $wp_roles = wp_roles();

        //Eliminamos las capacidades
        foreach ($wp_roles->role_objects as $key => $rol) {
            //Capacidad de ver el plugin
            if ( $rol->has_cap( self::$view_cap ) ) {                
                $rol->remove_cap( self::$view_cap );
            }

            //Capacidad de editar el plugin
            if ( $rol->has_cap( self::$edit_cap ) ) {                
                $rol->remove_cap( self::$edit_cap );
            }
        }
    }

    /**
     * Elimina todas las capacidades y las vuelve a asignar
     */
    public static function actualizar_capacidades(){
        self::desactivar_capacidades();
        self::activar_capacidades();
    }
    
}
