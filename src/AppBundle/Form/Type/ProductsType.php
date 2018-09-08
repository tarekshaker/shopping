<?php
/**
 * Created by PhpStorm.
 * User: Teka
 * Date: 9/1/2018
 * Time: 4:26 AM
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('product_name', TextType::class)
            ->add('product_desc', TextType::class,array('label' => 'Product Description'))
            ->add('price', NumberType::class)
            ->add('image', FileType::class,array('required' => false))
            ->add('quantity', NumberType::class,array('required' => false))
            ->add('product_type', EntityType::class, [
                    'class'        => 'AppBundle:Products_Types',
                    'choice_label' => 'product_type_name',
                    'label'        => 'Product Type'
                ]
            )
            ->add('sale_amount', NumberType::class,array('required' => false,'label'=>'Sale Amount (%)','disabled'=>true))

            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                array($this, 'onPreSubmitData')
            )
            ->add('save', SubmitType::class, array('label' => 'Add Product'))

        ;
    }/**
 * {@inheritdoc}
 * throws \Symfony\Component\OptionsResolver\Exception\AccessException
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


    public function onPreSubmitData(FormEvent $event)
    {

        $user = $event->getData();
        $form = $event->getForm();


        if (!$user) {
            return;
        }

        // checks whether the user has chosen to display their email or not.
        // If the data was submitted previously, the additional value that is
        // included in the request variables needs to be removed.
        if ($user['product_type'] == 2) {
            $form->add('sale_amount', NumberType::class,array('required' => false,'label'=>'Sale Amount (%)'));
        } else {
            unset($user['sale_amount']);
            $event->setData($user);
        }


    }

}