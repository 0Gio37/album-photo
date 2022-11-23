<?php

namespace App\Service;

use App\Entity\Photo;
use App\Repository\CommentaireRepository;
use App\Repository\PhotoRepository;

class Services
{
    public function SliderDetailsPhoto(PhotoRepository $photoRepository,String $status,int $albumIDofCurrentPhoto, $createdDateOfCurrentPhoto):Photo{
        $photoToDisplay = null;
        switch ($status){
            case "prev-photo":
                if($photoRepository->findSinglePhotoYounger($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto)){
                    $photoToDisplay = ($photoRepository->findSinglePhotoYounger($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto))[0];
                }else{
                    //false if current photo displayed is the oldest photo
                    $photoToDisplay = $photoRepository->findBy(['album'=>$albumIDofCurrentPhoto], ['createAt'=>'DESC'], 1)[0];
                }
                break;
            case "next-photo":
                if($photoRepository->findSinglePhotoOlder($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto)){
                    $photoToDisplay = ($photoRepository->findSinglePhotoOlder($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto))[0];
                }else{
                    //false if current photo displayed is the youngest photo
                    $photoToDisplay = $photoRepository->findBy(['album'=>$albumIDofCurrentPhoto], ['createAt'=>'ASC'], 1)[0];
                }
                break;
        }
        return $photoToDisplay;
    }
}