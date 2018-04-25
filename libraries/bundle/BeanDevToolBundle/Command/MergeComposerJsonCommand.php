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
		$projectDir   = $container->getParameter('kernel.project_dir');
		$projectDirFS = str_replace('\\', '/', $projectDir);
		
		$composerWSPath = $projectDir . DIRECTORY_SEPARATOR . 'composer.json';
		$composerWSJson = file_get_contents($container->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . 'composer_original.json');
		$composerWS     = json_decode($composerWSJson);
		// let's copy Bundles first
		foreach($bundles as $bundle) {
			$reflector   = new \ReflectionClass($bundle);
			$fn          = $reflector->getFileName();
			$bundleName  = basename($bundle);
			$bundleDir   = dirname($fn);
			$bundleDirFS = str_replace('\\', '/', $bundleDir);
			
			$output->writeln('$bundleDirFS is ' . $bundleDirFS);
			if(is_dir($container->getParameter('bean_dev_tool.library_workspace') . 'bundle' . DIRECTORY_SEPARATOR . $bundleName)) {
				$output->writeln([ $bundleName, $bundle, $bundleDir ]);
				$output->writeln('===================');
				$composerPath = $bundleDir . DIRECTORY_SEPARATOR . "composer.json";
				$composerJson = file_get_contents($composerPath);
				$composer     = json_decode($composerJson);
				
				JsonService::merge($composer, $composerWS, 'require');
				JsonService::merge($composer, $composerWS, 'require-dev');
				
				$psr4 = $composer->autoload->{'psr-4'};
				foreach($psr4 as $_ns => $_path) {
					$psr4->$_ns = str_replace($projectDir . DIRECTORY_SEPARATOR, '', $bundleDir) . DIRECTORY_SEPARATOR;
				}
				$composer->autoload->{'psr-4'} = $psr4;
				JsonService::merge($composer, $composerWS, 'autoload.psr-4');
				/////////////// TODO: DRY
//				$psr4 = $composer->{'autoload-dev'}->{'psr-4'};
//				foreach($psr4 as $_ns => $_path) {
//					$psr4->$_ns = str_replace($projectDirFS, '', $bundleDirFS) . DIRECTORY_SEPARATOR;
//				}
//				$composer->{'autoload-dev'}->{'psr-4'} = $psr4;
//				JsonService::merge($composer, $composerWS, 'autoload-dev', true);
				
				file_put_contents($composerWSPath, json_encode($composerWS, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
				
				$output->writeln('===================');
				$output->writeln('===================');
				$output->writeln('===================');
			}
		}
		
	}
}