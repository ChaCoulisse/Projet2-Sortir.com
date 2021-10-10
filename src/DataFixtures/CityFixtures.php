<?php


namespace App\DataFixtures;


use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        for($i=0;$i<21;$i++) {
            $city=new City();
            $city->setName($faker->city());
            $city->setZipCode($faker->postcode());
            $this->addReference(City::class.'_'.$i, $city);
            $manager->persist($city);
        }
        $manager->flush();
    }
}