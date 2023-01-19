<?php
/**
 * Templatefile for the admin view.
 *
 * @package Bluerpint
 */

namespace Aidministrator\Views;

?>

<div class="aidministrator">
	<div class="aidministrator-header">
		<div>
			<h2>Aidministrator</h2>
			<p><?php esc_html_e( 'Ask the AI for help.', 'aidministrator' ); ?></p>
		</div>
		<div>
			<button class="aidministrator-expand">
				<span class="dashicons dashicons-editor-expand"></span>
			</button>
		</div>
	</div>	
	<div class="messages-container">
		<div class="messages">
		</div>
		<div class="waiting" style="display:none"><?php esc_html_e( 'one moment please', 'aidministrator' ); ?></div>
	</div>	
	<div class="input">
		<form method="post" id="aidministrator" autocomplete="off">
			<input 
				type="text" 
				id="aidministrator-message" 
				name="aidministrator-message" 
				placeholder="<?php esc_html_e( 'Type your message here', 'aidministrator' ); ?>" 
				value=""/>	
			<input type="submit" value="<?php esc_html_e( 'Send', 'aidministrator' ); ?>" class="button"/>
		</form>
	</div>
</div>
