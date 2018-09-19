<?php

class FVConfig {

	//Llama a las funciones que configuran y renderizan las opciones de configuración
	static function fv_settings_init(  ) { 

		self::fv_view_config();
		self::fv_edit_config();

	}

	static function fv_view_config(){
		register_setting( 'view_config_options', 'fv_view_config' );

		add_settings_section(
			'fv_view_config_options_section', 
			'Ver', 
			null, 
			'view_config_options'
		);

		add_settings_field(
			'editor', 
			'Editor', 
			array(get_called_class(), 'editor_ver_render' ), 
			'view_config_options', 
			'fv_view_config_options_section' 
		);

		add_settings_field(
			'autor', 
			'Autor', 
			array(get_called_class(), 'autor_ver_render' ), 
			'view_config_options', 
			'fv_view_config_options_section' 
		);

		add_settings_field(
			'colaborador', 
			'Colaborador', 
			array(get_called_class(), 'colaborador_ver_render' ), 
			'view_config_options', 
			'fv_view_config_options_section' 
		);
	}

	static function fv_edit_config(){
		register_setting( 'edit_config_options', 'fv_edit_config' );

		add_settings_section(
			'fv_edit_config_options_section', 
			'Editar', 
			null, 
			'edit_config_options'
		);

		add_settings_field(
			'editor', 
			'Editor', 
			array(get_called_class(), 'editor_editar_render' ), 
			'edit_config_options', 
			'fv_edit_config_options_section' 
		);

		add_settings_field(
			'autor', 
			'Autor', 
			array(get_called_class(), 'autor_editar_render' ), 
			'edit_config_options', 
			'fv_edit_config_options_section' 
		);

		add_settings_field(
			'colaborador', 
			'Colaborador', 
			array(get_called_class(), 'colaborador_editar_render' ), 
			'edit_config_options', 
			'fv_edit_config_options_section' 
		);
	}

	static function editor_ver_render(  ) { 

		$options = get_option( 'fv_view_config' );
		?>
		<input type='checkbox' name='fv_view_config[editor]' <?php @checked( $options['editor'], 1 ); ?> value='1'>
		<?php

	}
	
	static function autor_ver_render(  ) { 

		$options = get_option( 'fv_view_config' );
		?>
		<input type='checkbox' name='fv_view_config[author]' <?php @checked( $options['author'], 1 ); ?> value='1'>
		<?php

	}


	static function colaborador_ver_render(  ) { 

		$options = get_option( 'fv_view_config' );
		?>
		<input type='checkbox' name='fv_view_config[contributor]' <?php @checked( $options['contributor'], 1 ); ?> value='1'>
		<?php

	}


	static function editor_editar_render(  ) { 

		$options = get_option( 'fv_edit_config' );
		?>
		<input type='checkbox' name='fv_edit_config[editor]' <?php @checked( $options['editor'], 1 ); ?> value='1'>
		<?php

	}


	static function autor_editar_render(  ) { 

		$options = get_option( 'fv_edit_config' );
		?>
		<input type='checkbox' name='fv_edit_config[author]' <?php @checked( $options['author'], 1 ); ?> value='1'>
		<?php

	}


	static function colaborador_editar_render(  ) { 

		$options = get_option( 'fv_edit_config' );
		?>
		<input type='checkbox' name='fv_edit_config[contributor]' <?php @checked( $options['contributor'], 1 ); ?> value='1'>
		<?php

	}


	static function fv_view_config_section_callback(  ) { 

		echo __( 'Descripción del sitio', 'Nombre del sitio' );

	}

}


?>