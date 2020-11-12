<?php


namespace App\Controller;

use App\ApiClient\SpoonacularApiClient;
use App\Entity\ReceipeReference;
use App\Entity\UserProduct;
use App\Entity\UserViewedReceipe;
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
        //add receipe to recently viewed
        $user = $this->getAuthenticatedUser();
        $entityManager = $this->getDoctrine()->getManager();
        $receipeReferenceFromDb = $entityManager->getRepository(ReceipeReference::class)->findOneBy(['receipeId' => $receipeId]);
        if($receipeReferenceFromDb)
        {
            $userViewedReceipe = $entityManager->getRepository(UserViewedReceipe::class)->findOneBy([
                'user' => $user,
                'receipeReference' => $receipeReferenceFromDb,
            ]);
            if($userViewedReceipe)
            {
                $userViewedReceipe->setLastView(new \DateTime());
                $entityManager->flush();
            }
            else
            {
                $userViewedReceipe = new UserViewedReceipe($user, $receipeReferenceFromDb);
                $entityManager->persist($userViewedReceipe);
                $entityManager->flush();
            }
        }
        else
        {
            $receipeReference = new ReceipeReference($receipe->getTitle(), $receipe->getImage(), $receipe->getId());
            $userViewedReceipe = new UserViewedReceipe($user, $receipeReference);
            $entityManager->persist($receipeReference);
            $entityManager->flush();
            $entityManager->persist($userViewedReceipe);
            $entityManager->flush();
        }
        return $this->render('receipe/showReceipe.html.twig', [
            'receipe' => $receipe,
            'userViewedReceipe' => $userViewedReceipe,
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
        $this->session->set('recalculatedUserProducts', $recalculatedUserProducts);
        return $this->render('receipe/receipeInstructions.html.twig', [
            'instructions' => $receipe->getAnalyzedInstructions(),
            'missedIngredients' => $missedIngredients,
        ]);
    }

    /**
     * @Route("/receipes/addToFavourites/{userViewedReceipeId}", name="app_favouriteReceipes")
     * @IsGranted("ROLE_USER")
     */
    public function addReceipeToFavourites($userViewedReceipeId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userViewedReceipe = $entityManager->getRepository(UserViewedReceipe::class)->find($userViewedReceipeId);
        $userViewedReceipe->setIsFavourite(true);
        $entityManager->persist($userViewedReceipe);
        $entityManager->flush();
        $this->redirectToRoute("app_showFavourites");
    }

    /**
     * @Route("/receipes/favourites", name="app_showFavourites")
     * @IsGranted("ROLE_USER")
     */
    public function showFavouriteReceipes()
    {
        $user = $this->getAuthenticatedUser();
        $userViewedReceipes = $user->getUserViewedReceipes();
        return $this->render('receipe/favouriteReceipes.html.twig', [
            'userViewedReceipes' => $userViewedReceipes
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