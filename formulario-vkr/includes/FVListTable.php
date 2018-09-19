<?php

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class FV_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {

        /** Process bulk action */
		$this->process_bulk_action();

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        @usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = @count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = @array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'nombre'        => 'Nombre',
            'apellidos'     => 'Apellidos',
            'email'         => 'Email'
        );
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('nombre' => array('nombre', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;

        $resultados = $wpdb->get_results(
            "SELECT id, nombre, apellidos, email "
            . "FROM {$wpdb->prefix}formulario_vkr "
            . "ORDER BY nombre ASC", "ARRAY_A"
        );

        return $resultados;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'nombre':
                return $this->column_name( $item );
            case 'apellidos':
            case 'email':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'nombre';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }

    /**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {

        if( current_user_can( 'fv_edit_enable' ) ) {
            $actions = [
                'bulk-delete' => 'Eliminar'
            ];    

        }else{
            $actions = [];
        }


        return $actions;
    }
    
    /**
     * Procesa la eliminación en bloque
     */
    public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_GET['_wpnonce'] );

			if (  wp_verify_nonce( $nonce, 'fv_delete_row' ) || !current_user_can( 'fv_edit_enable' ) ) {
				die( 'Acción no permitida' );
			}
			else {
				self::delete_row( absint( $_GET['row_id'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_row( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
    }

    /**
     * Elimina un solo registro
     */
    public function process_single_delete() {
        if( isset($_POST["row_id"]) && ! empty($_POST["row_id"]) ){
            return $this->delete_row($_POST["row_id"]);
        }
    }
    
    /**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
    }
    
    /**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_row( $id ) {
		global $wpdb;

		return $wpdb->delete(
			"{$wpdb->prefix}formulario_vkr",
			[ 'id' => $id ],
			[ '%d' ]
        );
        
    }

    /**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

        $delete_nonce = wp_create_nonce( 'fv_delete_row' );
        $title = '<strong>' . $item['nombre'] . '</strong>';

        //Si tiene permisos suficientes, devolvemos la opción de eliminar el registro
        if( current_user_can('fv_edit_enable')){
            $actions = [
                'delete' => sprintf( '<a href="javascript:void(0)" id="fv_row-%s">Eliminar</a>', absint( $item['id'] ) )
            ];

            return $title . $this->row_actions( $actions );

        }else{
            return $title;
        }
    }
    
}
