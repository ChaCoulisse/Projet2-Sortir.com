<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, AppAuthenticator $authenticator): Response
    {
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    //    $form->get('password')->getData()
                    $user ->getPassword()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

           // $this->addFlash("Succès ! Le nouvel utilisateur a bien été inscrit !");

            return $this->redirectToRoute('app_register');
        }

        return $this->render('admin/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Desactiver/{id}", name="user_inactive")
     */
    public function inactivate($id, EntityManagerInterface $em){
        $user=$em->getRepository(User::class)->find($id);
        $user->setActive(0);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute("user_list");
    }
    /**
     * @Route("/Activer/{id}", name="user_active")
     */
    public function activate($id, EntityManagerInterface $em){
        $user=$em->getRepository(User::class)->find($id);
        $user->setActive(1);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute("user_list");
    }

    /**
     * @Route("/Supprimer/{id}", name="user_delete")
     */
    public function delete($id, EntityManagerInterface $em){
        $user=$em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("user_list");
    }
}