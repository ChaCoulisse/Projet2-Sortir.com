<?php


namespace App\DataFixtures;


use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StateFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        for($i=0;$i<7;$i++) {
            $state =new State();
            $state->setWording($faker->company());
            $this->addReference(State::class.'_'.$i, $state);
            $manager->persist($state );
        }
        $manager->flush();
    }
}