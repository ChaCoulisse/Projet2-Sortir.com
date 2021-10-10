<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    private $campusRepository;

    public function __construct(CampusRepository $campusRepository)
    {
        $this->campusRepository=$campusRepository;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        for($i=0;$i<21;$i++) {
            $user=new User();
            $user->setCampus($this->getReference(Campus::class.'_'.mt_rand(0,2)));
            $user->setLastname($faker->lastName());
            $user->setFirstname($faker->firstName());
            $user->setUserName($faker->name());
            $user->setPhone($faker->phoneNumber());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $this->addReference(User::class.'_'.$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getOrder(): array
    {
        return [Campus::class];
    }
}
