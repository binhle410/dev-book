<?php

namespace Bean\Bundle\DevToolBundle\Command;

use Bean\Bundle\DevToolBundle\Service\JsonService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MergeComposerJsonCommand extends ContainerAwareCommand {
	
	protected function configure() {
		$this
			// the name of the command (the part after "bin/console")
			->setName('bean-dev:library:merge-composer')
			// the short description shown while running "php bin/console list"
			->setDescription('Extract a Composer Package')
			// the full command description shown when running the command with
			// the "--help" option
			->setHelp('This command allows you to extract a Composer Package...');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln([
			'DevTool',
			'============',
			'Merge Composer',
		]);
		$container = $this->getContainer();
		$bundles   = $container->getParameter('kernel.bundles');
		
		// outputs a message followed by a "\n"
		$output->writeln('Whoa! DIRECTORY_SEPARATOR is ' . DIRECTORY_SEPARATOR);
		
		$projectDir     = $container->getParameter('kernel.project_dir');
		$composerWSPath = $projectDir . DIRECTORY_SEPARATOR . 'composer.json';
		$composerWSJson = file_get_contents($container->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . 'composer_original.json');
		$composerWS     = json_decode($composerWSJson);
		
		$this->mergeLibraryComposer('bundle', $composerWS, $output);
		$this->mergeLibraryComposer('component', $composerWS, $output);
		
		file_put_contents($composerWSPath, json_encode($composerWS, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
		
		$output->writeln('===================');
		$output->writeln('===================');
		
	}
	
	private function mergeLibraryComposer($type, $composerWS, $output) {
		$container = $this->getContainer();
		
		$registeredLibraries = $container->getParameter(sprintf('bean_dev_tool.%ss', $type));
		
		$libraryDirs = glob($container->getParameter('bean_dev_tool.library_workspace') . $type . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		
		foreach($libraryDirs as $libraryDir) {
			$libraryName = basename($libraryDir);
			if(count($registeredLibraries) > 0) {
				if( ! in_array($libraryName, $registeredLibraries)) {
					$output->writeln('skipping ' . $libraryName);
					continue;
				}
			}
			$libraryDirFS = str_replace('\\', '/', $libraryDir);

//			$output->writeln('$bundleDirFS is ' . $libraryDirFS);
			
			$output->writeln([ $libraryName, $libraryDir ]);
			$output->writeln('===================');
			$composerPath = $libraryDir . DIRECTORY_SEPARATOR . "composer.json";
			$composerJson = file_get_contents($composerPath);
			$composer     = json_decode($composerJson);
			
			JsonService::merge($composer, $composerWS, 'require');
			JsonService::merge($composer, $composerWS, 'require-dev');
			
			$psr4 = $composer->autoload->{'psr-4'};
			$this->fixPsr4($psr4, $libraryDir);
			$composer->autoload->{'psr-4'} = $psr4;
			JsonService::merge($composer, $composerWS, 'autoload.psr-4');
			
			$psr4 = $composer->{'autoload-dev'}->{'psr-4'};
			$this->fixPsr4($psr4, $libraryDir);
			$composer->{'autoload-dev'}->{'psr-4'} = $psr4;
			JsonService::merge($composer, $composerWS, 'autoload-dev.psr-4');
			$psr4 = $composer->{'autoload-dev'}->{'psr-4'};


//				foreach($psr4 as $_ns => $_path) {
//					$psr4->$_ns = str_replace($projectDirFS, '', $bundleDirFS) . DIRECTORY_SEPARATOR;
//				}
//				$composer->{'autoload-dev'}->{'psr-4'} = $psr4;
//				JsonService::merge($composer, $composerWS, 'autoload-dev', true);
			$output->writeln('===================');
		}
		
	}
	
	private function fixPsr4($psr4, $bundleDir) {
		$projectDir = $this->getContainer()->getParameter('kernel.project_dir');
		
		foreach($psr4 as $_ns => $_path) {
			$psr4->$_ns = str_replace($projectDir . DIRECTORY_SEPARATOR, '', $bundleDir) . DIRECTORY_SEPARATOR . $_path;
		}
	}
}