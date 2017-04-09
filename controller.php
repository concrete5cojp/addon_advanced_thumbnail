<?php
namespace Concrete\Package\AdvancedThumbnail;

use Concrete\Core\File\Type\TypeList as FileTypeList;
use Concrete\Core\File\Type\Type as FileType;

class Controller extends \Concrete\Core\Package\Package
{
    protected $pkgHandle = 'advanced_thumbnail';
    protected $appversionRequired = '8.1.0';
    protected $pkgVersion = '1.0';
    
    public function getPackageDescription()
    {
        return t('Generate thumbnail image for PDF/Video files');
    }
    
    public function getPackageName()
    {
        return t('Advanced Thumbnail');
    }
    
    public function on_start()
    {
        // register autoloading
        $this->registerAutoload();
        
        $fileTypeList = FileTypeList::getInstance();
        
        // $extension, $name, $type, $customImporter, $inlineFileViewer, $editor, $pkgHandle
        $fileTypeList->define('pdf', t('PDF'), FileType::T_DOCUMENT, 'pdf', false, false, 'advanced_thumbnail');
        
        /**
         * Which File Formats are supported by ffmpeg?
         * See: {@link https://www.ffmpeg.org/general.html#File-Formats}
         */
        $fileTypeList->define('mp4', t('Video'), FileType::T_VIDEO, 'video', false, false, 'advanced_thumbnail');
        $fileTypeList->define('mov', t('Video'), FileType::T_VIDEO, 'video', false, false, 'advanced_thumbnail');
        $fileTypeList->define('wmv', t('Video'), FileType::T_VIDEO, 'video', false, false, 'advanced_thumbnail');
    }

    protected function registerAutoload() {
        require $this->getPackagePath().'/vendor/autoload.php';
    }
}