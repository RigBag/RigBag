<?php
class ProtonLabs_Video {
	
	
	public function extractVideoUrlFromEmbedCode( $embedCode ) {
		
		$embedCode	= substr( $embedCode, strpos( $embedCode, 'src="' ) + 5 );
		$embedCode	= substr( $embedCode, 0, strpos( $embedCode, '"' ) );
		
		
		return $embedCode;
		
	}
	
	public function extractVideoIdFromUrl( $videoUrl ) {
		
		// vimeo
		if( strpos( $videoUrl, 'vimeo' ) ) {
		
			if( strpos( $videoUrl, '?' ) ) {
				$videoUrl	= explode( '?', $videoUrl );
				$videoUrl	= $videoUrl[0];
			}
		
			if( strpos( $videoUrl, '#' ) ) {
				$videoUrl	= explode( '#', $videoUrl );
				$videoUrl	= $videoUrl[0];
			}
		
			$videoUrl	= explode( '/', $videoUrl );
			return $videoUrl[(count( $videoUrl ) - 1)];
		}
		// youtube
		elseif( strpos( $videoUrl, 'youtu' ) ) {
		
			if( strpos( $videoUrl, '/embed' ) ) {
				$videoUrl	= explode( '/', $videoUrl );
				return $videoUrl[(count( $videoUrl ) - 1)];
			} else { 
				$videoUrl	= explode( '?', $videoUrl );
				$videoUrl	= explode( '&', $videoUrl[1] );
					
				
				
				foreach( $videoUrl as $subUrl ) {
		
					$subUrl	= explode( '=', $subUrl );
		
					if( trim( $subUrl[0] ) == 'v' ) {
						return $subUrl[1];
					}
				}
			}
		}
		return null;
		
	}
	
	public function makeEmbedCodeFromVideoUrl( $videoUrl ) {
		
		$videoId	= $this->extractVideoIdFromUrl( $videoUrl );
		$embedCode	= null;
		
		// vimeo
		if( strpos( $videoUrl, 'vimeo' ) ) {
			$embedCode = '<iframe src="http://player.vimeo.com/video/' . $videoId . '" width="530" height="315" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		// youtube
		elseif( strpos( $videoUrl, 'youtu' ) ) {
			$embedCode = '<iframe width="530" height="315" src="http://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
		}
		
		return $embedCode;
	}
	
	public function makeEmbedCodeFromVideoId( $videoId, $type ) {
		
		$embedCode	= '';
		
		// vimeo
		if( $type == 'vimeo' ) {
			$embedCode = '<iframe src="http://player.vimeo.com/video/' . $videoId . '" width="740" height="416" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		}
		// youtube
		elseif( $type == 'youtube' ) {
			$embedCode = '<iframe width="560" height="315" src="http://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
		}
		
		return $embedCode;
		
	}
	
	public function getThumbnailUrlForVideoUrl( $videoUrl ) {
	
		// vimeo
		if( strpos( $videoUrl, 'vimeo' ) ) {
	
			$videoId	= $this->extractVideoIdFromUrl( $videoUrl );
			$hash  		= unserialize( file_get_contents( 'http://vimeo.com/api/v2/video/' . $videoId . '.php' ) );
					
			return $hash[0]['thumbnail_large'];
	
		}
		// youtube
		elseif( strpos( $videoUrl, 'youtu' ) ) {
			return 'http://img.youtube.com/vi/' . $this->extractVideoIdFromUrl( $videoUrl ) . '/0.jpg';
		}
		return null;
	}
	
}