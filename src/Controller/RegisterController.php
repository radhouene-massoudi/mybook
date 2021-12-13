<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
   
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, MailerInterface $mailer)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $email = (new Email())
            ->from('zaytounabook@gmail.com')
            ->to($user->getEmail())
            ->subject('Welcome to Zaytouna Book')
            ->text("Welcome to Our web site {$user->getName()}! ❤️");
            $mailer->send($email);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
