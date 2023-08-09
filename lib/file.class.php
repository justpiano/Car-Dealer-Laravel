<?php
    
    /**
     * File Class
     *
     * package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: file.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class File
    {
        
        /**
         * File::getExtension()
         *
         * @param mixed $path
         * @return string
         */
        public static function getExtension($path)
        {
            return strtolower(pathinfo($path, PATHINFO_EXTENSION));
        }
        
        /**
         * File::deleteRecursive()
         *
         * Usage File::deleteRecursive("test/dir");
         * @param string $dir
         * @param bool $removeParent - remove parent directory
         * @return true
         */
        public static function deleteRecursive($dir = '', $removeParent = false)
        {
            if (is_dir($dir)) {
                $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($ri as $file) {
                    $file->isDir() ? rmdir($file) : unlink($file);
                }
                if($removeParent){
                    self::deleteDirectory($dir);
                }
            }
            return true;
        }
        
        /**
         * File::deleteMulti()
         *
         * @param string $dir
         * @return void
         */
        public static function deleteMulti($dir)
        {
            if (is_dir($dir)) {
                self::deleteRecursive($dir, true);
            } else {
                self::deleteFile($dir);
            }
        }
        
        /**
         * File::deleteDirectory()
         *
         * @param string $dir
         * @return bool
         */
        public static function deleteDirectory($dir = '')
        {
            self::emptyDirectory($dir);
            return rmdir($dir);
        }
        
        /**
         * File::deleteTemp()
         *
         * @param string $dir
         * @param $prefix
         * @return void
         */
        public static function deleteTemp($dir, $prefix)
        {
            chdir($dir);
            $matches = glob("$prefix*");
            if (is_array($matches) && !empty($matches)) {
                foreach ($matches as $match) {
                    self::deleteRecursive($match, true);
                }
            }
        }
        
        /**
         * File::makeDirectory()
         *
         * /my/path/to/dir/
         * @param string $dir
         * @return true|void
         */
        public static function makeDirectory($dir = '')
        {
            if (!file_exists($dir)) {
                if (false === mkdir($dir, 0755, true)) {
                    self::_errorHandler('directory-error', 'Directory not writable {dir} .' . $dir);
                }
                return true;
            }
        }
        
        /**
         * File::renameDirectory()
         *
         * /my/path/to/dir
         * @param string $old
         * @param string $new
         * @return void
         */
        public static function renameDirectory($old = '', $new = '')
        {
            if (file_exists($old)) {
                if (false === rename($old, $new)) {
                    self::_errorHandler('directory-error', 'Can\'t rename {dir}. ' . $new);
                }
            }
        }
        
        /**
         * File::emptyDirectory()
         *
         * @param string $dir
         * @return true
         */
        public static function emptyDirectory($dir = '')
        {
            foreach (glob($dir . '/*') as $file) {
                if (is_dir($file)) {
                    self::emptyDirectory($file);
                } else {
                    unlink($file);
                }
            }
            return true;
        }
        
        /**
         * File::isThemeDir()
         *
         * @param string $theme_dir
         * @param string $default_dir
         * @return string
         */
        public static function isThemeDir($theme_dir, $default_dir)
        {
            
            if (is_dir($theme_dir)) {
                return $theme_dir;
            } else {
                return $default_dir;
            }
        }
        
        /**
         * File::copyDirectory()
         *
         * Copies content of source directory into destination directory
         * Warning: if the destination file already exists, it will be overwritten
         * @param string $source
         * @param string $dest
         * @param int $permissions
         * @return bool
         */
        public static function copyDirectory($source, $dest, $permissions = 0755)
        {
            if (is_link($source)) {
                return symlink(readlink($source), $dest);
            }
            
            if (is_file($source)) {
                return copy($source, $dest);
            }
            
            if (!is_dir($dest)) {
                mkdir($dest, $permissions, true);
            }
            
            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                
                // Deep copy directories
                self::copyDirectory("$source/$entry", "$dest/$entry", $permissions);
            }
            
            $dir->close();
            return true;
        }
        
        /**
         * File::isDirectoryEmpty()
         *
         * @param string $dir
         * @return bool
         */
        public static function isDirectoryEmpty($dir = '')
        {
            if ($dir == '' || !is_readable($dir))
                return false;
            $hd = opendir($dir);
            while (false !== ($entry = readdir($hd))) {
                if ($entry !== '.' && $entry !== '..') {
                    return false;
                }
            }
            closedir($hd);
            return true;
        }
        
        /**
         * File::getDirectoryFilesNumber()
         *
         * @param string $dir
         * @return int
         */
        public static function getDirectoryFilesNumber($dir = '')
        {
            return count(glob($dir . '*'));
        }
        
        /**
         * File::removeDirectoryOldestFile()
         *
         * @param string $dir
         * @return void
         */
        public static function removeDirectoryOldestFile($dir = '')
        {
            $oldestFileTime = date('Y-m-d H:i:s');
            $oldestFileName = '';
            if ($hdir = opendir($dir)) {
                while (false !== ($obj = readdir($hdir))) {
                    if ($obj == '.' || $obj == '..' || $obj == '.htaccess')
                        continue;
                    $fileTime = date('Y-m-d H:i:s', filectime($dir . $obj));
                    if ($fileTime < $oldestFileTime) {
                        $oldestFileTime = $fileTime;
                        $oldestFileName = $obj;
                    }
                }
            }
            if (!empty($oldestFileName)) {
                self::deleteFile($dir . $oldestFileName);
            }
        }
        
        /**
         * File::findSubDirectories()
         *
         * @param string $dir
         * @param bool $fullPath
         * @return array
         */
        public static function findSubDirectories($dir = '.', $fullPath = false)
        {
            $subDirectories = array();
            $folder = dir($dir);
            while ($entry = $folder->read()) {
                if ($entry != '.' && $entry != '..' && is_dir($dir . $entry)) {
                    $subDirectories[] = ($fullPath ? $dir : '') . $entry;
                }
            }
            $folder->close();
            return $subDirectories;
        }
        
        
        /**
         * File::scanDirectory()
         *
         * @param string $directory
         * @param array $options
         * @param string $sorting
         * @return array|false
         */
        public static function scanDirectory($directory, $options, $sorting)
        {
            
            if (str_ends_with($directory, '/')) {
                $directory = substr($directory, 0, -1);
            }
            $base = UPLOADS;
            
            if (!file_exists($directory) || !is_dir($directory)) {
                self::_errorHandler('directory-error', 'Invalid directory selected {dir}. ' . $directory);
                return false;
                
            } elseif (is_readable($directory)) {
                $dirs = array();
                $files = array();
                
                $exclude = array(
                    "htaccess",
                    "git",
                    "php");
                
                $dirfiles = new DirectoryIterator($directory);
                foreach ($dirfiles as $file) {
                    $path = $directory . '/' . $file->getBasename();
                    $real_path = (isset($options['showpath'])) ? $path : str_replace(UPLOADS, "", $path);
                    if ($file->isDot() or in_array($file, array("thumbs", "backups")))
                        continue;
                    
                    if ($file->isDir()) {
                        $dirs[] = array(
                            'path' => $real_path,
                            'url' => self::_fixPath(str_replace(UPLOADS, "", $file->getBasename()) . "/"),
                            'name' => str_replace("_", " ", $file->getBasename()),
                            'kind' => 'directory',
                            'total' => iterator_count(new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS)));
                    }
                    
                    if ($file->isFile()) {
                        if (isset($options['include'])) {
                            $filter = in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $options['include']);
                        } else {
                            $filter = !in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $exclude);
                        }
                        
                        if ($file->getBasename() != "." && $file->getBasename() != ".." && $filter) {
                            $url = self::_fixPath(str_replace($base, "", $file->getPathname()));
                            $files[] = array(
                                'path' => $real_path,
                                'url' => ltrim($url, '/'),
                                'name' => $file->getBasename(),
                                'extension' => $file->getExtension(),
                                'dir' => pathinfo($real_path, PATHINFO_DIRNAME),
                                'mime' => self::getMimeType($file->getPathname()),
                                'is_image' => in_array(strtolower($file->getExtension()), array(
                                    "jpg",
                                    "jpeg",
                                    "svg",
                                    "png",
                                    "gif",
                                    "webp",
                                    "bmp")),
                                'ftime' => Date::doDate("short_date", date('d-m-Y', $file->getMTime())),
                                'size' => File::getSize($file->getSize()),
                                'kind' => 'file');
                        }
                    }
                }
                
                $data['directory'] = $dirs;
                $data['dirsize'] = count($dirs);
                $data['filesize'] = count($files);
                
                $data['files'] = match ($sorting) {
                    "date" => Utility::sortArray($files, 'ftime'),
                    "size" => Utility::sortArray($files, 'size'),
                    "name" => Utility::sortArray($files, 'name'),
                    "type" => Utility::sortArray($files, 'extension'),
                    default => $files,
                };
                
                return $data;
            } else {
                self::_errorHandler('directory-error', 'Directory not readable {dir}. ' . $directory);
                return false;
            }
        }
        
        /**
         * File::scanDirectoryRecursively()
         *
         * @param $directory
         * @param array $options
         * @return array|false
         */
        public static function scanDirectoryRecursively($directory, $options = array())
        {
            if (str_ends_with($directory, '/')) {
                $directory = substr($directory, 0, -1);
            }
            
            if (!file_exists($directory) || !is_dir($directory)) {
                self::_errorHandler('directory-error', 'Invalid directory selected {dir}. ' . $directory);
                return false;
                
            } elseif (is_readable($directory)) {
                $iterator = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
                $all_files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                
                $dirs = array();
                $files = array();
                $exclude = array(
                    "htaccess",
                    "git",
                    "php");
                
                foreach ($all_files as $file) {
                    $path = $directory . '/' . $file->getBasename();
                    $real_path = isset($options['showpath']) ? $path : str_replace(UPLOADS, "", $path);
                    
                    if ($file->isDir()) {
                        $dirs[] = array(
                            'path' => $real_path,
                            'url' => str_replace(BASEPATH, "", $file->getPathname()) . "/",
                            'name' => str_replace("_", " ", $file->getBasename()),
                            'kind' => 'directory',
                            'total' => iterator_count(new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS)));
                    }
                    
                    if ($file->isFile()) {
                        if (isset($options['include'])) {
                            $filter = in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $options['include']);
                        } else {
                            $filter = !in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $exclude);
                        }
                        
                        if ($file->getBasename() != "." && $file->getBasename() != ".." && $filter) {
                            $files[] = array(
                                'path' => $path,
                                'url' => self::_fixPath(str_replace(BASEPATH, "", $file->getPathname())),
                                'name' => $file->getBasename(),
                                'extension' => $file->getExtension(),
                                //'mime' => self::getMimeType($file->getBasename()),
                                'is_image' => in_array($file->getExtension(), array(
                                    "jpg",
                                    "jpeg",
                                    "png",
                                    "gif",
                                    "webp",
                                    "bmp")),
                                'ftime' => Date::doDate("short_date", date('d-m-Y', $file->getMTime())),
                                'size' => File::getSize($file->getSize()),
                                'kind' => 'file');
                        }
                        
                    }
                }
                
                $data['directory'] = $dirs;
                $data['files'] = $files;
                return $data;
            } else {
                self::_errorHandler('directory-error', 'Directory not readable {dir}. ' . $directory);
                return false;
            }
        }
        
        /**
         * File::is_File()
         *
         * @param string $file
         * @return bool
         */
        public static function is_File($file = '')
        {
            if (file_exists($file)) {
                return true;
            } else {
                return false;
            }
            
        }
        
        /**
         * File::getFile()
         *
         * @param string $file
         * @return string|void
         */
        public static function getFile($file = '')
        {
            if (file_exists($file)) {
                return $file;
            } else {
                self::_errorHandler('file-loading-error', 'An error occurred while fetching file {file}. ' . $file);
            }
            
        }
        
        /**
         * File::loadFile()
         *
         * @param string $file
         * @return false|string
         */
        public static function loadFile($file = '')
        {
            $content = file_get_contents($file);
            self::_errorHandler('file-loading-error', 'An error occurred while loading file {file}. ' . $file);
            return $content;
        }
        
        /**
         * File::writeToFile()
         *
         * @param string $file
         * @param string $content
         * @return true
         */
        public static function writeToFile($file = '', $content = '')
        {
            file_put_contents($file, $content);
            self::_errorHandler('file-writing-error', 'An error occurred while writing to file ' . $file);
            return true;
        }
        
        /**
         * File::copyFile()
         *
         * @param string $src (absolute path BASEPATH . $src)
         * @param string $dest (absolute path BASEPATH . $dest)
         * @return bool
         */
        public static function copyFile($src = '', $dest = '')
        {
            $result = copy($src, $dest);
            self::_errorHandler('file-coping-error', 'An error occurred while copying the file {source ' . $src . '} to {destination - ' . $dest . '}');
            return $result;
        }
        
        /**
         * File::findFiles()
         *
         * Returns the files found under the given directory and subdirectories
         * Usage:
         * findFiles(
         *    $dir,
         *    array(
         *       'fileTypes'=>array('php', 'zip'),
         *     'exclude'=>array('html', 'htaccess', 'path/to/'),
         *     'level'=>-1,
         *       'returnType'=>'fileOnly'
         *  ))
         * fileTypes: array, list of file name suffix (without dot).
         * exclude: array, list of directory and file exclusions. Each exclusion can be either a name or a path.
         * level: integer, recursion depth, (-1 - unlimited depth, 0 - current directory only, N - recursion depth)
         * returnType : 'fileOnly' or 'fullPath'
         * @param mixed $dir
         * @param mixed $options
         * @return array
         */
        public static function findFiles($dir, $options = array())
        {
            $fileTypes = $options['fileTypes'] ?? array();
            $exclude = $options['exclude'] ?? array();
            $level = $options['level'] ?? -1;
            $returnType = $options['returnType'] ?? 'fileOnly';
            $filesList = self::_findFilesRecursive($dir, '', $fileTypes, $exclude, $level, $returnType);
            sort($filesList);
            return $filesList;
        }
        
        /**
         * File::scanFiles()
         *
         * @param $dir
         * @param string $extension (*php)
         * @return array|false
         */
        public static function scanFiles($dir, $extension)
        {
            $dirs = glob($dir . '*', GLOB_ONLYDIR);
            $files = array();
            foreach ($dirs as $d) {
                $file = glob($d . '/' . $extension);
                if (count($file)) {
                    $files = array_merge($files, $file);
                }
            }
            return $files;
        }
        
        /**
         * File::deleteFile()
         *
         * @param string $file
         * @return bool
         */
        public static function deleteFile($file = '')
        {
            $result = false;
            if (is_file($file)) {
                $result = unlink($file);
            }
            self::_errorHandler('file-deleting-error', 'An error occurred while deleting the file ' . $file);
            return $result;
        }
        
        /**
         * File::getThemes()
         *
         * @param mixed $dir
         * @return array
         */
        public static function getThemes($dir)
        {
            $directories = glob($dir . '/*', GLOB_ONLYDIR);
            $themes = [];
            if ($directories) {
                foreach ($directories as $row) {
                    $themes[] = basename($row);
                }
            }
            return $themes;
        }
        
        /**
         * File::getMailerTemplates()
         *
         * @return array|false
         */
        public static function getMailerTemplates()
        {
            $path = BASEPATH . "/mailer/" . App::Core()->lang . "/";
            return glob($path . "*.{tpl.php}", GLOB_BRACE);
        }
        
        /**
         * File::getFileType()
         *
         * @param mixed $filename
         * @return string
         */
        public static function getFileType($filename)
        {
            $ext = File::getExtension($filename);
            
            return match ($ext) {
                "mp3", "wav", "aiff", "ogg", "wma", "flac", "m4a", "m4b", "m4p" => "audio.svg",
                "jpg", "png", "jpeg", "bmp", "ai", "psd" => "images.svg",
                "txt", "doc", "docx", "xls", "xlsx", "pdf" => "documents.svg",
                "mov", "avi", "flv", "mp4", "mpeg", "wmv" => "videos.svg",
                "zip", "rar" => "compressed.svg",
                default => "default.svg",
            };
        }
        
        /**
         * File::getFileSize()
         *
         * @param mixed $file
         * @param string $units
         * @param bool $print
         * @return int|string
         */
        public static function getFileSize($file, $units = 'kb', $print = false)
        {
            if (!$file || !is_file($file))
                return 0;
            $showunit = $print ? $units : null;
            $filesSize = filesize($file);
            return match (strtolower($units)) {
                'g', 'gb' => number_format($filesSize / (1024 * 1024 * 1024), 2) . $showunit,
                'm', 'mb' => number_format($filesSize / (1024 * 1024), 2) . $showunit,
                'k', 'kb' => number_format($filesSize / 1024, 2) . $showunit,
                default => number_format($filesSize, 2) . $showunit,
            };
        }
        
        /**
         * File::getSize()
         *
         * @param mixed $size
         * @param int $precision
         * @return string
         */
        public static function getSize($size, $precision = 2)
        {
            $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            $step = 1024;
            $i = 0;
            while (($size / $step) > 0.9) {
                $size = $size / $step;
                $i++;
            }
            return round($size, $precision) . $units[$i];
        }
        
        /**
         * File::directorySize()
         *
         * @param $dir
         * @param $format
         * @return int|string
         */
        public static function directorySize($dir, $format = false)
        {
            $btotal = 0;
            $dir = realpath($dir);
            if ($dir !== false) {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $obj) {
                    $btotal += $obj->getSize();
                }
            }
            return $format ? self::getSize($btotal) : $btotal;
        }
        
        /**
         * File::unzip()
         *
         * @param $archive
         * @param $dir
         * @return bool
         */
        public static function unzip($archive, $dir)
        {
            
            // Check if webserver supports unzipping.
            if (!class_exists('ZipArchive')) {
                self::_errorHandler('zip-error', 'Your PHP version does not support unzip functionality. ' . $archive);
                return false;
            }
            
            if (str_ends_with($dir, '/')) {
                $dir = substr($dir, 0, -1);
            }
            
            if (!file_exists($archive) || !is_dir($dir)) {
                self::_errorHandler('directory-error', 'Invalid directory or file selected {dir}. ' . $dir);
                return false;
                
            } elseif (is_writeable($dir . '/')) {
                $zip = new ZipArchive;
                if ($zip->open($archive) === true) {
                    $zip->extractTo($dir);
                    $zip->close();
                } else {
                    self::_errorHandler('zip-error', 'Cannot read .zip archive. ' . $archive);
                }
                
                return true;
            } else {
                self::_errorHandler('directory-error', 'Directory not writeable {dir}. ' . $dir);
                return false;
            }
        }
        
        /**
         * File::upload()
         *
         * @param mixed $uploadName
         * @param mixed $maxSize
         * @param mixed $allowedExt
         * @return array|false
         */
        public static function upload($uploadName, $maxSize = null, $allowedExt = null)
        {
            
            if (!empty($_FILES[$uploadName])) {
                $fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]["name"], '.'), 1);
                $fileInfo['name'] = basename($_FILES[$uploadName]["name"]);
                $fileInfo['xame'] = substr($_FILES[$uploadName]["name"], 0, strrpos($_FILES[$uploadName]["name"], "."));
                $fileInfo['size'] = $_FILES[$uploadName]["size"];
                $fileInfo['temp'] = $_FILES[$uploadName]["tmp_name"];
                
                if ($fileInfo['size'] > $maxSize) {
                    Message::$msgs['name'] = Lang::$word->FU_ERROR10 . ' ' . File::getSize($maxSize);
                    return false;
                    
                }
                if (strlen($allowedExt) == 0) {
                    Message::$msgs['name'] = Lang::$word->FU_ERROR9; //no extension specified
                    return false;
                    
                }
                $exts = explode(',', $allowedExt);
                if (!in_array(strtolower($fileInfo['ext']), $exts)) {
                    Message::$msgs['name'] = Lang::$word->FU_ERROR8 . $allowedExt; //no extension specified
                    return false;
                    
                }
                if (in_array(strtolower($fileInfo['ext']), array(
                    "jpg",
                    "png",
                    "bmp",
                    "gif",
                    "jpeg"))) {
                    if (!getimagesize($fileInfo['temp'])) {
                        Message::$msgs['name'] = Lang::$word->FU_ERROR7; //invalid image
                        return false;
                        
                    }
                }
                return $fileInfo;
            }
            return false;
        }
        
        /**
         * File::process()
         *
         * @param $result
         * @param mixed $dir
         * @param mixed $prefix
         * @param bool $fname
         * @param bool $replace
         * @return array|void
         */
        public static function process($result, $dir, $prefix = 'SOURCE_', $fname = false, $replace = true)
        {
            if (!is_dir($dir)) {
                Message::$msgs['dir'] = Lang::$word->FU_ERROR12; //Directory doesn't exist!
            }
            
            if (!$fname) {
                $fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $result['ext'];
            } else {
                $fileInfo['fname'] = $fname . '.' . $result['ext'];
            }
            if ($replace) {
                while (file_exists($dir . $fileInfo['fname'])) {
                    $fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $result['ext'];
                }
            }
            if (move_uploaded_file($result['temp'], $dir . $fileInfo['fname'])) {
                return array_merge($result, $fileInfo);
            } else {
                Debug::AddMessage("errors", '<i>Error</i>', 'File could not be moved from temp directory', "session");
            }
            
        }
        
        /**
         * File::createShortenName()
         *
         * @param mixed $file
         * @param integer $lengthFirst
         * @param integer $lengthLast
         * @return array|string|string[]|null
         */
        public static function createShortenName($file, $lengthFirst = 10, $lengthLast = 10)
        {
            return preg_replace("/(?<=.$lengthFirst)(.+)(?=.$lengthLast)/", "...", $file);
        }
        
        
        /**
         * File::getMimeType()
         *
         * @param mixed $file
         * @return false|string
         */
        public static function getMimeType($file)
        {
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $file);
            finfo_close($finfo);
            
            return $mtype;
        }
        
        /**
         * File::readIni()
         *
         * @param mixed $file
         * @return false|mixed
         */
        public static function readIni($file = null)
        {
            if (empty($file)) {
                self::_errorHandler('directory-error', 'File does not exists. ' . $file);
                return false;
            }
            $result = parse_ini_file(realpath($file), true);
            $result = json_encode($result);
            return json_decode($result);
        }
        
        /**
         * File::writeIni()
         *
         * @param mixed $file
         * @param mixed $data
         * @param bool $sections
         * @return true
         */
        public static function writeIni($file = null, $data = array(), $sections = true)
        {
            
            $content = null;
            
            if ($sections) {
                foreach ($data as $section => $rows) {
                    $content .= '[' . $section . ']' . PHP_EOL;
                    $content = self::writeIniData($rows, $content);
                    $content .= PHP_EOL;
                }
            } else {
                $content = self::writeIniData($data, $content);
            }
            
            return self::writeToFile($file, trim($content));
        }
        
        /**
         * @param mixed $rows
         * @param string $content
         * @return string
         */
        private static function writeIniData(mixed $rows, string $content): string
        {
            foreach ($rows as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $v) {
                        $content .= $key . '[] = ' . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
                    }
                } elseif (empty($val)) {
                    $content .= $key . ' = ' . PHP_EOL;
                } else {
                    $content .= $key . ' = ' . (is_numeric($val) ? $val : '"' . $val . '"') . PHP_EOL;
                }
            }
            return $content;
        }
        
        /**
         * File::download()
         *
         * @param mixed $fileLocation
         * @param mixed $fileName
         * @param int $maxSpeed
         * @return false|void
         */
        public static function download($fileLocation, $fileName, $maxSpeed = 1024)
        {
            if (connection_status() != 0) {
                return (false);
            }
            $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $fileTypes = array();
            
            /* List of File Types */
            $fileTypes['pdf'] = 'application/pdf';
            $fileTypes['txt'] = 'text/plain';
            $fileTypes['exe'] = 'application/octet-stream';
            $fileTypes['zip'] = 'application/zip';
            $fileTypes['doc'] = 'application/msword';
            $fileTypes['xls'] = 'application/vnd.ms-excel';
            $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
            $fileTypes['gif'] = 'image/gif';
            $fileTypes['png'] = 'image/png';
            $fileTypes['jpeg'] = 'image/jpg';
            $fileTypes['jpg'] = 'image/jpg';
            $fileTypes['rar'] = 'application/rar';
            
            $fileTypes['ra'] = 'audio/x-pn-realaudio';
            $fileTypes['ram'] = 'audio/x-pn-realaudio';
            $fileTypes['ogg'] = 'audio/x-pn-realaudio';
            
            $fileTypes['wav'] = 'video/x-msvideo';
            $fileTypes['wmv'] = 'video/x-msvideo';
            $fileTypes['avi'] = 'video/x-msvideo';
            $fileTypes['asf'] = 'video/x-msvideo';
            $fileTypes['divx'] = 'video/x-msvideo';
            
            $fileTypes['mp3'] = 'audio/mpeg';
            $fileTypes['mp4'] = 'audio/mpeg';
            $fileTypes['mpeg'] = 'video/mpeg';
            $fileTypes['mpg'] = 'video/mpeg';
            $fileTypes['mpe'] = 'video/mpeg';
            $fileTypes['mov'] = 'video/quicktime';
            $fileTypes['swf'] = 'video/quicktime';
            $fileTypes['3gp'] = 'video/quicktime';
            $fileTypes['m4a'] = 'video/quicktime';
            $fileTypes['aac'] = 'video/quicktime';
            $fileTypes['m3u'] = 'video/quicktime';
            
            $contentType = $fileTypes[$extension];
            
            header("Cache-Control: public");
            header("Content-Transfer-Encoding: binary\n");
            header('Content-Type: ' . $contentType);
            
            $contentDisposition = 'attachment';
            
            if (str_contains($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
                $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
            }
            header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
            
            header("Accept-Ranges: bytes");
            $range = 0;
            $size = filesize($fileLocation);
            
            if (isset($_SERVER['HTTP_RANGE'])) {
                list(, $range) = explode("=", $_SERVER['HTTP_RANGE']);
                str_replace($range, "-", $range);
                $size2 = $size - 1;
                $new_length = $size - $range;
                header("HTTP/1.1 206 Partial Content");
                header("Content-Length: $new_length");
                header("Content-Range: bytes $range$size2/$size");
            } else {
                $size2 = $size - 1;
                header("Content-Range: bytes 0-$size2/$size");
                header("Content-Length: " . $size);
            }
            
            if ($size == 0) {
                die('Zero byte file! Aborting download');
            }
            
            $fp = fopen("$fileLocation", "rb");
            
            fseek($fp, $range);
            
            while (!feof($fp) and (connection_status() == 0)) {
                set_time_limit(0);
                print (fread($fp, 1024 * $maxSpeed));
                flush();
                @ob_flush();
                sleep(1);
            }
            fclose($fp);
            exit;
            
            return ((connection_status() == 0) and !connection_aborted());
        }
        
        /**
         * File::parseSQL()
         *
         * @param mixed $content
         * @return array
         */
        public static function parseSQL($content)
        {
            
            $sqlList = array();
            $lines = explode("\n", file_get_contents($content));
            $query = "";
            
            foreach ($lines as $sql_line) {
                $sql_line = trim($sql_line);
                if ($sql_line === "") {
                    continue;
                } else {
                    if (str_starts_with($sql_line, "--")) {
                        continue;
                    } else {
                        if (str_starts_with($sql_line, "#")) {
                            continue;
                        }
                    }
                    $query .= $sql_line;
                    if (preg_match("/(.*);/", $sql_line)) {
                        $query = trim($query);
                        $query = substr($query, 0, strlen($query) - 1);
                        $sqlList[] = $query . ';';
                        $query = "";
                    }
                }
                
            }
            return $sqlList;
        }
        
        /**
         * File::exists()
         *
         * @param mixed $file
         * @return bool
         */
        public static function exists($file)
        {
            
            return file_exists($file);
        }
        
        /**
         * File::_fixPath()
         *
         * @param mixed $path
         * @return array|string|string[]|null
         */
        public static function _fixPath($path)
        {
            $path = str_replace('\\', '/', $path);
            return preg_replace("#/+#", "/", $path);
        }
        
        /**
         * File::_findFilesRecursive()
         *
         * @param mixed $dir
         * @param mixed $base
         * @param mixed $fileTypes
         * @param mixed $exclude
         * @param mixed $level
         * @param string $returnType
         * @return array
         */
        protected static function _findFilesRecursive($dir, $base, $fileTypes, $exclude, $level, $returnType = 'fileOnly')
        {
            $list = array();
            if ($hdir = opendir($dir)) {
                while (($file = readdir($hdir)) !== false) {
                    if ($file === '.' || $file === '..')
                        continue;
                    $path = $dir . '/' . $file;
                    $isFile = is_file($path);
                    if (self::_validatePath($base, $file, $isFile, $fileTypes, $exclude)) {
                        if ($isFile) {
                            $list[] = ($returnType == 'fileOnly') ? $file : $path;
                        } else
                            if ($level) {
                                $list = array_merge($list, self::_findFilesRecursive($path, $base . '/' . $file, $fileTypes, $exclude, $level - 1, $returnType));
                            }
                    }
                }
            }
            closedir($hdir);
            return $list;
        }
        
        /**
         * File::validateDirectory()
         *
         * @param mixed $basepath
         * @param mixed $userpath
         * @return mixed|string
         */
        public static function validateDirectory($basepath, $userpath)
        {
            
            $realBase = realpath($basepath);
            $userpath = $basepath . $userpath;
            $realUserPath = realpath($userpath);
            
            return ($realUserPath === false || !str_starts_with($realUserPath, $realBase)) ? $basepath : $userpath;
        }
        
        /**
         * File::_validatePath()
         *
         * @param mixed $base
         * @param mixed $file
         * @param mixed $isFile
         * @param mixed $fileTypes
         * @param mixed $exclude
         * @return bool
         */
        protected static function _validatePath($base, $file, $isFile, $fileTypes, $exclude)
        {
            foreach ($exclude as $e) {
                if ($file === $e || str_starts_with($base . '/' . $file, $e))
                    return false;
            }
            if (!$isFile || empty($fileTypes))
                return true;
            if (($type = pathinfo($file, PATHINFO_EXTENSION)) !== '') {
                return in_array($type, $fileTypes);
            } else {
                return false;
            }
        }
        
        /**
         * File::_errorHandler()
         *
         * @param string $msgType
         * @param string $msg
         * @return void
         */
        private static function _errorHandler($msgType = '', $msg = '')
        {
            if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
                $err = error_get_last();
                if (isset($err['message']) && $err['message'] != '') {
                    $lastError = $err['message'] . ' | file: ' . $err['file'] . ' | line: ' . $err['line'];
                    $errorMsg = ($lastError) ?: $msg;
                    Debug::addMessage('errors', $msgType, $errorMsg, 'session');
                    @trigger_error('');
                }
            }
        }
        
    }