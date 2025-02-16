<?php

namespace App\DataFixtures;

use App\Entity\Expedition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ExpeditionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Faker en français

        for ($i = 0; $i < 10; $i++) {
            $expedition = new Expedition();
            $expedition->setNomExpedition($faker->sentence(3));
            $expedition->setUnivers($faker->word);
            $expedition->setCartTresorUrl($faker->url);
            $expedition->setQuetesDispo($faker->paragraph);
            $expedition->setObjetMagique($faker->word);
            $expedition->setGardienArtisanaux($faker->sentence);
            $expedition->setDureeMystique($faker->randomElement(['1 jour', '3 jours', '1 semaine']));
            $expedition->setSecretCache($faker->sentence);
            $expedition->setReliqueFinale($faker->word);

            $manager->persist($expedition);
        }

        $manager->flush();
    }
}
