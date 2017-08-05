<?php

namespace AdminBundle\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $image = $this->load($file);
        if ($image['type'] == IMAGETYPE_PNG) {
            return $this->save($image['image'], $image['type']);
        }
        $newImage = $this->crop($image);
        return $this->save($newImage, $image['type']);
    }

    private function load($file)
    {
        $imageInfo = getimagesize($file);
        $imageType = $imageInfo[2];
        if ($imageType == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($file);
        } elseif ($imageType == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($file);
        } else {
            throw new \Exception('Wrong image type.');
        }
        return [
            'image' => $image,
            'type' => $imageType,
            'width' => $imageInfo[0],
            'height' => $imageInfo[1]
        ];
    }

    private function save($image, $type, $compression = 75)
    {
        if ($type == IMAGETYPE_JPEG) {
            $fileName = md5(uniqid()) . '.jpg';
            imagejpeg($image, $this->targetDir . '/' . $fileName, $compression);
        } elseif ($type == IMAGETYPE_PNG) {
            $fileName = md5(uniqid()) . '.png';
            imagepng($image, $this->targetDir . '/' . $fileName);
        } else {
            throw new \Exception('Wrong image type.');
        }
        return $fileName;
    }

    private function crop($image)
    {
        $newHeight = round($image['width'] / 1.6);
        $newWidth = round($image['height'] * 1.6);
        if ($newHeight <= $image['height']) {
            $newImage = imagecreatetruecolor($image['width'], $newHeight);
            imagecopyresampled(
                $newImage,
                $image['image'],
                0,
                0,
                0,
                round($image['height'] / 2 - $newHeight / 2),
                $image['width'],
                $newHeight,
                $image['width'],
                $newHeight
            );
        } else {
            $newImage = imagecreatetruecolor($newWidth, $image['height']);
            imagecopyresampled(
                $newImage, $image['image'],
                0,
                0,
                round($image['width'] / 2 - $newWidth / 2),
                0,
                $image['width'],
                $newHeight,
                $image['width'],
                $newHeight
            );
        }
        return $newImage;
    }
}