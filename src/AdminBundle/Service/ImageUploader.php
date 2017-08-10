<?php

namespace AdminBundle\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function upload(UploadedFile $file, string $targetDir, float $ratio = 1.6)
    {
        $image = $this->load($file);
        if ($image['type'] == IMAGETYPE_PNG) {
            $name = md5(uniqid()) . '.png';
            $file->move($targetDir, $name);
            return $name;
        }
        $newImage = $this->crop($image, $ratio);
        return $this->save($newImage, $image['type'], $targetDir);
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

    private function save($image, $type, $targetDir)
    {
        if ($type == IMAGETYPE_JPEG) {
            $fileName = md5(uniqid()) . '.jpg';
            imagejpeg($image, $targetDir . '/' . $fileName, 75);
        } elseif ($type == IMAGETYPE_PNG) {
            $fileName = md5(uniqid()) . '.png';
            imagepng($image, $targetDir . '/' . $fileName);
        } else {
            throw new \Exception('Wrong image type.');
        }
        return $fileName;
    }

    private function crop($image, $ratio)
    {
        $newHeight = round($image['width'] / $ratio);
        $newWidth = round($image['height'] * $ratio);
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