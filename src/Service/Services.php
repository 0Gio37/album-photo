<?php

namespace App\Service;

use App\Entity\Photo;
use App\Repository\CommentaireRepository;
use App\Repository\PhotoRepository;

class Services
{
    public function SliderDetailsPhoto(PhotoRepository $photoRepository, CommentaireRepository $commentaireRepository, String $status,int $albumIDofCurrentPhoto, $createdDateOfCurrentPhoto):Photo{
        $photoToDisplay = null;
        if($status == 'photo-prec'){
            //if older photo is displayed, we get the most old young
            if(!($photoRepository->findSinglePhotoYounger($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto))){
                $photoToDisplay = $photoRepository->findBy(['album'=>$albumIDofCurrentPhoto], ['createAt'=>'DESC'], 1)[0];
            }else{
                $photoToDisplay = ($photoRepository->findSinglePhotoYounger($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto))[0];
            }
        }
        if($status == 'photo-suiv'){
            //if younger photo is displayed, we get the most old photo
            if(!($photoRepository->findSinglePhotoOlder($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto))){
                $photoToDisplay = $photoRepository->findBy(['album'=>$albumIDofCurrentPhoto], ['createAt'=>'ASC'], 1)[0];
            }else{
                $photoToDisplay = ($photoRepository->findSinglePhotoOlder($albumIDofCurrentPhoto, $createdDateOfCurrentPhoto))[0];
            }
        }
        return $photoToDisplay;
    }



}