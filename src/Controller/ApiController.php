<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function indexAPI(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    protected $statusCode = 200;

    public function getStatusCode() {
        return $this->statusCode;
    }

    protected function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $header = []) {
        return new JsonResponse($data, $this->getStatusCode(), $header);
    }

    public function respondWithErrors ($errors, $header = []) {
        $data = [
            'errors' => $errors,
        ];
        return new JsonResponse($data, $this->getStatusCode(), $header);
    }

    public function respondUnauthorized($message = 'Not authorized') {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    public function respondValidationError($message = 'Validation errors') {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    public function respondNotFound($message = 'Not Found') {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    public function respondCreated($data = []) {
        return $this->setStatusCode(201)->respond($data);
    }

    protected function transformJsonBody(Request $request) {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);
        return $request;
    }

    public function isAuthorized(): bool {
        if (! $_SERVER["HTTP_AUTHORIZATION"]) {
            return false;
        }

        $authType = null;
        $authData = null;

        @list($authType, $authData) = explode("", $_SERVER["HTTP_AUTHORIZATION"], 2);

        if ($authType != 'Bearer') {
            return false;
        }

        try {
            $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
                            ->setAdaptor(new \Okta\JwtVerifier\Adaptors\FirebasePhpJwt())
                            ->setAudience('api://default')
                            ->setClientId('0oa104sfo7bQJAxrX5d7')
                            ->setIssuer('dev-99812836.okta.com')
                            ->build(); 
            $jwt = $jwtVerifier->verify($authData);
        }
        catch (\Exception $e) {
            return false;
        }


    }

}
