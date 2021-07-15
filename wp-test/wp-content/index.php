<?php
/**
 * Plugin Name:       Basic JWT Rest API Test
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Basic JWT Rest API Test
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abuzer Firdousi
 * Text Domain:       basic-jwt-rest-apis-plugin-glogix
 **/



if (!class_exists('GLBasicJWTRestApiEndPoint')) {

	class GLBasicJWTRestApiEndPoint extends WP_REST_Controller
	{

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
    	$version = '1';
    	$namespace = 'api/v' . $version;
    	$base = 'sustainyou';

    	register_rest_route($namespace, '/' . $base . '/login',
    		array(
    			array(
    				'methods' => WP_REST_Server::READABLE,
    				'callback' => array($this, 'gl_user_login_callback'),
    				'args' => array(
    				),
    			),
    			array(
    				'methods' => WP_REST_Server::CREATABLE,
    				'callback' => array($this, 'gl_user_login_callback'),
    				'args' => array(),
    			),
    		)
    	);

    	register_rest_route($namespace, '/' . $base . '/profile/(?P<id>\d+)',
    		array(
    			array(
    				'methods' => WP_REST_Server::READABLE,
    				'callback' => array($this, 'gl_get_user_profile'),
    				'args' => [
    					'id'
    				],
    			),
    			array(
    				'methods' => WP_REST_Server::CREATABLE,
    				'callback' => array($this, 'gl_set_user_profile'),
    				'args' => array(),
    			),
    		)
    	);

    	// register_rest_route($namespace, '/' . $base . '/token',
    	// 	array(
    	// 		array(
    	// 			'methods' => WP_REST_Server::READABLE,
    	// 			'callback' => array($this, 'get_items'),
    	// 			'args' => array(
    	// 			),
    	// 		),
    	// 		array(
    	// 			'methods' => WP_REST_Server::CREATABLE,
    	// 			'callback' => array($this, 'save_user_device_token'),
    	// 			'args' => array(),
    	// 		),
    	// 	)
    	// );


    }

    public function get_items($request) {
    	$items['data'] = array();
    	return new WP_REST_Response($items, 200);
    }

    public function get_my_services($request) {
    	$user_id = $_GET['user_id'];
    	$services  =  get_user_meta($_GET['user_id'], 'profile_services', true);
        //print_r($services);
        //$items = unserialize($services);

    	$items = array();
    	if( !empty($services )){
    		$items = array_values($services);    
    	}
    	return new WP_REST_Response($items, 200);
    }



    public function gl_set_user_profile($request){
    	return new WP_REST_Response($request, 200);
    }	
    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function gl_get_user_profile($request) {



    	$ID = $request['id'];
    	$user = get_user_by( 'ID', $ID  );
    	$user_meta =  get_user_meta($ID);
            //print_r($user_meta);die;
//          return $user_meta;


    	$data = array();
    	$data['ID'] = $user->data->ID ;
    	$data['user_nicename'] = $user->data->user_nicename ;
    	$data['user_email'] = $user->data->user_email ;
    	$data['user_url'] = $user->data->user_url ;
    	$data['user_status'] = $user->data->user_status ;
    	$data['display_name'] = $user->data->display_name ;


              //  $data[ 'meta'] = $user_meta; 



    	$items =( $data );
    	return new WP_REST_Response($items, 200);






    }

    /**
     * Login user for application
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function user_login($request) {
    	$params = $request->get_params();
    	$_POST = $request->get_params();

    	if(isset($params['googleLogin']) && isset($params['username'])
    		&& ((boolean)$params['googleLogin'])){

    		$user = get_user_by("email", $params['username']);

    	unset($user->allcaps);
    	unset($user->filter);
    	$user->meta = get_user_meta($user->data->ID, '', true);

    	$user->avatar = apply_filters(
    		'listingo_get_media_filter',
    		listingo_get_user_avatar(array('width' => 100, 'height' => 100),
    			$user->data->ID),
    		array('width' => 100, 'height' => 100)
    	);

    	$user->banner = apply_filters(
    		'listingo_get_media_filter',
    		listingo_get_user_banner(array('width' => 270, 'height' => 120),
    			$user->data->ID),
                    array('width' => 270, 'height' => 120)//size width,height
                );

    	if (is_wp_error($user)) {
    		return new WP_Error('wrong-credentials',
    			__('message', 'listingo-app'), array('status' => 500));
    	} else {
    		$json['type'] = "success";
    		$json['message'] = esc_html__(".", "listingo_core");
    		$json['data'] = $user;
    		return new WP_REST_Response($json, 200);
    	}
    }else if (isset($params['username']) && isset($params['password'])) {
    	$creds = array(
    		'user_login' => $params['username'],
    		'user_password' => $params['password'],
    		'remember' => true
    	);

    	$user = wp_signon($creds, false);
    	unset($user->allcaps);
    	unset($user->filter);
    	$user->meta = get_user_meta($user->data->ID, '', true);

    	$user->avatar = apply_filters(
    		'listingo_get_media_filter',
    		listingo_get_user_avatar(array('width' => 100, 'height' => 100),
    			$user->data->ID),
    		array('width' => 100, 'height' => 100)
    	);

    	$user->banner = apply_filters(
    		'listingo_get_media_filter',
    		listingo_get_user_banner(array('width' => 270, 'height' => 120),
    			$user->data->ID),
                    array('width' => 270, 'height' => 120)//size width,height
                );

    	if (is_wp_error($user)) {
    		return new WP_Error('wrong-credentials',
    			__('message', 'listingo-app'), array('status' => 500));
    	} else {
    		$json['type'] = "success";
    		$json['message'] = esc_html__(".", "listingo_core");
    		$json['data'] = $user;
    		return new WP_REST_Response($json, 200);
    	}
    }




    return new WP_Error('cant-create', __('message', 'listingo-app'),
    	array('status' => 500));
}

    /**
     * Forgot password for application
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function forgot_password($request) {
    	$params = $request->get_params();
    	$_POST = $request->get_params();
    	$json = array();
    	if (isset($params['email'])) {
    		$user_login = $params['email'];
    		$status = true;
    		$response_message = '';
    		$json['message'] = 'Some error occured';
    		global $wpdb, $wp_hasher, $user_data;

    		$user_login = sanitize_text_field($user_login);


    		if (empty($user_login)) {
    			$status = false;
    			$response_message = 'Please enter email address';
    		} else if (strpos($user_login, '@')) {
    			$user_data = get_user_by('email', trim($user_login));
    			if (empty($user_data))
    				$status = false;
    			$response_message = 'Email address does not exist';
    		} else {
    			$login = trim($user_login);
    			$user_data = get_user_by('login', $login);
    		}

    		do_action('lostpassword_post');


    		if ($user_data) {



                // redefining user_login ensures we return the right case in the email
    			$user_login = $user_data->user_login;
    			$user_email = $user_data->user_email;

                do_action('retreive_password', $user_login);  // Misspelled and deprecated
                do_action('retrieve_password', $user_login);

                $allow = apply_filters('allow_password_reset', true,
                	$user_data->ID);

                if (!$allow) {
                	$status = false;
                	$json['message'] = 'Password change not allowed';
                } else if (is_wp_error($allow))
                $status = false;

                $key = wp_generate_password(20, false);
                do_action('retrieve_password_key', $user_login, $key);

                if (empty($wp_hasher)) {
                	require_once ABSPATH . 'wp-includes/class-phpass.php';
                	$wp_hasher = new PasswordHash(8, true);
                }
                $hashed = $wp_hasher->HashPassword($key);
                $wpdb->update($wpdb->users,
                	array('user_activation_key' => $hashed),
                	array('user_login' => $user_login));

                $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
                $message .= network_home_url('/') . "\r\n\r\n";
                $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
                $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
                $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
                $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login),
                	'login') . ">\r\n";

                if (is_multisite())
                	$blogname = $GLOBALS['current_site']->site_name;
                else
                	$blogname = wp_specialchars_decode(get_option('blogname'),
                		ENT_QUOTES);

                $title = sprintf(__('[%s] Password Reset'), $blogname);

                $title = apply_filters('retrieve_password_title', $title);
                $message = apply_filters('retrieve_password_message',
                	$message, $key);

                if ($message && !wp_mail($user_email, $title, $message))
                	$response_message = ( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );

                $response_message = '<p>Link for password reset has been emailed to you. Please check your email.</p>';
            }

            $json['message'] = $response_message;
            if ($status) {
            	$json['type'] = "success";
            	$json['data'] = array();
            	return new WP_REST_Response($json, 200);
            } else {
            	$json['type'] = "error";
            	return new WP_REST_Response($json, 200);
            }
        }
    }

}

}
add_action('rest_api_init',
	function () {
		$controller = new GLBasicJWTRestApiEndPoint;
		$controller->register_routes();
	});
