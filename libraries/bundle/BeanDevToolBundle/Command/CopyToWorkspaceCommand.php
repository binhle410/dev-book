<?php

namespace Bean\Bundle\DevToolBundle\Command;

use Bean\Bundle\DevToolBundle\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyToWorkspaceCommand extends ContainerAwareCommand {
	
	protected function configure() {
		$this
			// the name of the command (the part after "bin/console")
			->setName('bean-dev:library:copy-to-workspace')
			// the short description shown while running "php bin/console list"
			->setDescription('Extract a Composer Package')
			// the full command description shown when running the command with
			// the "--help" option
			->setHelp('This command allows you to extract a Composer Package...');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		// outputs multiple lines to the console (adding "\n" at the end of each line)
		$output->writeln([
			'DevTool',
			'============',
			'List Bundles',
		]);
		$container = $this->getContainer();
		$bundles   = $this->getContainer()->getParameter('kernel.bundles');
		
		// outputs a message followed by a "\n"
		$output->writeln('Whoa!');
		
		
		// let's copy Bundles first
		$bundleDirs = glob($container->getParameter('bean_dev_tool.library_source') . 'bundle' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		foreach($bundleDirs as $bundleDir) {
			$bundleName = basename($bundleDir);
//			$bundleDir  = dirname($fn);
			$output->writeln([ $bundleName, $bundleDir ]);
			$output->writeln('============ Copy to Workspace ============');
			FileService::copyFolder($bundleDir, $container->getParameter('bean_dev_tool.library_workspace') . $bundleName, [ '.git' ]);
			$output->writeln('===================');
			$output->writeln('===================');
			
		}
		
		// now how about non-bundle elements
		$output->writeln([
			'//////////////////////////////////////////////',
			'List and Copy Components and Sites to Git Source',
		]);
		
		$componentDirs = glob($container->getParameter('bean_dev_tool.library_source') . 'component' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		foreach($componentDirs as $componenDir) {
			$componentName = basename($componenDir);
			$output->writeln($componenDir);
			FileService::copyFolder($componenDir, $container->getParameter('bean_dev_tool.library_workspace') . 'component' . DIRECTORY_SEPARATOR . $componentName, [ '.git' ]);
		}
		
		
		// outputs a message without adding a "\n" at the end of the line
		$output->write('//// Finished \\\\\\\\\ ');
	}
	
}