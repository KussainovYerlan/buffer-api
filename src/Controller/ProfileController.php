<?php

namespace App\Controller;

use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractFOSRestController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Rest\Route("/profile", name="api_profile", methods={"GET"})
     * @Rest\View(serializerGroups={"Default", "Minimal", "Api"})
     */
    public function index(): View
    {
        $user = $this->getUser();
        $view = View::create()
            ->setData(['user' => $user]);

        return View::create($user, Response::HTTP_OK);
    }
}
