<?php

namespace Bean\Bundle\DevToolBundle\Command;

use Bean\Bundle\DevToolBundle\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyToSourceCommand extends ContainerAwareCommand {
	
	protected function configure() {
		$this
			// the name of the command (the part after "bin/console")
			->setName('bean-dev:library:copy-to-source')
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
			'List and Copy Bundles to Git Source',
		]);
		$container = $this->getContainer();
		$bundles   = $container->getParameter('kernel.bundles');
		// let's copy Bundles first
		foreach($bundles as $bundle) {
			$reflector  = new \ReflectionClass($bundle);
			$fn         = $reflector->getFileName();
			$bundleName = basename($bundle);
			$bundleDir  = dirname($fn);
			if(is_dir($container->getParameter('bean_dev_tool.library_workspace') . 'bundle' . DIRECTORY_SEPARATOR . $bundleName)) {
				$output->writeln([ $bundleName, $bundle, $bundleDir ]);
				$output->writeln('============ Copy to Source ============');
				FileService::copyFolder($bundleDir, $container->getParameter('bean_dev_tool.library_source') . 'bundle' . DIRECTORY_SEPARATOR . $bundleName, [ '.git' ]);
				$output->writeln('===================');
				$output->writeln('===================');
			}
		}
		
		$output->writeln([
			'//////////////////////////////////////////////',
			'List and Copy Components and Sites to Git Source',
		]);
		
		$componentDirs = glob($container->getParameter('bean_dev_tool.library_workspace') . 'component' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		foreach($componentDirs as $componenDir) {
			$output->writeln($componenDir);
			FileService::copyFolder($componenDir, $container->getParameter('bean_dev_tool.library_source').'component', [ '.git' ]);
		}
		
		// outputs a message without adding a "\n" at the end of the line
		$output->write('/////////////// Fnished \\\\\\\\\\\\\\\\\\\\ ');
	}
	
}