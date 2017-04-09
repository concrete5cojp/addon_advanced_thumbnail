<?php defined('C5_EXECUTE') or die("Access Denied.");

class PdfFileTypeInspector extends FileTypeInspector
{
    public function inspect($fv)
    {
        if (class_exists('Imagick')) {
            // create thumbnail directories
            $fv->createThumbnailDirectories();
            
            // get a image of first page of the pdf
            $filePath = $fv->getPath();
            $imagick = new Imagick($filePath . '[0]');
            
            // create thumbnails
            $imagick->setImageFormat('jpg');
            
            $newFilePath = $fv->getThumbnailPath(1);
            if (file_exists($newFilePath)) {
                unlink($newFilePath);
            }
            $imagick->thumbnailImage(AL_THUMBNAIL_WIDTH, AL_THUMBNAIL_HEIGHT);
            $imagick->writeImage($newFilePath);
            
            $newFilePath2 = $fv->getThumbnailPath(2);
            if (file_exists($newFilePath2)) {
                unlink($newFilePath2);
            }
            $imagick->thumbnailImage(AL_THUMBNAIL_WIDTH_LEVEL2, AL_THUMBNAIL_HEIGHT_LEVEL2);
            $imagick->writeImage($newFilePath2);
            
        }
    }
}