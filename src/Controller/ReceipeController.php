<?php


namespace App\Controller;

use App\ApiClient\SpoonacularApiClient;
use App\Receipe\Receipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReceipeController extends AbstractController
{

    private $security;
    private $session;

    public function __construct(Security $security, SessionInterface $session)
    {
        $this->security = $security;
        $this->session = $session;
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

    /**
     * @Route("/receipes/show/{receipeId}", name="app_showReceipe")
     * @IsGranted("ROLE_USER")
     */
    public function showReceipe(Request $request, $receipeId)
    {
        $apiClient = new SpoonacularApiClient();
        $receipeInformation = $apiClient->getReceipeInformation($receipeId);
        dump($receipeInformation);
        $receipe = new Receipe($receipeInformation);
        dump($receipe);
        $this->session->set('receipe', $receipe);
        return $this->render('receipe/showReceipe.html.twig', [
            'receipe' => $receipe,
            'email' => $this->getAuthenticatedUser()->getEmail()
        ]);
    }

    /**
     * @Route("/receipes/{receipeId}/instructions", name="app_receipeSteps")
     * @IsGranted("ROLE_USER")
     */
    public function showReceipeInstructions($receipeId)
    {
        $receipe = $this->session->get('receipe');
        return $this->render('receipe/receipeInstructions.html.twig', [
            'instructions' => $receipe->getAnalyzedInstructions(),
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