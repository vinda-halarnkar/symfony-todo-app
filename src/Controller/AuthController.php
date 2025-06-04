<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Check if email already exists
            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {
                $this->addFlash('error', 'An account with this email already exists.');
                return $this->redirectToRoute('app_register');
            }

            $plainPassword = $form->get('password')->getData();
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );            

            $token = Uuid::v4()->toRfc4122();
            $user->setVerificationToken($token);

            $entityManager->persist($user);
            $entityManager->flush();

            // Send email with activation link
            $activationLink = $this->generateUrl('app_verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            
            error_log('############################## Activation link ##################################');

            error_log('Activation link : '.date('Y-m-d H:i:s') . print_r($activationLink, true));

            error_log('############################## Activation link ##################################');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        // Find the user by verification token
        $user = $entityManager->getRepository(User::class)->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'The verification link is invalid or has expired.');
            return $this->render('error.html.twig');
        }

        # Mark the user as verified
        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $entityManager->flush();

        # Optionally flash a success message
        $this->addFlash('success', 'Your email has been verified. You can now log in.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
