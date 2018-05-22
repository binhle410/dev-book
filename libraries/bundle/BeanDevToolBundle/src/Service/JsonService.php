<?php

namespace Bean\Bundle\DevToolBundle\Service;

class JsonService {
	
	private $names = [];
	
	public function merge($src, $dest, $key = null, $force = false) {
		$this->names[ $src->name ] = true;
		
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
			$this->mergeProperties($properties, $src, $dest, $force);
		}
		
		$this->removeDevDependencies($dest);
	}
	
	public function removeDevDependencies($dest) {
		$key               = "require";
		$destPropertyArray = (array) ($dest->{$key});
		$destPropertyArrayKey = array_keys($destPropertyArray);

		foreach($this->names as $name => $val) {
			if(in_array($name, $destPropertyArrayKey)) {
				unset($destPropertyArray[ $name ]);
			}
		}
		
		$dest->{$key} = (object) $destPropertyArray;
	}
	
	public function mergeProperties($properties = array(), $src, $dest, $force = false) {
		$key = array_shift($properties);
		if(count($properties) > 0) {
			$this->mergeProperties($properties, $src->$key, $dest->$key, $force);
		}
		$this->mergeProperty($key, $src, $dest, $force);
	}
	
	public function mergeProperty($key, $src, $dest, $force = false) {
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