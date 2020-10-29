<?php


namespace App\Controller;


use App\Entity\UserProduct;
use App\Form\UserProductFormType;
use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserProductController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/showProducts", name="app_showProducts")
     * @IsGranted("ROLE_USER")
     */
    public function showUserProducts()
    {
        $user = $this->getAuthenticatedUser();
        $repository = $this->getDoctrine()->getRepository(UserProduct::class);
        $userProducts = $repository->findBy(['userId' => $user->getId()]);

        return $this->render('authenticated/showProducts.html.twig', [
            'email' => $this->getAuthenticatedUser()->getEmail(),
            'userProducts' => $userProducts
        ]);
    }

    /**
     * @Route("/addProduct", name="app_addProduct")
     * @IsGranted("ROLE_USER")
     */
    public function addProductToUser(Request $request)
    {
        $userProduct = new UserProduct();
        $user1 = $this->security->getUser();
        $form = $this->createForm(UserProductFormType::class, $userProduct);

        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userProduct = $form->getData();

            $userProduct ->setUserId($user1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userProduct);
            $entityManager->flush();

            return $this->redirectToRoute('app_showProducts');
        }

        return $this->render('authenticated/addProduct.html.twig', [
            'form' => $form->createView(),
            'email' => $this->getAuthenticatedUser()->getEmail(),
            //'user' => $userProduct->getUserId()-,
        ]);
    }

    /**
     * @Route("/editProduct/{userProductId}", name="app_editProduct")
     * @IsGranted("ROLE_USER")
     */
    public function editAmountOfProduct(Request $request, $userProductId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userProduct = $entityManager->getRepository(UserProduct::class)->find($userProductId);
        if(!$userProduct)
        {
            throw $this->createNotFoundException('There is no such product');
        }

        $form = $this->createForm(UserProductFormType::class, $userProduct);

        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userProduct = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_showProducts');
        }

        return $this->render('authenticated/addProduct.html.twig', [
            'form' => $form->createView(),
            'email' => $this->getAuthenticatedUser()->getEmail(),
        ]);
    }

    /**
     * @Route("/deleteProduct/{userProductId}", name="app_deleteProduct")
     * @IsGranted("ROLE_USER")
     */
    public function deleteProduct($userProductId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userProduct = $entityManager->getRepository(UserProduct::class)->find($userProductId);
        if(!$userProduct)
        {
            throw $this->createNotFoundException('There is no such product');
        }
        $entityManager->remove($userProduct);
        $entityManager->flush();

        return $this->redirectToRoute('app_showProducts');
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