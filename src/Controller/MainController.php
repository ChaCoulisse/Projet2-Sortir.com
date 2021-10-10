<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\SearchTripType;
use App\Form\TripType;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main_home")
     */
    public function home(Request $request, TripRepository $tripRepository): Response
    {
        $trip = new Trip();
        $searchTripForm = $this->createForm(SearchTripType::class, $trip);
        $searchTripForm->handleRequest($request);

        if($searchTripForm->isSubmitted()){
            $organizer = isset($request->request->get('search_trip')['organizer']);
            $participant = isset($request->request->get('search_trip')['participant']);
            $notParticipant = isset($request->request->get('search_trip')['notParticipant']);
            $state = isset($request->request->get('search_trip')['state']);
            $name = isEmpty($request->request->get('search_trip')['name']) ? ' ' : $request->request->get('search_trip')['name'];
            $user = $this->getUser()->getId();

            return $this->render('main/index.html.twig', [
                'tripList' => $tripRepository->findTripByParameter(
                    $request->request->get('search_trip')['campus'],
                    $name,
                    $request->request->get('search_trip')['start'],
                    $request->request->get('search_trip')['end'],
                    $organizer,
                    $participant,
                    $notParticipant,
                    $state,
                    $user
                ),
                'searchTripForm' => $searchTripForm->createView(),
            ]);
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'tripList' => $tripRepository->findAllTrip(),
            'searchTripForm' => $searchTripForm->createView(),
        ]);
    }
}