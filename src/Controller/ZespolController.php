<?php

namespace App\Controller;

use App\Entity\Zespol;
use App\Repository\ZespolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class ZespolController extends ApiController
{
    /**
     * @Route("/zespoly", name="zespolyg", methods="GET")
     */
    public function index222(ZespolRepository $zespolRepository) {
        // if (! $this->isAuthorized()) {
        //     return $this->respondUnauthorized();
        // }
        
        $zespoly = $zespolRepository->transformAll();
        
        return $this->respond($zespoly);
    }

    /**
     * @Route("zespoly", name="zespolyp", methods="POST")
     */
    public function create(Request $request, ZespolRepository $zespolRepository, EntityManagerInterface $em) {
        if (! $this->isAuthorized()) {
            return $this->respondUnauthorized();
        }
        
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (! $request->get("title")) {
            return $this->respondValidationError('Please provide title');
        }

        $zespol = new Zespol();
        $zespol->setTitle($request->get("title"));
        $zespol->setCount(0);
        $em->persist($zespol);
        $em->flush();

        return $this->respondCreated($zespolRepository->transform($zespol));
    }

    /**
     * @Route("zespoly/{id}/count", methods="POST")
     */
    public function increaseCount($id, EntityManagerInterface $em, ZespolRepository $zespolRepository) {
        // if (! $this->isAuthorized()) {
        //     return $this->respondUnauthorized();
        // }
        
        $zespol = $zespolRepository->find($id);

        if (! $zespol) {
            return $this->respondNotFound();
        }

        $zespol->setCount($zespol->getCount() + 1);
        $em->persist($zespol);
        $em->flush();

        return $this->respond([
            'count' => $zespol->getCount()
        ]);
    } 

    //  public function zespolyLista () {
    //     return new JsonResponse([
    //         [
    //             'title' => 'Polska',
    //             'count' => 0
    //         ]
    //     ]);
    // }


    // public function index(): Response
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/ZespolController.php',
    //     ]);
    // }
}
