<?php

namespace Imatic\Bundle\ControllerBundle\ImportExport\Import\File;

use Imatic\Bundle\ImportExportBundle\Import\File\FileImport as BaseFileImport;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileImport extends BaseFileImport
{
    protected function configureOptions(OptionsResolverInterface $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setOptional([
            'command',
        ]);
    }
}
