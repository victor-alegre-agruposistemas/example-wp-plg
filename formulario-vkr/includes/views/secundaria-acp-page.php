
<?php 
    // Actualizamos los permisos de cada rol de usuario al cargar la página.
    // Convendría detectar que se ejecutase solo al hacer submit
    FVPluginConfig::actualizar_capacidades(); 
?>

<div class="wrap">
        <h2>Configuración</h2>
</div>

<div class="wrap">

    <div class="config-section">

        <form action='options.php' method='post'>

        <?php
            //Cargamos la config y la vista de los elementos del formulario
            settings_fields( 'view_config_options' );
            do_settings_sections( 'view_config_options' );
            submit_button();
        ?>

        </form>

    </div>

    <div class="config-section">

        <form action='options.php' method='post'>

        <?php
            //Cargamos la config y la vista de los elementos del formulario
            settings_fields( 'edit_config_options' );
            do_settings_sections( 'edit_config_options' );
            submit_button();
        ?>

        </form>

    </div>    

</div>