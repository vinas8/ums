<?php
namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;

class HealthcheckController extends FOSRestController
{
    /**
     * @Get("/api/ping", name="healthcheck")
     */
    public function index()
    {
        $data = array(
            "name" => "test",
            "extra" => "example"
        );

        $view = $this->view($data, 200);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/test/{slug}", name="test")
     *
     * @param  Request $slug
     * @return Response
     */
    public function getTest($slug) {
        return $this->json($slug);
    }
}
