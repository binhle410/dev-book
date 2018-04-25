<?php

namespace Bean\Bundle\DevToolBundle\Service;

class JsonService {
	public static function merge($src, $dest, $key = null, $force = false) {
		$isPropExistent = false;
		if(property_exists($src, $key)) {
			$isPropExistent = true;
			$properties     = [ $key ];
		} else {
			$properties = explode('.', $key);
			// TODO: implement for this case
			if(count($properties) > 0) {
				$isPropExistent = true;
			}
		}
		
		if($isPropExistent) {
			//						$composerWS->{'require-dev'} = (object) array_merge((array) $composerWS->{'require-dev'}, (array) $composer->{'require-dev'});
			self::mergeProperties($properties, $src, $dest, $force);
		}
	}
	
	public static function mergeProperties($properties = array(), $src, $dest, $force = false) {
		$key = array_shift($properties);
		if(count($properties) > 0) {
			self::mergeProperties($properties, $src->$key, $dest->$key, $force);
		}
		self::mergeProperty($key, $src, $dest, $force);
	}
	
	public static function mergeProperty($key, $src, $dest, $force = false) {
		$srcPropertyArray  = (array) ($src->{$key});
		$destPropertyArray = (array) ($dest->{$key});
		foreach($srcPropertyArray as $_key => $_value) {
			if($force || ! array_key_exists($_key, $destPropertyArray)) {
				$destPropertyArray[ $_key ] = $_value;
			}
		}
		$dest->{$key} = (object) $destPropertyArray;
//			var_dump($dest->{$key});
	}
}