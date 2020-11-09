<?php


namespace App\Controller;

use App\ApiClient\SpoonacularApiClient;
use App\Receipe\Receipe;
use Doctrine\Common\Collections\ArrayCollection;
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
        $receipe = new Receipe($receipeInformation);
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
        $ingredients = $receipe->getIngredients();
        $user = $this->getAuthenticatedUser();
        // collection contains objects UserProducts
        $userProducts = $user->getUserProducts();
        $missedIngredients = new ArrayCollection();
        $recalculatedUserProducts = new ArrayCollection();
        // checking if user have all required ingredients
        foreach($ingredients as $ingredient)
        {
            $i = 0;
            foreach ($userProducts as $userProduct)
            {
                if($ingredient->getName() == $userProduct->getProduct()->getName())
                {
                    $ingredient->recalculateAmountAndUnit($userProduct->getUnit()->getShortName());
                    $newAmount = $userProduct->getAmount() - $ingredient->getRecalculatedAmount();
                    if($newAmount < 0)
                    {
                        $missedIngredients->add($ingredient);
                    }
                    else {
                        $userProduct->setAmount($newAmount);
                        $recalculatedUserProducts->add($userProduct);
                    }
                    break;
                }
                $i++;
            }
            if($i == $userProducts->count())
            {
                $missedIngredients->add($ingredient);
            }
        }
        dump($userProducts);
        $this->session->set('recalculatedUserProducts', $recalculatedUserProducts);
        return $this->render('receipe/receipeInstructions.html.twig', [
            'instructions' => $receipe->getAnalyzedInstructions(),
            'email' => $this->getAuthenticatedUser()->getEmail(),
            'missedIngredients' => $missedIngredients,
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