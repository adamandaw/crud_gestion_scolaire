<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Planning;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class PlanningFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        for ($i=1; $i <= 4; $i++) { 
            $planning= new Planning();
            $planning->setCours($this->getReference("Cours". $i));
            $beginTime = DateTimeImmutable::createFromFormat('H_i_s',$faker->time('H_i_s'));
            $endTime = DateTimeImmutable::createFromFormat('H_i_s',$faker->time('H_i_s'));
            $planning->setBeginAt($beginTime);
            $planning->setEndAt($endTime);
           
            $manager->persist($planning);
            $this->addReference("Plannings".$i,$planning);

        }
        $manager->flush();
    }
    public function getDependencies(){
        return array(
            CoursFixtures::class,
        ); 
       }
}
