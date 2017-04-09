<?php
namespace Concrete\Package\AdvancedThumbnail\Src\File\Type\Inspector;

use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\Service\File as FileService;
use Concrete\Core\File\Type\Inspector\Inspector;
use Concrete\Core\File\Importer as FileImporter;

class PdfInspector extends Inspector
{
    public function inspect(Version $fv)
    {
        /** @var FileService $fh */
        $fh = \Core::make('helper/file');
        $fileName = $fv->getFileName();

        // Create a tmp pdf file
        $resource = $fv->getFileResource();
        $filePath = $fh->getTemporaryDirectory() . DIRECTORY_SEPARATOR . $fileName;
        $fh->append($filePath, $resource->read());

        // get a image of first page of the pdf
        $imagick = new \Imagick($filePath . '[0]');

        $newFileName = substr($fileName, 0, strrpos($fileName, '.')) . '_tmb.jpg';
        $newFilePath = $fh->getTemporaryDirectory() . '/' . $newFileName;
        if ($imagick->writeImage($newFilePath) === true) {
            $fi = new FileImporter();
            $fi->import($newFilePath, $newFileName);
            unlink($newFilePath);
        }
        unlink($filePath);
    }
}