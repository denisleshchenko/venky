<?php 
/**
 * @author 		: Saravana Kumar K
 * @copyright	: sarkware.com
 * @todo		: Wrapper module for all wccpf related Ajax response.
 * 				  All Ajax response from wccpf will be converted to "wcff_response" object and
 * 				  made available to the context through "wcff()->response".
 */
if (!defined('ABSPATH')) { exit; }

class Wcff_Response {
	
	function __construct() {
		add_filter('wcff_response', array($this, 'prepare_response'), 5, 3);
	}
	
	function prepare_response($_status, $_msg, $_data) {
		return json_encode(array ( 
			"status" => $_status, 
			"message"=>$_msg, 
			"data"=>$_data)
		);
	}	
	
}

new Wcff_Response();

?>