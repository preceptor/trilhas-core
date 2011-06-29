<?php
/**
 * Trilhas - Learning Management System
 * Copyright (C) 2005-2010  Preceptor Educação a Distância Ltda. <http://www.preceptoead.com.br>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @author Mohamed Alsharaf
 * @author Abdala Cequeira <abdala.cerqueira@gmail.com>
 *
 */
class Tri_View_Helper_Thumbnail extends Zend_View_Helper_Abstract
{
    private $_name   = null;
    private $_width  = null;
    private $_height = null;
    private $_type   = array('small'  => array('width' => 40, 'height' => 40),
                             'medium' => array('width' => 200, 'height' => 200));

    const IMAGETYPE_GIF  = 'image/gif';
    const IMAGETYPE_JPG  = 'image/jpg';
    const IMAGETYPE_JPEG = 'image/jpeg';
    const IMAGETYPE_PNG  = 'image/png';
    const NO_IMAGE = "noimage";

    public function thumbnail($path, $type)
    {
        $uploadDir = Tri_Config::get('tri_upload_dir');
        $thumb = $uploadDir . $path . $type . '.jpg';
        if (!file_exists($thumb)) {
            $this->_open($path);
            if ($this->_image == self::NO_IMAGE) {
                return $type . '.jpg';
            }
            $this->_resize($this->_type[$type]['width'], $this->_type[$type]['height']);
            imagejpeg($this->_image, $thumb, 70);
        }
        return $path . $type . '.jpg';
    }

    protected function _setInfo($path)
    {
        $imgSize = @getimagesize($path);
        if(!$imgSize || ($imgSize[0] == 0 || $imgSize[1] == 0)) {
            $this->_width    = self::NO_IMAGE;
            $this->_height   = self::NO_IMAGE;
            $this->_mimeType = self::NO_IMAGE;
            return;
        } 
        $this->_width    = $imgSize[0];
        $this->_height   = $imgSize[1];
        $this->_mimeType = $imgSize['mime'];
    }

    protected function _setDimension($forDim, $maxWidth, $maxHeight)
    {
        if ($this->_width > $maxWidth) {
            $ration = $maxWidth/$this->_width;
            $newwidth = round($this->_width*$ration);
            $newheight = round($this->_height*$ration);

            if ($newheight > $maxHeight) {
                $ration = $maxHeight/$newheight;
                $newwidth = round($newwidth*$ration);
                $newheight = round($newheight*$ration);

                if ($forDim == 'w') {
                    return $newwidth;
                } else {
                    return $newheight;
                }
            } else {
                if ($forDim == 'w') {
                    return $newwidth;
                } else {
                    return $newheight;
                }
            }
        } else if ($this->_height > $maxHeight) {
            $ration = $maxHeight/$this->_height;
            $newwidth = round($this->_width*$ration);
            $newheight = round($this->_height*$ration);
            if ($newwidth > $maxWidth) {
                $ration = $maxWidth/$newwidth;
                $newwidth = round($newwidth*$ration);
                $newheight = round($newheight*$ration);
                if ($forDim == 'w') {
                    return $newwidth;
                } else {
                    return $newheight;
                }
            } else {
                if ($forDim == 'w') {
                    return $newwidth;
                } else {
                    return $newheight;
                }
            }
        } else {
            if($forDim == 'w') {
                return $this->_width;
            } else {
                return $this->_height;
            }
        }
    }

    protected function _open($path)
    {
        $uploadDir = Tri_Config::get('tri_upload_dir');
        $imagePath = $uploadDir . $path;
        
        if (!file_exists($imagePath)) {
            $imagePath = APPLICATION_PATH . '/../data/upload/' . $path;
        }
        
        $this->_setInfo($imagePath);
        
        switch($this->_mimeType) {
            case self::IMAGETYPE_GIF:
                $this->_image = imagecreatefromgif($imagePath);
                break;
            case self::IMAGETYPE_JPEG:
            case self::IMAGETYPE_JPG:
                $this->_image = imagecreatefromjpeg($imagePath);
                break;
            case self::IMAGETYPE_PNG:
                $this->_image = imagecreatefrompng($imagePath);
                break;
            default:
                $this->_image = self::NO_IMAGE;
                break;
        }
    }

    public function _resize($maxWidth, $maxHeight)
    {
        $newWidth  = $this->_setDimension('w', $maxWidth, $maxHeight);
        $newHeight = $this->_setDimension('h', $maxWidth, $maxHeight);

        $newImage   = imagecreatetruecolor($newWidth, $newHeight);
        $background = imagecolorallocate($newImage, 0, 0, 0);

        imagecolortransparent($newImage, $background);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        imagecopyresampled($newImage, $this->_image, 0, 0, 0, 0, $newWidth, $newHeight, $this->_width, $this->_height);

        $this->_image = $newImage;
    }
}