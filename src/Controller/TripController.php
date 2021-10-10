<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\CityFormType;
use App\Form\PlaceFormType;
use App\Form\Trip1Type;
use App\Form\TripCancelType;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trip", name="trip_")
 */

class TripController extends AbstractController
{

//    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/create", name="create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        //afficher le formulaire
        $trip =  new Trip();
        $place= new Place();
        $city= new City();
        $user=$this->getUser();

        $form1= $this->createForm(TripType::class, $trip);
        $form2= $this->createForm(PlaceFormType::class, $place);
        $form3 = $this->createForm(CityFormType::class, $city);

        $form1->handleRequest($request);
        $form2->handleRequest($request);
        $form3->handleRequest($request);

        //traiter le formulaire

        if($form3->isSubmitted() && $form3->isValid()){
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();
        }

        if($form2->isSubmitted() && $form2->isValid()){
            $place->setCity($city);
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();
        }


        if ($form1->isSubmitted() && $form1->isValid()) {
            $trip->setOrganizer($user);
            $trip->setPlace($place);
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();


            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été créee');

            return $this->redirectToRoute('main_home');
        }
        return $this->render('trip/create.html.twig', [
            'CityFormType' => $form3->createView(),
            'Trip1Type'=> $form1->createView(),
            'PlaceFormType'=> $form2->createView(),
        ]);
    }
//    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/display/{id}", name="display")
     */

    public function display(int $id, TripRepository $tripRepository): Response
    {

        $trip=$tripRepository->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }
        foreach ($trip->getParticipant() as $participants) {
            $participants->getUserName();
            $participants->getFirstName();
            $participants->getLastname();
        }
        return $this->render('trip/display.html.twig', [
            'controller_name' => 'TripController',
            'tripDisplay' => $tripRepository->displayTrip($id),
            'listParticipants'=>$trip->getParticipant() ,

        ]);
    }
//    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/cancel/{id}", name="cancel")
     */

    public function cancel(
        int $id,
        Request $request,
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager)
    {
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        $state= $entityManager->getRepository(State::class)->find(6);

        if (!$trip) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }
        $tripCancelForm = $this->createForm(TripCancelType::class, $trip);
        $tripCancelForm->handleRequest($request);

        //traiter le formulaire
        if ($tripCancelForm->isSubmitted() && $tripCancelForm->isValid()) {
            $trip->setState( $state);
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->flush();

            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été annulée');

            return $this->redirectToRoute('main_home');
        }

        return $this->render('trip/cancel.html.twig', [
            'controller_name' => 'TripController',
            'tripCancelForm'=>$tripCancelForm->createView(),
            'tripDisplay' =>$tripRepository->displayTrip($id),
        ]);
    }
//    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/edit/{id}", name="edit")
     */

    public function edit( int $id, Request $request,EntityManagerInterface $entityManager) : Response {
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if (!$trip) {
            throw $this->createNotFoundException("Cette sortie est inconnue");
        }
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $entityManager>persist($trip);
            $entityManager->flush();
            $this->addFlash("success", "La sortie est bien modifiée.");
            return $this->redirectToRoute("trip_display", ["id"=> $trip->getId()]);
        }
        return $this->render('trip/edit.html.twig', [
            'tripForm'=>$tripForm->createView(),
            'trip'=> $trip
        ]);

    }
    //    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/{id_user}/{id_trip}/registerParticipant", name="registerParticipant")
     */

    public function registerParticipant( $id_user, $id_trip,Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id_user);
        $trip = $this->getDoctrine()->getRepository(Trip::class)->find($id_trip);
        $trip->addParticipant( $user);
        $entityManager->flush();
        return $this->redirectToRoute('main_home');
    }
//    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/{id_user}/{id_trip}/giveUpParticipant", name="giveUpParticipant")
     */


    public function giveUpParticipant($id_user,$id_trip, Request $request, TripRepository $tripRepository, EntityManagerInterface $entityManager)
    {

        $trip = $this->getDoctrine()->getRepository(Trip::class)->find($id_trip);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id_user);
        $trip->removeParticipant( $user);
        $entityManager->flush();
        return $this->redirectToRoute('main_home');
    }
    //    -------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/{id}/publishTrip", name="publishTrip")
     */

    public function publishTrip($id,
                                Request $request,
                                TripRepository $tripRepository,
                                EntityManagerInterface $entityManager)
    {

        $trip = $this->getDoctrine()->getRepository(Trip::class)->find($id);
        $state= $entityManager->getRepository(State::class)->find(2);

        if (!$trip) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }

        $trip->setState( $state);
        $entityManager= $this->getDoctrine()->getManager();
        $entityManager->flush();

        //afficher un message flash
        $this->addFlash('success', 'Votre sortie a bien été publiée');

        return $this->redirectToRoute('main_home');
    }
}