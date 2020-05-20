<?php

namespace App\Controller;

use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationController extends AbstractController
{
    private $userService;
    private $paramFetcher;

    public function __construct(UserService $userService, ParamFetcherInterface $paramFetcher)
    {
        $this->userService = $userService;
        $this->paramFetcher = $paramFetcher;
    }

    /**
     * @Rest\Route("/register", name="api_register", methods={"POST"})
     * @RequestParam(name="email", requirements=@Assert\Email, nullable=false, strict=true, description="Email")
     * @RequestParam(name="password", requirements="\w+", nullable=false, strict=true, description="Password")
     */
    public function register(): JsonResponse
    {
        $email = $this->paramFetcher->get('email');
        $password = $this->paramFetcher->get('password');

        $result = $this->userService->register($email, $password);

        return new JsonResponse($result);
    }

    /**
     * @Rest\Route("/confirm", name="api_confirm", methods={"GET"})
     */
    public function confirm(Request $request): JsonResponse
    {
        $verificationToken = $request->get('token');

        $result = $this->userService->confirm($verificationToken);

        return new JsonResponse($result);
    }
}
