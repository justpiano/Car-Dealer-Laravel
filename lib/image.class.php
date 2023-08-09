<?php
    /**
     * Class Image
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: image.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class Image
    {
        const CROPTOP = 1;
        const CROPCENTRE = 2;
        const CROPCENTER = 2;
        const CROPBOTTOM = 3;
        const CROPLEFT = 4;
        const CROPRIGHT = 5;
        const CROPTOPCENTER = 6;
        const IMG_FLIP_HORIZONTAL = 0;
        const IMG_FLIP_VERTICAL = 1;
        const IMG_FLIP_BOTH = 2;
        
        public $quality_jpg = 85;
        public $quality_webp = 85;
        public $quality_png = 9;
        public $quality_truecolor = true;
        public $gamma_correct = false;
        public $interlace = 1;
        public $source_type;
        protected $source_image;
        protected $original_w;
        protected $original_h;
        protected $dest_x = 0;
        protected $dest_y = 0;
        protected $source_x;
        protected $source_y;
        protected $dest_w;
        protected $dest_h;
        protected $source_w;
        protected $source_h;
        protected $source_info;
        protected $filters = [];
        
        
        /**
         * Image::__construct()
         *
         * @param mixed $filename
         */
        public function __construct($filename)
        {
            if (!defined('IMAGETYPE_WEBP')) {
                define('IMAGETYPE_WEBP', 18);
            }
            
            if (!defined('IMAGETYPE_BMP')) {
                define('IMAGETYPE_BMP', 6);
            }
            
            if ($filename === null || empty($filename) || (!str_starts_with($filename, 'data:') && !is_file($filename))) {
                Debug::AddMessage("errors", '<i>Exception</i>', 'File does not exist', "session");
            }
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $checkWebp = false;
            if (!str_contains(finfo_file($finfo, $filename), 'image')) {
                if (version_compare(PHP_VERSION, '7.0.0', '<=') && str_contains(file_get_contents($filename), 'WEBPVP8')) {
                    $checkWebp = true;
                    $this->source_type = IMAGETYPE_WEBP;
                } else {
                    Debug::AddMessage("errors", '<i>Exception</i>', 'Unsupported file type', "session");
                }
            } elseif (str_contains(finfo_file($finfo, $filename), 'image/webp')) {
                $checkWebp = true;
                $this->source_type = IMAGETYPE_WEBP;
            }
            
            if (!$image_info = getimagesize($filename, $this->source_info)) {
                $image_info = getimagesize($filename);
            }
            
            if (!$checkWebp) {
                if (!$image_info) {
                    if (str_contains(finfo_file($finfo, $filename), 'image')) {
                        Debug::AddMessage("errors", '<i>Exception</i>', 'Unsupported image type', "session");
                    }
                    
                    Debug::AddMessage("errors", '<i>Exception</i>', 'Could not read file', "session");
                }
                
                $this->original_w = $image_info[0];
                $this->original_h = $image_info[1];
                $this->source_type = $image_info[2];
            }
            
            switch ($this->source_type) {
                case IMAGETYPE_GIF:
                    $this->source_image = imagecreatefromgif($filename);
                    break;
                
                case IMAGETYPE_JPEG:
                    $this->source_image = $this->imageCreateJpegfromExif($filename);
                    $this->original_w = imagesx($this->source_image);
                    $this->original_h = imagesy($this->source_image);
                    break;
                
                case IMAGETYPE_PNG:
                    $this->source_image = imagecreatefrompng($filename);
                    break;
                
                case IMAGETYPE_WEBP:
                    $this->source_image = imagecreatefromwebp($filename);
                    $this->original_w = imagesx($this->source_image);
                    $this->original_h = imagesy($this->source_image);
                    break;
                
                case IMAGETYPE_BMP:
                    if (version_compare(PHP_VERSION, '7.2.0', '<')) {
                        Debug::AddMessage("errors", '<i>Exception</i>', 'For bmp support PHP >= 7.2.0 is required', "session");
                    }
                    $this->source_image = imagecreatefrombmp($filename);
                    break;
                
                default:
                    Debug::AddMessage("errors", '<i>Exception</i>', 'Unsupported image type', "session");
            }
            
            if (!$this->source_image) {
                Debug::AddMessage("errors", '<i>Exception</i>', 'Could not load image', "session");
            }
            
            finfo_close($finfo);
            
            return $this->resize($this->getSourceWidth(), $this->getSourceHeight());
        }
        
        /**
         * Image::createFromString()
         *
         * @param mixed $image_data
         * @return Image
         */
        public static function createFromString($image_data)
        {
            if (empty($image_data) || $image_data === null) {
                Debug::AddMessage("errors", '<i>Exception</i>', 'image_data must not be empty', "session");
            }
            return new self('data://application/octet-stream;base64,' . base64_encode($image_data));
        }
        
        
        /**
         * Image::addFilter()
         *
         * @param mixed $filter
         * @return Image
         */
        public function addFilter(callable $filter)
        {
            $this->filters[] = $filter;
            return $this;
        }
        
        /**
         * Image::applyFilter()
         *
         * @param mixed $image
         * @param mixed $filterType
         * @return void
         */
        protected function applyFilter($image, $filterType = IMG_FILTER_NEGATE)
        {
            foreach ($this->filters as $function) {
                $function($image, $filterType);
            }
        }
        
        
        /**
         * Image::imageCreateJpegfromExif()
         *
         * @param mixed $filename
         * @return false|GdImage|resource
         */
        public function imageCreateJpegfromExif($filename)
        {
            $img = imagecreatefromjpeg($filename);
            
            if (!function_exists('exif_read_data') || !isset($this->source_info['APP1']) || !str_starts_with($this->source_info['APP1'], 'Exif')) {
                return $img;
            }
            
            try {
                $exif = @exif_read_data($filename);
            } catch (exception) {
                $exif = null;
            }
            
            if (!$exif || !isset($exif['Orientation'])) {
                return $img;
            }
            
            $orientation = $exif['Orientation'];
            
            if ($orientation === 6 || $orientation === 5) {
                $img = imagerotate($img, 270, 0);
            } elseif ($orientation === 3 || $orientation === 4) {
                $img = imagerotate($img, 180, 0);
            } elseif ($orientation === 8 || $orientation === 7) {
                $img = imagerotate($img, 90, 0);
            }
            
            if ($orientation === 5 || $orientation === 4 || $orientation === 7) {
                imageflip($img, IMG_FLIP_HORIZONTAL);
            }
            
            return $img;
        }
        
        /**
         * Image::save()
         *
         * @param mixed $filename
         * @param mixed $image_type
         * @param mixed $quality
         * @param mixed $permissions
         * @param bool $exact_size
         * @return Image
         */
        public function save($filename, $image_type = null, $quality = null, $permissions = null, $exact_size = false)
        {
            $image_type = $image_type ?: $this->source_type;
            $quality = is_numeric($quality) ? (int)abs($quality) : null;
            
            switch ($image_type) {
                case IMAGETYPE_GIF:
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                    }
                    
                    $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 1);
                    imagecolortransparent($dest_image, $background);
                    imagefill($dest_image, 0, 0, $background);
                    imagesavealpha($dest_image, true);
                    break;
                
                case IMAGETYPE_JPEG:
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $exact_size[0], $exact_size[1], $background);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                    }
                    break;
                
                case IMAGETYPE_WEBP:
                    if (version_compare(PHP_VERSION, '5.5.0', '<')) {
                        Debug::AddMessage("errors", '<i>Exception</i>', 'For WebP support PHP >= 5.5.0 is required', "session");
                    }
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $exact_size[0], $exact_size[1], $background);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                    }
                    
                    imagealphablending($dest_image, false);
                    imagesavealpha($dest_image, true);
                    
                    break;
                
                case IMAGETYPE_PNG:
                    if (!$this->quality_truecolor || !imageistruecolor($this->source_image)) {
                        if (!empty($exact_size) && is_array($exact_size)) {
                            $dest_image = imagecreate($exact_size[0], $exact_size[1]);
                        } else {
                            $dest_image = imagecreate($this->getDestWidth(), $this->getDestHeight());
                        }
                    } else {
                        if (!empty($exact_size) && is_array($exact_size)) {
                            $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        } else {
                            $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        }
                    }
                    
                    imagealphablending($dest_image, false);
                    imagesavealpha($dest_image, true);
                    
                    $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 127);
                    imagecolortransparent($dest_image, $background);
                    imagefill($dest_image, 0, 0, $background);
                    break;
                
                case IMAGETYPE_BMP:
                    if (version_compare(PHP_VERSION, '7.2.0', '<')) {
                        Debug::AddMessage("errors", '<i>Exception</i>', 'For WebP support PHP >= 7.2.0 is required', "session");
                    }
                    
                    if (!empty($exact_size) && is_array($exact_size)) {
                        $dest_image = imagecreatetruecolor($exact_size[0], $exact_size[1]);
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $exact_size[0], $exact_size[1], $background);
                    } else {
                        $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
                        $background = imagecolorallocate($dest_image, 255, 255, 255);
                        imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                    }
                    break;
            }
            
            imageinterlace($dest_image, $this->interlace);
            
            if ($this->gamma_correct) {
                imagegammacorrect($this->source_image, 2.2, 1.0);
            }
            
            if (!empty($exact_size) && is_array($exact_size)) {
                if ($this->getSourceHeight() < $this->getSourceWidth()) {
                    $this->dest_x = 0;
                    $this->dest_y = ($exact_size[1] - $this->getDestHeight()) / 2;
                }
                if ($this->getSourceHeight() > $this->getSourceWidth()) {
                    $this->dest_x = ($exact_size[0] - $this->getDestWidth()) / 2;
                    $this->dest_y = 0;
                }
            }
            
            imagecopyresampled($dest_image, $this->source_image, $this->dest_x, $this->dest_y, $this->source_x, $this->source_y, $this->getDestWidth(), $this->getDestHeight(), $this->source_w, $this->source_h);
            
            if ($this->gamma_correct) {
                imagegammacorrect($dest_image, 1.0, 2.2);
            }
            
            $this->applyFilter($dest_image);
            
            switch ($image_type) {
                case IMAGETYPE_GIF:
                    imagegif($dest_image, $filename);
                    break;
                
                case IMAGETYPE_JPEG:
                    if ($quality === null || $quality > 100) {
                        $quality = $this->quality_jpg;
                    }
                    
                    imagejpeg($dest_image, $filename, $quality);
                    break;
                
                case IMAGETYPE_WEBP:
                    if (version_compare(PHP_VERSION, '5.5.0', '<')) {
                        Debug::AddMessage("errors", '<i>Exception</i>', 'For WebP support PHP >= 5.5.0 is required', "session");
                    }
                    if ($quality === null) {
                        $quality = $this->quality_webp;
                    }
                    
                    imagewebp($dest_image, $filename, $quality);
                    break;
                
                case IMAGETYPE_PNG:
                    if ($quality === null || $quality > 9) {
                        $quality = $this->quality_png;
                    }
                    
                    imagepng($dest_image, $filename, $quality);
                    break;
                
                case IMAGETYPE_BMP:
                    imagebmp($dest_image, $filename, $quality);
                    break;
            }
            
            if ($permissions) {
                chmod($filename, $permissions);
            }
            
            imagedestroy($dest_image);
            
            return $this;
        }
        
        /**
         * Image::getImageAsString()
         *
         * @param mixed $image_type
         * @param mixed $quality
         * @return false|string
         */
        public function getImageAsString($image_type = null, $quality = null)
        {
            $string_temp = tempnam(sys_get_temp_dir(), '');
            $this->save($string_temp, $image_type, $quality);
            $string = file_get_contents($string_temp);
            unlink($string_temp);
            
            return $string;
        }
        
        /**
         * Image::__toString()
         *
         * @return false|string
         */
        public function __toString()
        {
            return $this->getImageAsString();
        }
        
        /**
         * Image::output()
         *
         * @param mixed $image_type
         * @param mixed $quality
         * @return void
         */
        public function output($image_type = null, $quality = null)
        {
            $image_type = $image_type ?: $this->source_type;
            header('Content-Type: ' . image_type_to_mime_type($image_type));
            $this->save(null, $image_type, $quality);
        }
        
        /**
         * Image::resizeToShortSide()
         *
         * @param mixed $max_short
         * @param bool $allow_enlarge
         * @return Image
         */
        public function resizeToShortSide($max_short, $allow_enlarge = false)
        {
            if ($this->getSourceHeight() < $this->getSourceWidth()) {
                $ratio = $max_short / $this->getSourceHeight();
                $long = (int)($this->getSourceWidth() * $ratio);
                $this->resize($long, $max_short, $allow_enlarge);
            } else {
                $ratio = $max_short / $this->getSourceWidth();
                $long = (int)($this->getSourceHeight() * $ratio);
                $this->resize($max_short, $long, $allow_enlarge);
            }
            
            return $this;
        }
        
        /**
         * Image::resizeToLongSide()
         *
         * @param mixed $max_long
         * @param bool $allow_enlarge
         * @return Image
         */
        public function resizeToLongSide($max_long, $allow_enlarge = false)
        {
            if ($this->getSourceHeight() > $this->getSourceWidth()) {
                $ratio = $max_long / $this->getSourceHeight();
                $short = (int)($this->getSourceWidth() * $ratio);
                $this->resize($short, $max_long, $allow_enlarge);
            } else {
                $ratio = $max_long / $this->getSourceWidth();
                $short = (int)($this->getSourceHeight() * $ratio);
                $this->resize($max_long, $short, $allow_enlarge);
            }
            
            return $this;
        }
        
        /**
         * Image::resizeToHeight()
         *
         * @param mixed $height
         * @param bool $allow_enlarge
         * @return Image
         */
        public function resizeToHeight($height, $allow_enlarge = false)
        {
            $ratio = $height / $this->getSourceHeight();
            $width = (int)($this->getSourceWidth() * $ratio);
            $this->resize($width, $height, $allow_enlarge);
            
            return $this;
        }
        
        /**
         * Image::resizeToWidth()
         *
         * @param mixed $width
         * @param bool $allow_enlarge
         * @return Image
         */
        public function resizeToWidth($width, $allow_enlarge = false)
        {
            $ratio = $width / $this->getSourceWidth();
            $height = (int)($this->getSourceHeight() * $ratio);
            $this->resize($width, $height, $allow_enlarge);
            
            return $this;
        }
        
        /**
         * Image::bestFit()
         *
         * @param mixed $max_width
         * @param mixed $max_height
         * @param bool $allow_enlarge
         * @return Image
         */
        public function bestFit($max_width, $max_height, $allow_enlarge = false)
        {
            if ($this->getSourceWidth() <= $max_width && $this->getSourceHeight() <= $max_height && $allow_enlarge === false) {
                return $this;
            }
            
            $ratio = $this->getSourceHeight() / $this->getSourceWidth();
            $width = $max_width;
            $height = (int)($width * $ratio);
            
            if ($height > $max_height) {
                $height = $max_height;
                $width = (int)($height / $ratio);
            }
            
            return $this->resize($width, $height, $allow_enlarge);
        }
        
        /**
         * Image::thumbnail()
         *
         * @param mixed $width
         * @param mixed $height
         * @param mixed $allow_enlarge
         * @return Image
         */
        public function thumbnail($width, $height, $allow_enlarge = false)
        {
            $height = $height ?: $width;
            
            // Determine aspect ratios
            $current_aspect_ratio = $this->getSourceHeight() / $this->getSourceWidth();
            $new_aspect_ratio = $height / $width;
            
            // Fit to height/width
            if ($new_aspect_ratio > $current_aspect_ratio) {
                $this->resizeToHeight($height);
            } else {
                $this->resizeToWidth($width);
            }
            //$left = floor(($this->getSourceHeight() / 2) - ($width / 2));
            //$top = floor(($this->getSourceWidth() / 2) - ($height / 2));
            
            return $this->resize($width, $height, $allow_enlarge);
        }
        
        /**
         * Image::scale()
         *
         * @param mixed $scale
         * @return Image
         */
        public function scale($scale)
        {
            $width = (int)($this->getSourceWidth() * $scale / 100);
            $height = (int)($this->getSourceHeight() * $scale / 100);
            $this->resize($width, $height, true);
            
            return $this;
        }
        
        /**
         * Image::resize()
         *
         * @param mixed $width
         * @param mixed $height
         * @param bool $allow_enlarge
         * @return Image
         */
        public function resize($width, $height, $allow_enlarge = false)
        {
            if (!$allow_enlarge) {
                if ($width > $this->getSourceWidth() || $height > $this->getSourceHeight()) {
                    $width = $this->getSourceWidth();
                    $height = $this->getSourceHeight();
                }
            }
            
            $this->source_x = 0;
            $this->source_y = 0;
            
            $this->dest_w = $width;
            $this->dest_h = $height;
            
            $this->source_w = $this->getSourceWidth();
            $this->source_h = $this->getSourceHeight();
            
            return $this;
        }
        
        /**
         * Image::crop()
         *
         * @param mixed $width
         * @param mixed $height
         * @param bool $allow_enlarge
         * @param mixed $position
         * @return Image
         */
        public function crop($width, $height, $allow_enlarge = false, $position = self::CROPCENTER)
        {
            if (!$allow_enlarge) {
                if ($width > $this->getSourceWidth()) {
                    $width = $this->getSourceWidth();
                }
                
                if ($height > $this->getSourceHeight()) {
                    $height = $this->getSourceHeight();
                }
            }
            
            $ratio_source = $this->getSourceWidth() / $this->getSourceHeight();
            $ratio_dest = $width / $height;
            
            if ($ratio_dest < $ratio_source) {
                $this->resizeToHeight($height, $allow_enlarge);
                
                $excess_width = (int)(($this->getDestWidth() - $width) * $this->getSourceWidth() / $this->getDestWidth());
                
                $this->source_w = $this->getSourceWidth() - $excess_width;
                $this->source_x = $this->getCropPosition($excess_width, $position);
                
                $this->dest_w = $width;
            } else {
                $this->resizeToWidth($width, $allow_enlarge);
                
                $excess_height = (int)(($this->getDestHeight() - $height) * $this->getSourceHeight() / $this->getDestHeight());
                
                $this->source_h = $this->getSourceHeight() - $excess_height;
                $this->source_y = $this->getCropPosition($excess_height, $position);
                
                $this->dest_h = $height;
            }
            
            return $this;
        }
        
        /**
         * Image::freecrop()
         *
         * @param mixed $width
         * @param mixed $height
         * @param bool $x
         * @param bool $y
         * @return Image
         */
        public function freecrop($width, $height, $x = false, $y = false)
        {
            if ($x === false || $y === false) {
                return $this->crop($width, $height);
            }
            $this->source_x = $x;
            $this->source_y = $y;
            if ($width > $this->getSourceWidth() - $x) {
                $this->source_w = $this->getSourceWidth() - $x;
            } else {
                $this->source_w = $width;
            }
            
            if ($height > $this->getSourceHeight() - $y) {
                $this->source_h = $this->getSourceHeight() - $y;
            } else {
                $this->source_h = $height;
            }
            
            $this->dest_w = $width;
            $this->dest_h = $height;
            
            return $this;
        }
        
        /**
         * Image::getSourceWidth()
         *
         * @return false|int|mixed
         */
        public function getSourceWidth()
        {
            return $this->original_w;
        }
        
        /**
         * Image::getSourceHeight()
         *
         * @return false|int|mixed
         */
        public function getSourceHeight()
        {
            return $this->original_h;
        }
        
        /**
         * Image::getDestWidth()
         *
         * @return mixed
         */
        public function getDestWidth()
        {
            return $this->dest_w;
        }
        
        /**
         * Image::getDestHeight()
         *
         * @return mixed
         */
        public function getDestHeight()
        {
            return $this->dest_h;
        }
        
        /**
         * Image::getCropPosition()
         *
         * @param mixed $expectedSize
         * @param mixed $position
         * @return int
         */
        protected function getCropPosition($expectedSize, $position = self::CROPCENTER)
        {
            $size = 0;
            switch ($position) {
                case self::CROPBOTTOM:
                case self::CROPRIGHT:
                    $size = $expectedSize;
                    break;
                case self::CROPCENTER:
                case self::CROPCENTRE:
                    $size = $expectedSize / 2;
                    break;
                case self::CROPTOPCENTER:
                    $size = $expectedSize / 4;
                    break;
            }
            return (int)$size;
        }
        
        /**
         * Image::gamma()
         *
         * @param bool $enable
         * @return Image
         */
        public function gamma($enable = false)
        {
            $this->gamma_correct = $enable;
            
            return $this;
        }
    }