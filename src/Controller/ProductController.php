<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\Unit;
use App\Entity\UserProduct;
use App\Form\ProductFormType;
use App\Form\UserProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/products/new", name="app_newProduct")
     * @IsGranted("ROLE_USER")
     */
    public function newProduct(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            $productsWithSameName = $this->getDoctrine()->getRepository(
                Product::class)->findBy(['name' => $product->getName()]
            );
            if(!$productsWithSameName)
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($product);
                $entityManager->flush();
                return $this->redirectToRoute("app_addProduct");
            }
            else
            {
                return $this->render("authenticated/newProduct.html.twig", [
                    'form' => $form->createView(),
                    'email' => $this->getAuthenticatedUser()->getEmail(),
                    'errorMessage' => 'Product with this name already exist',
                ]);
            }
        }

        return $this->render("/authenticated/newProduct.html.twig", [
            'form' => $form->createView(),
            'email' => $this->getAuthenticatedUser()->getEmail(),
            'errorMessage' => ''
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