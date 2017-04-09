<?php
namespace Concrete\Package\AdvancedThumbnail\Src\File\Type\Inspector;

use Concrete\Core\Entity\File\Version;
use Concrete\Core\File\Service\File as FileService;
use Concrete\Core\File\Type\Inspector\Inspector;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Concrete\Core\File\Importer as FileImporter;

class VideoInspector extends Inspector
{
    public function inspect(Version $fv)
    {
        $config = [];
        if ($ffmpegbinaries = \Config::get('concrete.file_manager.inspector.ffmpeg.binaries')) {
            $config['ffmpeg.binaries'] = $ffmpegbinaries;
        }
        if ($ffprobebinaries = \Config::get('concrete.file_manager.inspector.ffprobe.binaries')) {
            $config['ffprobe.binaries'] = $ffprobebinaries;
        }
        if ($timeout = \Config::get('concrete.file_manager.inspector.timeout')) {
            $config['timeout'] = $timeout;
        }
        if ($ffmpegthreads = \Config::get('concrete.file_manager.inspector.ffmpeg.threads')) {
            $config['ffmpeg.threads'] = $ffmpegthreads;
        }
        $ffmpeg = FFMpeg::create($config);
        if (is_object($ffmpeg)) {
            /** @var FileService $fh */
            $fh = \Core::make('helper/file');
            $fileName = $fv->getFileName();

            // Create a tmp video file
            $resource = $fv->getFileResource();
            $filePath = $fh->getTemporaryDirectory() . DIRECTORY_SEPARATOR . $fileName;
            $fh->append($filePath, $resource->read());

            // open a tmp video file with ffmpeg
            $video = $ffmpeg->open($filePath);

            // create a tmp image file
            $newFileName = substr($fileName, 0, strrpos($fileName, '.')) . '_tmb.jpg';
            $newFilePath = $fh->getTemporaryDirectory() . DIRECTORY_SEPARATOR . $newFileName;
            $video->frame(TimeCode::fromSeconds(5))->save($newFilePath);

            // create a new image and import it to file manager
            $fi = new FileImporter();
            $fi->import($newFilePath, $newFileName);

            // remove tmp files
            unlink($filePath);
            unlink($newFilePath);
        }
    }
}