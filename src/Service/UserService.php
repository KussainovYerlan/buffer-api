<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $userPasswordEncoder;
    private $userRepository;
    private $em;
    private $mailerService;
    private $jwtEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        MailerService $mailerService,
        JWTEncoderInterface $jwtEncoder)
    {
        $this->em = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->mailerService = $mailerService;
        $this->jwtEncoder = $jwtEncoder;
        $this->userRepository = $userRepository;
    }

    public function register($email, $password): bool
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $user->setStatus(User::STATUS_BLOCKED);

        $this->em->persist($user);
        $this->em->flush();

        $verificationToken = $this->jwtEncoder->encode(['email' => $user->getEmail(), 'exp' => time() + 3600]);

        $this->mailerService->sendConfirmationEmail($email, $verificationToken);
        
        return true;
    }

    public function confirm($verificationToken): void
    {
        $email = $this->jwtEncoder->decode($verificationToken)['email'];
        $user = $this->userRepository->findForVerification($email);

        $user->setStatus(User::STATUS_ACTIVE);
        $this->em->persist($user);
        $this->em->flush();
    }
}
