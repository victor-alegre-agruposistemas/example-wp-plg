(function($){
    $(document).ready(function(){
        var formulario = $(document).find("#main-form");
        var resultado = $(document).find("#fv_resultados");
        var listado = $(document).find("#fv_listado_usuarios");

        //Al hacer submit en el botón insertar
        $(document).on('submit', '#main-form', function(ev){
            ev.preventDefault();

            $.ajax({
                type: "post",
                url: fv_form.ajax_url + window.location.search,
                data: {
                    action : "guardar_datos_ajax",
                    fv_nombre : formulario.find("[name='fv_nombre']").val(),
                    fv_apellidos : formulario.find("[name='fv_apellidos']").val(),
                    fv_email : formulario.find("[name='fv_email']").val()
                },
                beforeSend: function(){
                    resultado.html('Cargando ...');
                },
                success : (response) => {   
                    console.log(response);
                    if(response){                
                        response = fix_pagination_response(response);

                        //Actualizamos la lista con los datos recibidos
                        listado.html(response);    

                        //Informamos de que se ha insetado correctamente
                        resultado.html("Se ha insertado correctamente");
                        
                        //Reiniciamos el formulario
                        formulario.trigger("reset");
                    }else{
                        resultado.html("Rellena los campos correctamente");
                    }
                },
                error : () => {
                    resultado.html("Algo falló en el envío del formulario.");
                }
            });
        });

        //Al hacer click en el botón de bulk actions
        $(document).on('submit', '#lista-container #doaction', function(ev){
            ev.preventDefault();

            $.ajax({
                type: "post",
                url: fv_form.ajax_url,
                data: {
                    action : "eliminar_registro_lote",
                    row_id: $(this).attr("id").substring(7)
                },
                beforeSend: function(){
                    resultado.html('Eliminando ...');
                },
                success : (response) => {   
                    if(response){                
                        response = fix_pagination_response(response);

                        //Actualizamos la lista con los datos recibidos
                        listado.html(response);    

                        //Informamos de que se ha insetado correctamente
                        resultado.html("Fila eliminada correctamente");
                    }else{
                        resultado.html("No se pudo eliminar la fila");
                    }
                },
                error : () => {
                    resultado.html("Algo falló en la eliminación de la fila");
                }
            });
            
        });

        //Al hacer click en el boton eliminar de alguna fila..
        $(document).on('click', '[id^=fv_row-]', function(ev){
            console.log("Eliminando fila con id " + $(this).attr("id").substring(7));
            
            $.ajax({
                type: "post",
                url: fv_form.ajax_url,
                data: {
                    action : "eliminar_registro",
                    row_id: $(this).attr("id").substring(7)
                },
                beforeSend: function(){
                    resultado.html('Eliminando ...');
                },
                success : (response) => {   
                    if(response){             
                        response = fix_pagination_response(response);

                        //Actualizamos la lista con los datos recibidos
                        listado.html(response);    

                        //Informamos de que se ha insetado correctamente
                        resultado.html("Fila eliminada correctamente");
                    }else{
                        resultado.html("No se pudo eliminar la fila");
                    }
                },
                error : () => {
                    resultado.html("Algo falló en la eliminación de la fila");
                }
            });
        });
    });
})(jQuery);

function fix_pagination_response(response){
    return response.replace(/[^"']*admin\-ajax\.php\?/gim, window.location.href+"&");
}