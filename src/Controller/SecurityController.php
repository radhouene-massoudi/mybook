<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgetPWDType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('book_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('book_index');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    

    
    /**
     * @Route("/accesdenied", name="accesdenied")
     */
    public function accesdenied()
    {
        return $this->render('security/accesdenied.html.twig');
         }

         /**
     * @Route("/Forgotpwd", name="Forgotpwd")
     */
    public function Forgotpwd(Request $request, UserPasswordEncoderInterface $encoder,MailerInterface $mailer)
    {
        $user=new User();
        $form=$this->createForm(ForgetPWDType::class,$user)->handleRequest($request);
        if($form->isSubmitted()){
            $email = $form->getData()->getEmail();
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            
        }
        
        if ($user === null) {
            $this->addFlash('danger', 'Email Inconnu, recommence !');
            return $this->redirectToRoute('Forgotpwd');
        }else{
            $email = (new Email())
            ->from('zaytounabook@gmail.com')
            ->to($user->getEmail())
            ->subject('new password')
            ->text('good');
            $mailer->send($email);
        }

        return $this->render('security/ForgetPwd.html.twig',[
            'f'=>$form->createView(),
        ]);
         }

}
