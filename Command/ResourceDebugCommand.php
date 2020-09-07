<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Command;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\VarDumper;

class ResourceDebugCommand extends Command
{
    private $repository;

    public function __construct(ConfigurationRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('imatic:controller:resource-debug')
            ->setDescription('Debug controller resources')
            ->addArgument('resource', InputArgument::OPTIONAL, 'Resource to debug')
            ->addArgument('action', InputArgument::OPTIONAL, 'Action to debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resourceName = $input->getArgument('resource');
        $io = new SymfonyStyle($input, $output);

        if ($resourceName) {
            $resource = $this->repository->getResource($resourceName);

            if ($actionName = $input->getArgument('action')) {
                $io->title(\sprintf('Controller resource action %s:%s', $resourceName, $actionName));
                $action = $resource->getAction($actionName);
                $this->executeAction($io, $action);
            } else {
                $io->title(\sprintf('Controller resource %s', $resourceName));
                $this->executeResource($io, $resource);
            }
        } else {
            $io->title('Controller resources');
            $this->executeResources($io, $this->repository);
        }

        return 0;
    }

    private function executeAction(StyleInterface $io, ResourceAction $action)
    {
        VarDumper::dump($action);
    }

    private function executeResource(StyleInterface $io, Resource $resource)
    {
        $io->section('Config');
        VarDumper::dump($resource->getConfig());

        $io->section('Actions');
        $io->listing(\array_keys($resource->getActions()));
    }

    private function executeResources(StyleInterface $io, ConfigurationRepository $repository)
    {
        $io->listing($repository->getResourceNames());
    }
}
