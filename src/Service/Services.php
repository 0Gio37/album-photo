<?php

namespace App\Service;

use App\Entity\Photo;
use App\Repository\PhotoRepository;

class Services
{
    /*public function Slider(Photo $photoDisplayed, PhotoRepository $photoRepository,  int $currentAlbumId): array
    {
        $newArray = [];
        array_push($newArray,$photoDisplayed);
        $allPhotosFromAlbumParent = $photoRepository->findBy(['album'=>$currentAlbumId]);
        foreach ($allPhotosFromAlbumParent as $photo){
            if (!in_array($photo, $newArray))
            {
                array_push($newArray, $photo);
            }}
        return $newArray;
    }*/



}