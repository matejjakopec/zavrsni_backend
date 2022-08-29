<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{

    public function respondWithSuccess($data){
        $response = new JsonResponse($data, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function respondWithFailure($message, $statusCode){
        $response = new JsonResponse(['message' => $message], status: $statusCode);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getPageMetaData($currentPage, $perPage, $totalResults){
        return ['meta' => [
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'totalResults' => $totalResults
            ]
        ];
    }

}