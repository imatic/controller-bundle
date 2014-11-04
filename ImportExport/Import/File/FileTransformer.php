<?php

namespace Imatic\Bundle\ControllerBundle\ImportExport\Import\File;

use Exception;
use Imatic\Bundle\ControllerBundle\Controller\Feature\Command\CommandFeature;
use Imatic\Bundle\ImportBundle\Event\RecordImportEvent;
use Imatic\Bundle\ImportExportBundle\Import\File\FileTransformer as BaseFileTransformer;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileTransformer extends BaseFileTransformer
{
    /** @var CommandFeature */
    protected $command;

    public function __construct(FormFactory $formFactory, CommandFeature $command)
    {
        parent::__construct($formFactory);
        $this->command = $command;
    }

    protected function handleRecord(FormInterface $form, RecordImportEvent $event)
    {
        $import = $event->getImport();
        if (!$import->hasOption('command')) {
            return;
        }

        $this->command->setCommandName($import->getOption('command'));
        $result = $this->command->execute([
            'data' => $form->getData(),
        ]);

        if (!$result->isSuccessful()) {
            throw new Exception('Import failed');
        }
    }
}
