<?php


namespace App\DataFixtures;


use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CampusFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        for($i=0;$i<3;$i++) {
            $campus =new Campus();
            $campus->setName($faker->company());
            $this->addReference(Campus::class.'_'.$i, $campus);
            $manager->persist($campus );
        }
        $manager->flush();
    }
}