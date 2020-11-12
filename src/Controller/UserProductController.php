<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\UserProduct;
use App\Form\SelectProductsFormType;
use App\Form\UserProductFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserProductController extends AbstractController
{
    private $security;
    private $session;

    public function __construct(Security $security, SessionInterface $session)
    {
        $this->security = $security;
        $this->session = $session;
    }

    /**
     * @Route("/products", name="app_products")
     * @IsGranted("ROLE_USER")
     */
    public function showUserProducts(Request $request)
    {
        $errorMessage = '';
        if($request->get('errorMessage'))
        {
            $errorMessage = $request->get('errorMessage');
        }
        $user = $this->getAuthenticatedUser();
        $userProducts = $user->getUserProducts();
        dump($userProducts);
        return $this->render('product/showProducts.html.twig', [
            'userProducts' => $userProducts,
            'errorMessage' => $errorMessage
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
                return $this->render('product/addProduct.html.twig', [
                    'form' => $form->createView(),
                    'errorMessage' => $exception->getMessage(),
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_products');
        }
        return $this->render('product/addProduct.html.twig', [
            'form' => $form->createView(),
            'errorMessage' => '',
            'productName' => '',
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
            return $this->redirectToRoute('app_products',
                [
                    'errorMessage' => 'You can not edit product which you do not have!'
                ]);
        }
        $form = $this->createForm(UserProductFormType::class, $userProduct);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userProduct = $form->getData();
            $entityManager->flush();
            return $this->redirectToRoute('app_products');
        }
        return $this->render('product/addProduct.html.twig', [
            'form' => $form->createView(),
            'errorMessage' => '',
            'productName' => $userProduct->getProduct()->getName(),
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

    /**
     * @Route("/products/select", name="app_selectProducts")
     * @IsGranted("ROLE_USER")
     */
    public function chooseUserProductsForReceipes(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        $product = new Product();
        $userProducts = $user->getUserProducts();
        $products = new ArrayCollection();
        foreach ($userProducts as $userProduct)
        {
            $products->add($userProduct->getProduct());
        }
        $form = $this->createForm(SelectProductsFormType::class, $product, ['products' => $products]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $productNames = '';
            foreach ($form['name']->getData() as $selectedProduct)
            {
                //create string used in API call as parameter
                $productNames .= $selectedProduct->getName() . ',';
            }
            $productNames = substr($productNames, 0, -1);
            return $this->redirectToRoute('app_findReceipes', ['productNames' => $productNames]);
        }
        return $this->render('product/selectProducts.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/recalculateAmountOfProducts", name="app_recalculateProducts")
     * @IsGranted("ROLE_USER")
     */
    public function recalculateUserProducts(Request $request)
    {
        $recalcatedUserProducts = $this->session->get('recalculatedUserProducts');
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($recalcatedUserProducts as $recalculatedUserProduct)
        {
            $userProduct = $entityManager->getRepository(UserProduct::class)->find($recalculatedUserProduct->getId());
            if($recalculatedUserProduct->getAmount() == 0)
            {
                $entityManager->remove($userProduct);
            }
            else
            {
                $userProduct->setAmount($recalculatedUserProduct->getAmount());
                $entityManager->persist($userProduct);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute("app_products");
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