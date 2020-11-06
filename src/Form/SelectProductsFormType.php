<?php


namespace App\Form;


use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SelectProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $products = $options['products'];
        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $products,
                'choice_value' => 'name',
                'choice_label' => function(?Product $product){
                return $product ? $product->getName() : '';
                },
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'products' => null
        ]);
    }
}