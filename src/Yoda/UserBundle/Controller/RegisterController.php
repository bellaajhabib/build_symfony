<?php

namespace Yoda\UserBundle\Controller;

use Yoda\EventBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Yoda\UserBundle\Entity\User;
use Yoda\UserBundle\Form\RegisterFormType;
class RegisterController extends Controller
{
    /**
     * @Route("/register",name="user_register")
     *  @Template
     */
    public function registerAction(Request $request)
    {


        $defaultUser = new User();
        $defaultUser->setUsername('Foo');

        $form = $this->createForm( RegisterFormType::class  , $defaultUser);
        $s=$form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $user =$form->getData();

            $user->setPassword($this->encodePassword($user,$user->getPlainpassword()));
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Welcome to the Death Star, have a magical day!')
            ;

            $url=$this->generateUrl('event');
            return $this->redirect($url);
        }


        return array('form'=>$form->createView());
    }
    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }
}
