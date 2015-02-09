<?php defined('C5_EXECUTE') or die("Access Denied.");

class VideoFileTypeInspector extends FileTypeInspector
{
    public function inspect($fv)
    {
        $ffmpeg = FFMpeg\FFMpeg::create();
        if (is_object($ffmpeg)) {
            // create thumbnail directories
            $fv->createThumbnailDirectories();
            
            // get a image of first page of the pdf
            $filePath = $fv->getPath();
            $video = $ffmpeg->open($filePath);
            
            // create a tmp image file
            $fileName = $fv->getFileName();
            $newFileName = Loader::helper('file')->replaceExtension($fileName, 'jpg');
            $newFilePath = Loader::helper('file')->getTemporaryDirectory() . '/' . $newFileName;
            $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))->save($newFilePath);
            
            // create thumbnails
            $ih = Loader::helper('image');
    		$ih->create($newFilePath, $fv->getThumbnailPath(1), AL_THUMBNAIL_WIDTH, AL_THUMBNAIL_HEIGHT);
    		$ih->create($newFilePath, $fv->getThumbnailPath(2), AL_THUMBNAIL_WIDTH_LEVEL2, AL_THUMBNAIL_HEIGHT_LEVEL2);
            
            // remove the tmp file
            if (file_exists($newFilePath)) {
                unlink($newFilePath);
            }
        }
    }
}