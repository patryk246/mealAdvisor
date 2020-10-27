<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;


class MainController extends AbstractController
{
    private $security;

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
        /** @var \src\Entity\User $user */
        $user = $this->security->getUser();
        return $this->render('authenticated/home.html.twig', ['email' => $user->getEmail()]);
    }
}