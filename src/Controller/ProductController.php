<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\Unit;
use App\Entity\UserProduct;
use App\Form\UserProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{



    public function newProduct()
    {

    }


}