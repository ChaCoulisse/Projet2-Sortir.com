<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use App\Repository\CityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PlaceFixtures extends Fixture implements OrderedFixtureInterface
{

    private $cityRepository;
    /**
     * PlaceFixtures constructor.
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        for($i=0;$i<21;$i++) {
            $place=new Place();
            $place->setCity($this->getReference(City::class.'_'.mt_rand(1,20)));
            $place->setName($faker->company());
            $place->setStreet($faker->streetName());
            $place->setLatitude($faker->latitude());
            $place->setLongitude($faker->longitude());
            $this->addReference(Place::class.'_'.$i, $place);
            $manager->persist($place);
        }
        $manager->flush();
    }

    public function getOrder(): array
    {
        return [City::class];
    }
}
