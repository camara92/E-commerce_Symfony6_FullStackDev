<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use App\Service\SendMailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            //return $this->redirectToRoute('app_login');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // affiche du message d'erreur 
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [

            'last_username' => $lastUsername, 'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'reset_password')]
    public function forgottenPassword(Request $request, UsersRepository $usersRepository, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $manager, SendMailService $mail): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //    On va chercher l'utilisateur par son emai: 
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());
            // dd($user); 
            if ($user) {
                // On génère un token de réinitialisation 
                $token = $tokenGeneratorInterface->generateToken();
                // dd($token);
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();

                // On génère un lien de réinitialisation du mot de passe : 
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                // On crée les données du mail 
                $context = compact('url', 'user');
                //  On envoie l'email 
                $mail->send(
                    'no-reply@en-marche.fr',
                    $user->getEmail(),
                    'Réinitialisation du mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès ! ');
                return $this->redirectToRoute('app_login');
            }
            $this->addFlash('danger', 'Un problème est survenu. ');
            return $this->redirectToRoute('app_login');
        }
        return $this->render(
            'security/reset_password_request.html.twig',
            [
                'requestPassform' => $form->createView()
            ]
        );
    }

    #[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]

    public function resetPass(Request $request, UsersRepository $usersRepository, EntityManagerInterface $entityManagerInterface, string $token, UserPasswordHasherInterface $passwordHasherInterface): Response
    {
        // On vérifie si on a ce token dans la bdd 
        $user = $usersRepository->findOneByResetToken($token);
        if ($user) {

            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                // On efface le token 
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasherInterface->hashPassword($user, $form->get('password')->getData()));
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();
                    // flash message
                    $this->addFlash('success', 'Votre mot de passe a été mis à jour 😘');
                    return $this->redirectToRoute('app_login');

                    

            }
            return $this->render('security/reset_password.html.twig', [
                'PassForm' => $form->createView(),
                // ceux qui sont en bas sont optionals, vous pouvez effacer au beosin 

                'user' => $user,
                'token' => $token,
                'password_hasher' => $passwordHasherInterface,
            ]); 
        }
        $this->addFlash('danger text-center', 'Jeton invalide 😒😒😒😒😒😒😒😒😒😒 ');
        return $this->redirectToRoute('app_login');
    }
}
