<?php


namespace App\Controller;


use App\Entity\UserProduct;
use App\Form\UserProductFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
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
     * @Route("/products", name="app_products")
     * @IsGranted("ROLE_USER")
     */
    public function showUserProducts()
    {
        $user = $this->getAuthenticatedUser();
        $repository = $this->getDoctrine()->getRepository(UserProduct::class);
        $userProducts = $repository->findBy(['user' => $user->getId()]);

        return $this->render('authenticated/showProducts.html.twig', [
            'email' => $this->getAuthenticatedUser()->getEmail(),
            'userProducts' => $userProducts,
            'errorMessage' => ''
        ]);
    }

    /**
     * @Route("/products/add", name="app_addProduct")
     * @IsGranted("ROLE_USER")
     */
    public function addProductToUser(Request $request)
    {
        $userProduct = new UserProduct();
        $form = $this->createForm(UserProductFormType::class, $userProduct);

        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userProduct = $form->getData();
            $user = $this->getAuthenticatedUser();
            try {
                $user->addUserProduct($userProduct);
            }
            catch (Exception $exception){
                return $this->render('authenticated/addProduct.html.twig', [
                    'form' => $form->createView(),
                    'email' => $this->getAuthenticatedUser()->getEmail(),
                    'errorMessage' => $exception->getMessage(),
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_products');
        }

        return $this->render('authenticated/addProduct.html.twig', [
            'form' => $form->createView(),
            'email' => $this->getAuthenticatedUser()->getEmail(),
            'errorMessage' => '',
        ]);
    }

    /**
     * @Route("/products/edit/{userProductId}", name="app_editProduct")
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

            return $this->redirectToRoute('app_products');
        }

        return $this->render('authenticated/addProduct.html.twig', [
            'form' => $form->createView(),
            'email' => $this->getAuthenticatedUser()->getEmail(),
            'errorMessage' => '',
        ]);
    }

    /**
     * @Route("/products/delete/{userProductId}", name="app_deleteProduct")
     * @IsGranted("ROLE_USER")
     */
    public function deleteProduct($userProductId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userProduct = $entityManager->getRepository(UserProduct::class)->find($userProductId);
        if($userProduct)
        {
            try {
                $this->getAuthenticatedUser()->removeUserProduct($userProduct);
                $entityManager->flush();
            } catch (Exception $exception) {
                return $this->redirectToRoute('app_products', [
                    'errorMessage' => $exception->getMessage(),
                ]);
            }
            return $this->redirectToRoute('app_products');
        }
        return $this->redirectToRoute('app_products',
            [
                'errorMessage' => 'You can not delete product which you do not have!'
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