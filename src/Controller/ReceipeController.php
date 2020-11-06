<?php


namespace App\Controller;

use App\ApiClient\SpoonacularApiClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReceipeController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route("/receipes/find", name="app_findReceipes")
     * @IsGranted("ROLE_USER")
     */
    public function findReceipesByIngredients(Request $request)
    {
        $productNames = $request->get('productNames');
        $apiClient = new SpoonacularApiClient();
        $response = $apiClient->getReceipesByIngredients($productNames);
        return $this->render("receipe/receipesSearchedById.html.twig", [
            'response' => $response,
            'email' => $this->getAuthenticatedUser()->getEmail()
        ]);
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