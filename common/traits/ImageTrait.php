<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 26.12.2018
 * Time: 18:55
 */

namespace common\traits;


use finfo;
use yii\helpers\ArrayHelper;

trait ImageTrait
{
    public function deleteSubFolders($path)
    {
        $empty=true;
        foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file)
        {
            $empty &= is_dir($file) && $this->deleteSubFolders($file);
        }
        return $empty && rmdir($path);
    }

    public function deleteImage(?string $photo)
    {
        $path = \Yii::getAlias('@storage').'/web/source/'.$photo;
        if(file_exists($path)){
            unlink($path);
            $firstFolder = explode('/',$photo)[0];
            $this->deleteSubFolders(\Yii::getAlias('@storage').'/web/source/'.$firstFolder);
        }

    }

    public function resize_image_jpeg($file, $w, $h, $byBig = false):bool
    {

        list($file, $width, $height, $proportion) = $this->getPreparedVariables($file);
        list($newwidth, $newheight) = $this->getConfig($w, $h, $byBig, $proportion, $height, $width);
        $dst = $this->resizeByConfig($file, $newwidth, $newheight, $width, $height);
        return imagejpeg($dst, $file, 100);
    }

    public function resize_image_png($file, $w, $h, $byBig = false):bool
    {

        list($file, $width, $height, $proportion) = $this->getPreparedVariables($file);
        list($newwidth, $newheight) = $this->getConfig($w, $h, $byBig, $proportion, $height, $width);
        $dst = $this->resizeByConfig($file, $newwidth, $newheight, $width, $height);
        return imagepng($dst, $file, 9);
    }

    public function fixOrientation($orientation,$dst)
    {
        if(is_null($orientation)){
            return $dst;
        }
        switch($orientation) {
            case 3:
                $dst = imagerotate($dst, 180, 0);
                break;
            case 6:
                $dst = imagerotate($dst, -90, 0);
                break;
            case 8:
                $dst = imagerotate($dst, 90, 0);
                break;
        }

        return $dst;
    }

    /**
     * @param $file
     * @return array
     */
    private function prepareImage($file): array
    {
        list($exif, $src) = $this->getSrc($file);
        $fixedOrientation = $this->fixOrientation(ArrayHelper::getValue($exif, 'Orientation'), $src);
        if(!is_null($exif)){
            imagejpeg($fixedOrientation, $file, 100);
        }
        return array($src, $exif, $file);
    }

    /**
     * @param $w
     * @param $h
     * @param $proportion
     * @return array
     */
    private function getConfigByBigSide($w, $h, $proportion): array
    {
        if ($w / $h > $proportion) {
            $newwidth = $h * $proportion;
            $newheight = $h;
        } else {
            $newheight = $w / $proportion;
            $newwidth = $w;
        }
        return array($newwidth, $newheight);
    }

    /**
     * @param $w
     * @param $h
     * @param $height
     * @param $width
     * @return array
     */
    private function getConfigByParams($w, $h, $height, $width): array
    {
        if ($w && $h) {
            $newheight = $h;
            $newwidth = $w;
        } elseif ($w && !$h) {
            $newheight = $height / ($width / $w);
            $newwidth = $w;
        } elseif ($h && !$w) {
            $newheight = $h;
            $newwidth = $width / ($height / $h);
        }
        return array($newheight, $newwidth);
    }

    /**
     * @param $file
     * @param $newwidth
     * @param $newheight
     * @param $width
     * @param $height
     * @return resource
     */
    private function resizeByConfig($file, $newwidth, $newheight, $width, $height)
    {
        list($exif,$src) = $this->getSrc($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($dst,false);
        imagesavealpha($dst,true);
        $transparentindex = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefill($dst, 0, 0, $transparentindex);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        $dst = $this->fixOrientation(ArrayHelper::getValue($exif, 'Orientation'), $dst);
        return $dst;
    }

    /**
     * @param $width
     * @param $height
     * @return float|int
     */
    private function getProportion($width, $height)
    {
        return $width / $height;
    }

    /**
     * @param $file
     * @return array
     */
    private function getPreparedVariables($file): array
    {
        list($src, $exif, $file) = $this->prepareImage($file);
        list($width, $height) = getimagesize($file);
        $proportion = $this->getProportion($width, $height);
        return array($file, $width, $height, $proportion);
    }

    /**
     * @param $w
     * @param $h
     * @param $byBig
     * @param $proportion
     * @param $height
     * @param $width
     * @return array
     */
    private function getConfig($w, $h, $byBig, $proportion, $height, $width): array
    {
        switch ($byBig) {
            case true:
                list($newwidth, $newheight) = $this->getConfigByBigSide($w, $h, $proportion);
                break;
            default:
                list($newheight, $newwidth) = $this->getConfigByParams($w, $h, $height, $width);
                break;
        }
        return array($newwidth, $newheight);
    }

    /**
     * @param $file
     * @return array
     */
    private function getSrc($file):array
    {
        $file_info = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $file_info->buffer(file_get_contents($file));
        if ($mime_type == 'image/png') {
            $src = imagecreatefrompng($file);
            $exif = null;

        } else {
            $src = imagecreatefromjpeg($file);
            $exif = exif_read_data($file);

        }
        return [$exif,$src];
    }
}