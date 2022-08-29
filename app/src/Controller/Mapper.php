<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Offer;
use App\Entity\User;

class Mapper
{
    public static function getUser(User $user){
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'phoneNumber' => $user->getPhoneNumber(),
            'email' => $user->getEmail()
        ];
    }

    public static function getNestedUser(User $user){
        return [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'phoneNumber' => $user->getPhoneNumber(),
                'email' => $user->getEmail()
        ];
    }

    public static function getThumbnailImage($images){
        if($images[0]){
            return [
                'id' => $images[0]->getId(),
                'url' => $images[0]->getUrl()
            ];
        }else{
            return [];
        }
    }

    public static function getImages($images){
        $json = [];
        foreach ($images as $item){
            $json[] = [
                'id' => $item->getId(),
                'url' => $item->getUrl()
            ];
        }
        return $json;
    }

    public static function getGarbageList($list){
        $json = [];
        foreach ($list as $item){
            $json[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'published' => $item->getPublished(),
                'author' => self::getNestedUser($item->getAuthor()),
                'image' => self::getThumbnailImage($item->getImages()),
                'location' => $item->getLocation()
            ];
        }
        return $json;
    }

    public static function getGarbagePost($post){
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'location' => $post->getLocation(),
            'images' => self::getImages($post->getImages()),
            'author' => self::getUser($post->getAuthor())
        ];
    }

    public static function getOffer(Offer $offer){
        return[
            'id' => $offer->getId(),
            'message' => $offer->getMessage(),
            'price' => $offer->getPrice(),
            'post' => $offer->getPost()->getId(),
            'author' => self::getNestedUser($offer->getAuthor()),
            'published' => $offer->getPublished()
        ];
    }

    public static function getOfferList($list){
        $json = [];
        foreach ($list as $offer){
            $json[] = self::getOffer($offer);
        }
        return $json;
    }

}