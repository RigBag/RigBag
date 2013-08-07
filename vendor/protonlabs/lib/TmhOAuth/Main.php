<?php
require_once __DIR__.'/src/tmhOAuth.php';
require_once __DIR__.'/src/tmhUtilities.php';

class TmhOAuth_Main extends tmhOAuth {

	
	public function outputError($tmhOAuth) {
		echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
		tmhUtilities::pr($tmhOAuth);
	}
}

class TmhOAuth_Utilities extends tmhUtilities {
	
}