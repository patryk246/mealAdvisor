<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;


class MainController
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
    public function helloWorld(){
        /** @var \src\Entity\User $user */
        $user = $this->security->getUser();
        return new Response(
        '<html><body>Hello World</body></html>'
        );
    }
}