<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: Wrapper module for all wccpf related Ajax request.
 * 				  All Ajax request target for wccpf will be converted to "wcff_request" object and
 * 				  made available to the context through "wcff()->request".
 * 
 */
if (!defined('ABSPATH')) { exit; }

class Wcff_Request {
	
	function __construct() {
		add_filter('wcff_request', array($this, 'prepare_request'));
	}
	
	function prepare_request() {
		if (isset($_REQUEST["wcff_param"])) {	
			$payload = json_decode(str_replace('\"','"', $_REQUEST["wcff_param"]), true);			
			return array (				
			    "method" 	=> isset($payload["request"]) ? $payload["request"] : null,
			    "context" 	=> isset($payload["context"]) ? $payload["context"] : null,
			    "post" 		=> isset($payload["post"]) ? $payload["post"] : null,
			    "post_type" => isset($payload["post_type"]) ? $payload["post_type"] : null,
			    "payload" 	=> isset($payload["payload"]) ? $payload["payload"] : null
			);
		}
	}
	
}

new Wcff_Request();

?>