<?php
/**
 * Plugin Name: Cache Issue
 * Plugin URI: https://example.com
 * Description: Show an example of the options caching problems in Atomic sites
 * Version: 0.0.1
 * Author: Sam Seay
 * Author URI: https://example.com
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 */

//  Setup a basic REST API extension that proxies to update_options/get_options
 add_action('rest_api_init', function () {
  register_rest_route('my_api', 'opts', array(
      array(
        'methods' => 'POST',
        'callback' => function ( $request ) {
          $params = $request->get_json_params();
          $updated = array();

          foreach ($params as $key => $value) {
            $updated[$key] = update_option($key, $value);
          }

          return $updated;
        }
      ),
      array(
        'methods' => 'GET',
        'callback' => function ( $request ) {
          $params  = explode(',', $request['options']);
          $options = array();

          if ( ! is_array( $params ) ) {
            return array();
          }

          foreach ( $params as $option ) {
            $options[ $option ] = get_option( $option );
          }

          return $options;
        }
      )
    )
  );
});

function add_menu_item() {
  wp_enqueue_script('client', plugin_dir_url(__FILE__) . 'client.js', array(), '0.0.1', true);
  wp_localize_script( 'client', 'context', [
    'root'  => esc_url_raw( rest_url() ),
    'nonce' => wp_create_nonce( 'wp_rest' ),
]);
  
  add_menu_page( 'Cache Issue', 'Cache Issue', 'manage_options', 'cache-issue',  function() {
    ?>
        <div class="wrap">
          <div id="app">
            
            <p id="option-loading" style="display: none;">Working...</p>
            <h3>Step 1</h3>
            <label for="#option-value">Value to set option to</label>
            <input id="option-value" placeholder="Value to set" value="1">
            <br>  
            <br>  
            <br>  
            <input id="option-name" placeholder="Add an option name"/>
            <button id="option-update" >Add option with specified value</button>
            <p id="option-set-result"></p>
            
            <div id="get-options" style="display:none;">
              <hr />
              <h3>Step 2</h3>
              <button id="option-get-button" >Fetch the option value (false indicates it does not exist)</button>
              <p id="option-get-result" ></p>
            </div>
          </div>
        </div>
		  <?php
  });
}


//  A basic webpage for testing
add_action('admin_menu', 'add_menu_item');
