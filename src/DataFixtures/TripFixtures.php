<?php


namespace App\DataFixtures;


use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\PlaceRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TripFixtures extends Fixture implements OrderedFixtureInterface
{
    private $campusRepository;
    private $userRepository;
    private $stateRepository;
    private $placeRepository;

    /**
     * TripFixtures constructor.
     */
    public function __construct(CampusRepository $campusRepository, UserRepository $userRepository, StateRepository $stateRepository, PlaceRepository $placeRepository)
    {
        $this -> campusRepository = $campusRepository;
        $this -> userRepository = $userRepository;
        $this -> stateRepository = $stateRepository;
        $this -> placeRepository = $placeRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory ::create('fr_FR');
        for ($i = 0; $i < 21; $i ++) {
            $trip = new Trip();
            $trip -> setName($faker -> sentence(3));
            $trip -> setStartHour($faker -> dateTimeBetween('now', '+3 months'));
            $trip -> setDuration($faker -> numberBetween(30, 240));
            $trip -> setLimitDate($faker -> dateTimeBetween('- 3 days', $trip -> getStartHour()));
            $trip -> setLimitedPlace($faker -> randomNumber(1, 15));
            $trip -> setInfoTrip($faker -> text(255));
            $trip -> setOrganizer($this->getReference(User::class.'_'.mt_rand(0,20)));
            $trip -> setCampus($this->getReference(Campus::class.'_'.mt_rand(0,2)));
            $trip -> setState($this->getReference(State::class.'_'.mt_rand(0,6)));
            $trip -> setPlace($this->getReference(Place::class.'_'.mt_rand(0,20)));
            $manager -> persist($trip);
        }
        $manager -> flush();
    }

    public function getOrder(): array
    {
        return [User::class];
    }
}