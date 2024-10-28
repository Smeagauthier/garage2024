<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Voiture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');  
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $voiture = new Voiture();
            $voiture->setMarque($faker->company());
            $voiture->setModele($faker->word());
            $voiture->setImage($faker->imageUrl(640, 480, 'cars'));
            $voiture->setKm($faker->numberBetween(0, 200000));
            $voiture->setPrix($faker->numberBetween(1000, 50000));
            $voiture->setNbProprietaire($faker->numberBetween(1, 5)); 
            $voiture->setCylindree($faker->numberBetween(1000, 5000)); 
            $voiture->setPuissance($faker->numberBetween(50, 400)); 
            $voiture->setCarburant($faker->randomElement(['Essence', 'Diesel', 'Électrique', 'Hybride']));
            $voiture->setAnnee($faker->year()); 
            $voiture->setTransmission($faker->randomElement(['Manuelle', 'Automatique'])); 
            $voiture->setDescription($faker->paragraph()); 
            $voiture->setAutresOptions($faker->sentence()); 

            $manager->persist($voiture);
        }

        $manager->flush();
    }
}
