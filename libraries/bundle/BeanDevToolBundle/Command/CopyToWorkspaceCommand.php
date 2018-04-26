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
		$bundles = $this->getContainer()->getParameter('kernel.bundles');
		
		// outputs a message followed by a "\n"
		$output->writeln('Whoa!');
		
		
		// let's copy Bundles first
		foreach($bundles as $bundle) {
			$reflector  = new \ReflectionClass($bundle);
			$fn         = $reflector->getFileName();
			$bundleName = basename($bundle);
			$bundleDir  = dirname($fn);
			if(is_dir($container->getParameter('bean_dev_tool.library_workspace') . 'bundle' . DIRECTORY_SEPARATOR . $bundleName)) {
				$output->writeln([ $bundleName, $bundle, $bundleDir ]);
				$output->writeln('============ Copy to Workspace ============');
				FileService::copyFolder($bundleDir, $container->getParameter('bean_dev_tool.library_source') . $bundleName,['.git']);
				$output->writeln('===================');
				$output->writeln('===================');
			}
		}
		
		// now how about non-bundle elements
		
		// outputs a message without adding a "\n" at the end of the line
		$output->write('You are about to ');
		$output->write('create a user.');
	}
	
}