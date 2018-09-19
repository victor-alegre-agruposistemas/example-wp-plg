
<div class="wrap">

    <h1>Formularios Vkr</h1>
    <p>¡Bienvenido/a! Este es mi primer plugin así que no me juzgues pls</p>

</div>


<?php   if( current_user_can( 'fv_edit_enable' ) ) :    ?>

<div class="wrap" class="formulario-vkr">
    <form id="main-form">
        <!-- Nombre del usuario -->
        <div class="grupo-formulario">
            <label>Nombre</label>
            <input id="fv_nombre" name="fv_nombre" type="text" placeholder="Introduce aquí tu nombre">
        </div>
        
        <!-- Apellidos del usuario -->
        <div class="grupo-formulario">
            <label>Apellidos</label>
            <input id="fv_apellidos" name="fv_apellidos" type="text" placeholder="Introduce aquí tus apellidos">
        </div>

        <!-- Email del usuario -->
        <div class="grupo-formulario">
            <label>Correo electrónico</label>
            <input id="fv_email" name="fv_email" type="email" placeholder="Introduce aquí tu correo">
        </div>

        <!-- Botón de enviar y mensaje -->
        <div class="enviar-container">
            <button id="fv_insertar" class="button button-primary">Insertar</button>
            <div id="fv_resultados"></div>
        </div>

    </form>
</div>

<?php endif; ?>

<div class="wrap" id="lista-container">
    <form method="post">
        <div id="fv_listado_usuarios">
            <!-- Muestra la lista de usuarios -->
            <?= $fv_plugin_handler->listar_usuarios() ?>
        </div>
    </form>
</div>

