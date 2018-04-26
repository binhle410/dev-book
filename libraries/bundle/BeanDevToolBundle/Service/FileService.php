<?php

namespace Bean\Bundle\DevToolBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FileService {
	
	/** @var ContainerInterface $container */
	private $container;
	
	function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	/**
	 * @param       $type (bundle|component)
	 * @param       $srcPath
	 * @param       $destPath
	 * @param array $ignoredFolders
	 */
	public function copyLibrary($type, $srcPath, $destPath, $ignoredFolders = array()) {
		$container  = $this->container;
		$bundles    = $container->getParameter('bean_dev_tool.bundles');
		$components = $container->getParameter('bean_dev_tool.components');
		var_dump($bundles);
		var_dump($components);
		
		return;
		$libraryDirs = glob($srcPath . $type . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		foreach($libraryDirs as $libraryDir) {
			$libraryName = basename($libraryDir);
//			$output->writeln([ $bundleName, $bundleDir ]);
//			$output->writeln('============ Copy to Workspace ============');
			$this->copyFolder($libraryDir, $destPath . $type . DIRECTORY_SEPARATOR . $libraryName, $ignoredFolders);
//			$output->writeln('===================');
//			$output->writeln('===================');
			
		}
	}
	
	public function copyFolder($src, $dest, $ignoredFolders = array()) {
		if(in_array(basename($src), $ignoredFolders)) {
			echo '-> Skipping ' . $src . '; ';
			
			return;
		}
		
		$dir = opendir($src);
		@mkdir($dest);
		while(false !== ($file = readdir($dir))) {
			if(($file != '.') && ($file != '..')) {
				if(is_dir($src . '/' . $file)) {
					$this->copyFolder($src . '/' . $file, $dest . '/' . $file, $ignoredFolders);
				} else {
					copy($src . '/' . $file, $dest . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
	
	
}