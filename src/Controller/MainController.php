<?php


namespace App\Controller;

use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;


class MainController extends AbstractController
{
    private $security;
    private $email;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
    * @Route("/", name="app_homepage")
    * @IsGranted("ROLE_USER")
    */
    public function homePage(): Response
    {
        return $this->render('authenticated/home.html.twig', ['email' => $this->getAuthenticatedUser()->getEmail()]);
    }

    private function getAuthenticatedUser()
    {
        /** @var \src\Entity\User $user */
        $user = $this->security->getUser();
        if($user != null)
        {
            return $user;
        }
        throw new Exception('User is not signed in');
    }
}