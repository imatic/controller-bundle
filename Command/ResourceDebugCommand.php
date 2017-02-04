<?php

namespace Imatic\Bundle\ControllerBundle\Command;

use Imatic\Bundle\ControllerBundle\Resource\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
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
        $resource = $input->getArgument('resource');
        /** @var ConfigurationRepository $repository */
        $repository = $this->getContainer()->get('imatic_controller.resources.resource_repository');

        $io = new SymfonyStyle($input, $output);

        if ($resource) {
            $io->title(sprintf('Controller resource %s', $resource));
            $this->executeResource($io, $repository, $resource);
        } else {
            $io->title('Controller resources');
            $this->executeResources($io, $repository);
        }
    }

    private function executeResource(StyleInterface $io, ConfigurationRepository $repository, $resourceName)
    {
        $resource = $repository->getResource($resourceName);

        $io->section('Config');
        VarDumper::dump($resource->getConfig());

        $io->section('Actions');
        foreach ($resource->getActions() as $action) {
            $io->text($action['name']);

            VarDumper::dump($action);
        }
    }

    private function executeResources(StyleInterface $io, ConfigurationRepository $repository)
    {
        $io->listing($repository->getResourceNames());
    }
}