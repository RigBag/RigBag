<?php
class ProtonLabs_Dir {
	
	public static function generatePath( $depth = 5, $seed = null ) {
		
		if( is_null( $seed ) ) {
			$seed		= rand( 0, 1000000 );
		}
		
		if( $depth > 32 ) {
			$depth		= 32;
		}
		
		$hash	= md5( time() . '|' . $seed );
		$path	= '';
		
		for( $a = 0; $a < $depth; $a++ ) {
			$path .= substr( $hash, $a, 1 ) . '/';
		}
		
		return $path;
	}
	
	public static function makeDirectory( $path, $mode = 0777 ) {
		
		if( !is_dir( $path ) ) {
			return mkdir( $path, $mode, true );
		} 
		return true;
		
	}
	
	public static function generateBufforPath( $str, $depth = 2, $size = 50 ) {
		
		$str	= (int)$str;
		$path	= array();
		
		for( $a = $depth; $a > 0; $a-- ) {
			$t 		= pow( $size, $a );
			$path[]	= ceil( $str / $t );
		}
		
		return implode( '/', $path ) . '/';
	}
}