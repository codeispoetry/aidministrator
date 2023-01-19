<?php
//phpcs:disable WordPress.WP.AlternativeFunctions

namespace Aidministrator\Controllers;

/**
 * The Controller class for the AJAX action.
 */
class Action {


	/**
	 * Sends the Blueprint to the browser.
	 */
	public function human() {
		$request_body = file_get_contents( 'php://input' );
		parse_str( $request_body, $param );

		if ( empty( $param['message'] || ! wp_verify_nonce( $param['nonce'], 'aidministrator' ) ) ) {
			return false;
		}

		$result = self::request( $param['message'] );
		$answer = $result->choices[0]->text;

		$output = self::run_wp_cli( $answer );
		$output = sprintf( '<h4>%s</h4><pre>%s</pre>', $answer, $output );
		wp_send_json( $output, 200 );
	}

	/**
	 * Runs an arbitrary command
	 *
	 * @param string $command The command to perform.
	 */
	private function run_wp_cli( $command ) {
		$command = trim( $command );

		if ( empty( $command ) ) {
			$response = sprintf( esc_html__( 'No command found. Can not do anything.', 'aidministrator' ), $command );
			wp_send_json( [ $response ], 200 );
		}

		if ( preg_match( '/&/', $command ) || preg_match( '/;/', $command ) || ! preg_match( '/^wp /', $command ) ) {
			// translators: %s is the command.
			$response = sprintf( esc_html__( 'Nope! %s is not allowed.', 'aidministrator' ), $command );
			wp_send_json( [ $response ], 200 );
		}

		exec( $command, $output ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec
		return join( "\n", $output );
	}

	/**
	 * Handles the communication with the OpenAI API.
	 *
	 * @param string $input The prompt for the AI.
	 */
	private function request( $input ) {
		$config = PLUGIN::get_config();

		if ( ! empty( $config['error'] ) ) {
			wp_send_json( $config['error'], 200 );
		}

		$prompt = "Return the WP-CLI command to {$input}";
		// $prompt = "Für die WordPress-Installation auf http://localhost:9200 : {$input}";
		// $prompt = "Wie lautet die URL für WordPress-Installation auf http://localhost:9200 um: {$input}";

		$payload = '{
			"model": "text-davinci-003",
			"prompt": "' . $prompt . '",
			"temperature": 0.3,
			"max_tokens": 100,
			"top_p": 1,
			"frequency_penalty": 0,
			"presence_penalty": 0,
			"stop": ["You:"]
		  }';

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, 'https://api.openai.com/v1/completions' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $config['open_api_key'],
		];
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$result = curl_exec( $ch );

		if ( curl_errno( $ch ) ) {
			wp_send_json( esc_html__( 'Could not reach AI. It says: ', 'aidministrator' ) . curl_error( $ch ), 200 );
		}

		curl_close( $ch );

		$result_json = json_decode( $result );
		if ( ! empty( $result_json->error ) ) {
			wp_send_json( json_encode( $result_json->error->message ), 200 );
		}

		return json_decode( $result );
	}
}
