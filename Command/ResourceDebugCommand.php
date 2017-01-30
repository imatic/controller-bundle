<?php

namespace Imatic\Bundle\ControllerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\VarDumper;

class ResourceDebugCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('imatic:controller:resource-debug')
            ->setDescription('Debug controller resources')
            ->addArgument('resource', InputArgument::OPTIONAL, 'Resource to debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->getContainer()->get('imatic_controller.resource.config_repository');

        $io = new SymfonyStyle($input, $output);
        $io->title('Controller resources');

        VarDumper::dump($repository->getActions());
    }
}