<?php

namespace Imatic\Bundle\ControllerBundle\ImportExport\Import\File;

use Exception;
use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface;
use Imatic\Bundle\ImportBundle\Event\RecordImportEvent;
use Imatic\Bundle\ImportExportBundle\Import\File\FileTransformer as BaseFileTransformer;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileTransformer extends BaseFileTransformer
{
    /** @var CommandExecutorInterface */
    protected $commandExecutor;

    public function __construct(FormFactory $formFactory, CommandExecutorInterface $commandExecutor)
    {
        parent::__construct($formFactory);
        $this->commandExecutor = $commandExecutor;
    }

    protected function handleRecord(FormInterface $form, RecordImportEvent $event)
    {
        $import = $event->getImport();
        if (!$import->hasOption('command')) {
            return;
        }

        $command = new Command($import->getOption('command'), [
            'data' => $form->getData(),
        ]);
        $result = $this->commandExecutor->execute($command);

        if (!$result->isSuccessful()) {
            throw new Exception('Import failed');
        }
    }
}
