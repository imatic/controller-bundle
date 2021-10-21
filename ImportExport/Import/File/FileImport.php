<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\ImportExport\Import\File;

use Imatic\Bundle\ImportExportBundle\Import\File\FileImport as BaseFileImport;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileImport extends BaseFileImport
{
    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefined([
            'command',
        ]);
    }
}
