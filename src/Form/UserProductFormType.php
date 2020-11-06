<?php


namespace App\Form;


use App\Entity\Product;
use App\Entity\Unit;
use App\Entity\UserProduct;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // displaying productId field only when creating new user product
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $userProduct = $event->getData();
            $form = $event->getForm();
            // checks if the Product object is "new"
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "Product"
            if (!$userProduct || null === $userProduct->getId()) {
                $form->add('product', EntityType::class, [
                    'class' => Product::class,
                    'choice_label' => 'name',
                ]);
            }
        })
            ->add('product', HiddenType::class, [
                'mapped' => false,
            ])
            // other, constant fields of the form
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'choice_label' => 'shortName',
                ])
            ->add('amount', NumberType::class)
            ->add('save', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProduct::class,
        ]);
    }
}