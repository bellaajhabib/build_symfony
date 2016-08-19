<?php
/**
 * Created by PhpStorm.
 * User: habib
 * Date: 8/10/2016
 * Time: 11:11 PM
 */
// src/Yoda/UserBundle/Form/RegisterFormType.php
namespace Yoda\UserBundle\Form;
use Symfony\Component\Form\FormBuilderInterface;
class RegisterFormType extends \Symfony\Component\Form\AbstractType {

    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType',array(
        'required' => false,
        'label' => 'Email Address',
        'attr'    => array('class' => 'C-3PO')
    ))
            ->add('plainpassword',
                'Symfony\Component\Form\Extension\Core\Type\RepeatedType',
                array('type'=>'Symfony\Component\Form\Extension\Core\Type\PasswordType')
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
{
    $resolver->setDefaults(array(
        'data_class'=>'Yoda\UserBundle\Entity\User',
    ));
}

    public function getName()
    {
        return 'user_register';
    }
} 