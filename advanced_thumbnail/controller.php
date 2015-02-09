<?php defined('C5_EXECUTE') or die('Access Denied');

class AdvancedThumbnailPackage extends Package
{
    protected $pkgHandle = 'advanced_thumbnail';
    protected $appversionRequired = '5.6';
    protected $pkgVersion = '0.1';
    
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
        require_once(__DIR__ . '/vendor/autoload.php');
    }
}