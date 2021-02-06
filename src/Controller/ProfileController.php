<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(
        Request $request,
        UserPasswordEncoderInterface $encoder
    ): Response {
        $user = $this->getUser();
        $error = '';
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $user->setFirstName($request->get('firstName'));
            $user->setLastName($request->get('lastName'));
            $user->setEmail($request->get('email'));
            $password = $request->get('password');
            $newPassword = $request->get('newPassword');
            if ($password && !$encoder->isPasswordValid($user, $password)) {
                $error = 'Invalid current password';
            } elseif ($password && $newPassword) {
                $user->setPassword(
                    $encoder->encodePassword($user, $newPassword)
                );
            }
            if (!$error) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
        }

        return $this->render('profile.html.twig', [
            'user' => $user,
            'error' => $error,
        ]);
    }
}
