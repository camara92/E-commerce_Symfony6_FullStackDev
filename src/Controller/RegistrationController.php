<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UsersAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt
    ): Response {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            // on genere ;e jwt de user 
            // on crée le header 

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256',

            ];
            // on crée le payload 
            $payload = [
                'user_id' => $user->getId()
            ];
            // on genere le signature 
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // dd($token);	
            // on envoie ce token à l'utilisateur pour validation 

            // envoie de mail 
            $mail->send(
                'no-reply@daouda.net',
                $user->getEmail(),
                'Activation du compte',
                'register',
                [
                    // compact('user', 'token')
                    'user' => $user,
                    'token' => $token,
                ]
            );


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request, 
                
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verif_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        // dd($jwt->isValid($token));
        // dd($jwt->getPayload($token)); 
        // dd($jwt->isExpired($token)); 
        // dd($jwt->check($token, $this->getParameter('app.jwtsecret')));
        if($jwt->isValid($token) 
            && !$jwt->isExpired($token)
            && $jwt->check($token, $this->getParameter('app.jwtsecret'))) 
            
            {
            // On récupère le payload 
            $payload = $jwt->getPayload($token);
            // on récupère le token d user 
            $user = $usersRepository->find($payload['user_id']);
            // on verifie que l'utilisateur existe et n'a pas encore activé son compte : 
                if ($user && !$user->getIsVerified()) {
                    $user->setIsVerified(true);
                    $em->flush($user);
                    $this->addFlash('success', 'Utilisateur activé ');
                    return $this->redirectToRoute('profile_index'); 
                }
        }
        // ici un problème se pose dans le token : 
        $this->addFlash('warning', 'Le token est invalid  ou a expiré ! ');
        return $this->redirectToRoute('app_login');
}

    #[Route('/renvoieverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository ): Response
        {
             $user = $this->getUser();
             if(!$user){
                $this->addFlash('danger', 'Vous devez être ,connecté pour accéder à cette page ! ');
                return $this->redirectToRoute('app_login');

             }
             if($user->getIsVerified()){

                $this->addFlash('warning', 'Ce compte a déjà été activé !  ');
                return $this->redirectToRoute('profile_index');

             }

            //  envoie de l'email 
                  // on genere ;e jwt de user 
            // on crée le header 

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256',

            ];
            // on crée le payload 
            $payload = [
                'user_id' => $user->getId()
            ];
            // on genere le signature 
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // dd($token);	
            // on envoie ce token à l'utilisateur pour validation 

            // envoie de mail 
            $mail->send(
                'no-reply@daouda.net',
                $user->getEmail(),
                'Activation du compte',
                'register',
                [
                    // compact('user', 'token')
                    'user' => $user,
                    'token' => $token,
                ]
            );


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request, 
                
            ); 

            $this->addFlash('success', 'Email de vérification envoyée  !  ');
            return $this->redirectToRoute('profile_index');
}


}