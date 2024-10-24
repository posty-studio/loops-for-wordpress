<?php

namespace L4WP\Endpoints;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

class REST_Subscribe_Controller extends WP_REST_Controller {
	public function __construct() {
		$this->namespace = 'loops/v1';
		$this->rest_base = 'subscribe';
	}

	/**
	 * @param string   $email
	 * @param string   $first_name
	 * @param WP_Error $error
	 */
	private function log_signup_attempt( $email, $first_name, $error ) {
		$log_message = sprintf(
			'[%s] Failed Newsletter Signup Attempt - Email: %s, First Name: %s, Error: %s (Code: %s)',
			current_time( 'mysql' ),
			$email,
			$first_name,
			$error->get_error_message(),
			$error->get_error_code()
		);
		error_log( $log_message );
	}

	/**
	 * @param string $email
	 * @param string $first_name
	 * @return bool|WP_Error True if the subscriber was added, WP_Error otherwise.
	 */
	private function add_subscriber( $email, $first_name ) {
		$endpoint = 'https://app.loops.so/api/v1/contacts/create';

		if ( ! defined( 'LOOPS_API_KEY' ) ) {
			return new WP_Error(
				'l4wp_missing_api_key',
				__( 'Missing API key.', 'loops-for-wordpress' ),
				[ 'status' => 500 ]
			);
		}

		if ( ! is_email( $email ) ) {
			return new WP_Error(
				'l4wp_invalid_email',
				__( 'Invalid email address.', 'loops-for-wordpress' ),
				[ 'status' => 400 ]
			);
		}

		$response = wp_remote_post(
			$endpoint,
			[
				'body'    => wp_json_encode(
					[
						'email'        => sanitize_email( $email ),
						'firstName'    => sanitize_text_field( $first_name ),
						'mailingLists' => [
							'12345' => true, // Example mailing list ID.
						],
					]
				),
				'headers' => [
					'Authorization' => 'Bearer ' . LOOPS_API_KEY,
					'Content-Type'  => 'application/json',
				],
			]
		);

		$code = wp_remote_retrieve_response_code( $response );

		if ( $code !== 200 ) {
			$body    = wp_remote_retrieve_body( $response );
			$body    = json_decode( $body, true );
			$message = $body['message'];

			return new WP_Error(
				'l4wp_subscribe_failed',
				$message,
				[ 'status' => $code ]
			);
		}

		return true;
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => '__return_true',
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * POST `/loops/v1/subscribe`
	 *
	 * @param WP_REST_Request $request The API request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {
		$email      = $request->get_param( 'email' );
		$first_name = $request->get_param( 'first_name' );
		$result     = $this->add_subscriber( $email, $first_name );

		if ( is_wp_error( $result ) ) {
			$this->log_signup_attempt( $email, $first_name, $result );
		}

		return rest_ensure_response( $result );
	}

	/**
	 * Retrieves the endpoint schema, conforming to JSON Schema.
	 *
	 * @return array Schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'loops-for-wordpress-subscribe',
			'type'       => 'object',
			'properties' => [
				'email'      => [
					'description' => __( 'Email address', 'loops-for-wordpress' ),
					'type'        => 'string',
					'format'      => 'email',
					'required'    => true,
					'context'     => [ 'edit' ],
				],
				'first_name' => [
					'description' => __( 'First name', 'loops-for-wordpress' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => [ 'edit' ],
				],
				'honey'      => [
					'description' => __( 'Honeypot field', 'loops-for-wordpress' ),
					'type'        => 'string',
					'context'     => [ 'edit' ],
					'enum'        => [ '' ],
				],
			],
		];

		$schema = rest_default_additional_properties_to_false( $schema );

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}
}
