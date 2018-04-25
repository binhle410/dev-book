<?php

namespace Bean\Bundle\DevToolBundle\Command;

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
		
		$bundles = $this->getContainer()->getParameter('kernel.bundles');
		
		// outputs a message followed by a "\n"
		$output->writeln('Whoa!');
		
		foreach($bundles as $bundle) {
			$reflector = new \ReflectionClass($bundle);
			$fn = $reflector->getFileName();
			$bundleName = basename($bundle);
			$bundleDir = dirname($fn);
			$output->writeln([ $bundleName, $bundle, $bundleDir ]);
			$output->writeln('===================');
			if(in_array($bundleName, [ 'BeanBookBundle', 'BeanDevToolBundle' ])) {
				$composerJson = file_get_contents($bundleDir . DIRECTORY_SEPARATOR . "composer.json");
				var_dump(json_decode($composerJson));
				$output->writeln([
					' Config ',
					$this->getContainer()->getParameter('bean_dev_tool.library_source'),
					$this->getContainer()->getParameter('bean_dev_tool.library_workspace'),
					' End Config '
				]);
			}
		}
		
		// outputs a message without adding a "\n" at the end of the line
		$output->write('You are about to ');
		$output->write('create a user.');
	}
	
}