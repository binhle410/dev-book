<?php

namespace Bean\Bundle\DevToolBundle\Service;

class FileService {
	
	public static function copyFolder($src, $dest, $ignoredFolders = array()) {
		if(in_array(basename($src), $ignoredFolders)) {
			echo 'skipping ' . $src;
			
			return;
		}
		
		$dir = opendir($src);
		@mkdir($dest);
		while(false !== ($file = readdir($dir))) {
			if(($file != '.') && ($file != '..')) {
				if(is_dir($src . '/' . $file)) {
					self::copyFolder($src . '/' . $file, $dest . '/' . $file);
				} else {
					copy($src . '/' . $file, $dest . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
	
	
}