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
 * */
include 'vendor/autoload.php';

use Firebase\JWT\JWT;

if (!class_exists('GLBasicJWTRestApiEndPoint')) {

    class GLBasicJWTRestApiEndPoint extends WP_REST_Controller {

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
                            'methods' => WP_REST_Server::CREATABLE,
                            'callback' => array($this, 'gl_user_login_callback'),
                            'args' => array(),
                        ),
                    )
            );

            register_rest_route($namespace, '/' . $base . '/register',
                    array(
                        array(
                            'methods' => WP_REST_Server::CREATABLE,
                            'callback' => array($this, 'gl_create_user'),
                            'args' => array(),
                        ),
                    )
            );

            register_rest_route($namespace, '/' . $base . '/reset-password',
                    array(
                        array(
                            'methods' => WP_REST_Server::CREATABLE,
                            'callback' => array($this, 'gl_reset_password'),
                            'permission_callback' => array($this, 'gl_validate_token'),
                        ),
                    )
            );
        }

        public function gl_reset_password($request) {
            $json_params = $request->get_json_params();

            $user = $this->gl_validate_token();
            $user_id = $user->ID;

            if (!empty($json_params['new_password'])) {
                     wp_set_password($json_params['new_password'], $user_id);
                    $response = array(
                        'success' => true,
                        'statusCode' => 200,
                        'message' => __('Password has been updated', 'basic-jwt-rest-apis-plugin-glogix'),
                        'data' => array(),
                    );
                    return new WP_REST_Response($response);
            }else {
                    $response = array(
                        'success' => true,
                        'statusCode' => 403,
                        'message' => __('Unable to update the password', 'basic-jwt-rest-apis-plugin-glogix'),
                        'data' => array(),
                    );
                    return new WP_REST_Response($response,403);
                }
        }

        public function gl_user_login_callback($request) {
            $secret_key = "testing-key";
            $json_params = $request->get_params();
            $username = $json_params['username'];
            $password = $json_params['password'];

            // First thing, check the secret key if not exist return a error.
            if (!$secret_key) {
                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => __('JWT is not configurated properly.', 'basic-jwt-rest-apis-plugin-glogix'),
                    'data' => array(),
                        ), 403
                );
            }

            $user = $this->authenticate_user($username, $password);

            // If the authentication is failed return error response.
            if (is_wp_error($user)) {
                $error_code = $user->get_error_code();

                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'code' => $error_code,
                    'message' => strip_tags($user->get_error_message($error_code)),
                    'data' => array(),
                        ),403
                );
            }

            // Valid credentials, the user exists, let's generate the token.
            return $this->generate_token($user, false);
            return new WP_REST_Response($request, 200);
        }

        public function authenticate_user($username, $password) {

            $user = wp_authenticate($username, $password);

            return $user;
        }

        public function gl_validate_token() {
            $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;
            if (!$auth) {
                return false;
            }
            list($token) = sscanf($auth, 'Bearer %s');
            $secret_key = 'testing-key';
            $alg = 'HS256';
            $payload = JWT::decode($token, $secret_key, array($alg));


            // Check the user id existence in the token.
            if (!isset($payload->data->user->id)) {
                // No user id in the token, abort!!
                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => __('User ID not found in the token.', 'basic-jwt-rest-apis-plugin-glogix'),
                    'data' => array(),
                        )
                );
            }

            // So far so good, check if the given user id exists in db.
            $user = get_user_by('id', $payload->data->user->id);
            return $user;
        }

        public function validate_token($output = true) {
            /**
             * Looking for the HTTP_AUTHORIZATION header, if not present just
             * return the user.
             */
            $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;

            // Double check for different auth header string (server dependent).
            if (!$auth) {
                $auth = isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : false;
            }

            if (!$auth) {
                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => $this->messages['jwt_auth_no_auth_header'],
                    'data' => array(),
                        ),403
                );
            }

            /**
             * The HTTP_AUTHORIZATION is present, verify the format.
             * If the format is wrong return the user.
             */
            list($token) = sscanf($auth, 'Bearer %s');
            var_dump($token);
            if (!$token) {
                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => $this->messages['jwt_auth_bad_auth_header'],
                    'data' => array(),
                        ),403
                );
            }

            // Get the Secret Key.
            $secret_key = 'testing-key';

            if (!$secret_key) {
                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => __('JWT is not configurated properly.', 'basic-jwt-rest-apis-plugin-glogix'),
                    'data' => array(),
                        ),403
                );
            }

            // Try to decode the token.
            try {
                $alg = 'HS256';
                $payload = JWT::decode($token, $secret_key, array($alg));


                // Check the user id existence in the token.
                if (!isset($payload->data->user->id)) {
                    // No user id in the token, abort!!
                    return new WP_REST_Response(
                            array(
                        'success' => false,
                        'statusCode' => 403,
                        'message' => __('User ID not found in the token.', 'basic-jwt-rest-apis-plugin-glogix'),
                        'data' => array(),
                            ),403
                    );
                }

                // So far so good, check if the given user id exists in db.
                $user = get_user_by('id', $payload->data->user->id);

                if (!$user) {
                    // No user id in the token, abort!!
                    return new WP_REST_Response(
                            array(
                        'success' => false,
                        'statusCode' => 403,
                        'message' => __("User doesn't exist", 'basic-jwt-rest-apis-plugin-glogix'),
                        'data' => array(),
                            ),403
                    );
                }

                // Everything looks good return the token if $output is set to false.
                if (!$output) {
                    return $payload;
                }

                $response = array(
                    'success' => true,
                    'statusCode' => 200,
                    'message' => __('Token is valid', 'basic-jwt-rest-apis-plugin-glogix'),
                    'data' => array(),
                );

                $response = apply_filters('jwt_auth_valid_token_response', $response, $user, $token, $payload);

                // Otherwise, return success response.
                return new WP_REST_Response($response);
            } catch (Exception $e) {
                // Something is wrong when trying to decode the token, return error response.
                return new WP_REST_Response(
                        array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => $e->getMessage(),
                    'data' => array(),
                        ),403
                );
            }
        }

        public function generate_token($user, $return_raw = true) {
            $secret_key = 'testing-key';
            $issued_at = time();
            $not_before = $issued_at;
            $expire = $issued_at + ( DAY_IN_SECONDS * 5 );

            $payload = array(
                'iss' => get_bloginfo('url'),
                'iat' => $issued_at,
                'nbf' => $not_before,
                'exp' => $expire,
                'data' => array(
                    'user' => array(
                        'id' => $user->ID,
                    ),
                ),
            );

            $alg = 'HS256';
            $token = JWT::encode($payload, $secret_key, $alg);

            // If return as raw token string.
            if ($return_raw) {
                return $token;
            }

            // The token is signed, now create object with basic info of the user.
            $response = array(
                'success' => true,
                'statusCode' => 200,
                'data' => array(
                    'token' => $token,
                    'id' => $user->ID,
                    'email' => $user->user_email,
                    'nicename' => $user->user_nicename,
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'displayName' => $user->display_name,
                ),
            );

            return $response;
        }

        public function gl_create_user($request) {

            $params = $request->get_params();
            $response = array();
            $validations = array(
                'first_name' => esc_html__('First name is required.', 'basic-jwt-rest-apis-plugin-glogix'),
                'last_name' => esc_html__('Last name is required.', 'basic-jwt-rest-apis-plugin-glogix'),
                'email' => esc_html__('Email address is required.', 'basic-jwt-rest-apis-plugin-glogix'),
                'password' => esc_html__('Password is required.', 'basic-jwt-rest-apis-plugin-glogix'),
                'confirm_password' => esc_html__('Please re-type your password.', 'basic-jwt-rest-apis-plugin-glogix'),
            );
            $emailData = array();
            foreach ($validations as $key => $value) {

                if (empty($params[$key])) {
                    $response = array(
                        'success' => false,
                        'statusCode' => 200,
                        'message' => __($value, 'basic-jwt-rest-apis-plugin-glogix'),
                        'data' => array(),
                    );
                    return new WP_REST_Response($response);
                }

                if ($key === 'confirm_password') {
                    if ($params['password'] != $params['confirm_password']) {
                        $response = array(
                            'success' => false,
                            'statusCode' => 200,
                            'message' => __('Password does not match', 'basic-jwt-rest-apis-plugin-glogix'),
                            'data' => array(),
                        );
                        return new WP_REST_Response($response);
                    }
                }
            }
            $password = $params['password'];

            $username = sanitize_user($params['username'], true);

            $email = $params['email'];
            $first_name = sanitize_title($params['first_name']);
            $last_name = sanitize_title($params['last_name']);



            $userdata = array(
                'user_pass' => $password,
                'user_login' => $username,
                'user_nicename' => $first_name,
                'user_email' => $email,
                'display_name' => $first_name . ' ' . $last_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
            );
            $user_id = wp_insert_user($userdata);

            // On success.
            if (!is_wp_error($user_id)) {



                $response = array(
                    'success' => true,
                    'statusCode' => 200,
                    'message' => __('Account has been created', 'basic-jwt-rest-apis-plugin-glogix'),
                    'data' => array(),
                );
                return new WP_REST_Response($response);
            } else {
                $response = array(
                    'success' => false,
                    'statusCode' => 403,
                    'message' => __('User already exists. Please try another one', 'basic-jwt-rest-apis-plugin-glogix'),
                    'data' => array(),
                );
                return new WP_REST_Response($response,403);
            }
        }

    }

}
add_action('rest_api_init',
        function () {
    $controller = new GLBasicJWTRestApiEndPoint;
    $controller->register_routes();
});
