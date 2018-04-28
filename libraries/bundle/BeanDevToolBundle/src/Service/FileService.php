<?php

namespace Bean\Bundle\DevToolBundle\Service;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileService {
	
	/** @var ContainerInterface $container */
	private $container;
	/** @var OutputInterface $output */
	private $output;
	
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
		$container           = $this->container;
		$registeredLibraries = $container->getParameter(sprintf('bean_dev_tool.%ss', $type));
		$output              = $this->output;
		
		$libraryDirs = glob($srcPath . $type . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		foreach($libraryDirs as $libraryDir) {
			$libraryName = basename($libraryDir);
			if(count($registeredLibraries) > 0) {
				if( ! in_array($libraryName, $registeredLibraries)) {
					$output->writeln('Skipping Library  ' . $type . ' ' . $libraryName);
					continue;
				}
			}
			$output->writeln([ $libraryName, $libraryDir ]);
			$output->writeln('============ Copy in Progress ============');
			$this->copyFolder($libraryDir, $destPath . $type . DIRECTORY_SEPARATOR . $libraryName, $ignoredFolders);
			$output->writeln('===================');
			$output->writeln('===================');
			
		}
	}
	
	public function copyFolder($src, $dest, $ignoredFolders = array()) {
		if(in_array(basename($src), $ignoredFolders)) {
			echo '-> Skipping ' . $src . '; ';
			
			return;
		}
		
		$this->output->writeln('copying ' . $src);
		$dir = opendir($src);
		if( ! file_exists($dest)) {
			mkdir($dest);
		}
		while(false !== ($file = readdir($dir))) {
			if(($file != '.') && ($file != '..')) {
				$destFile = $dest . '/' . $file;
				$srcFile  = $src . '/' . $file;
				if(is_dir($srcFile)) {
					$this->copyFolder($srcFile, $destFile, $ignoredFolders);
				} else {
					copy($srcFile, $destFile);
				}
			}
		}
		closedir($dir);
	}
	
	/**
	 * @param OutputInterface $output
	 */
	public function setOutput(OutputInterface $output): void {
		$this->output = $output;
	}
	
}