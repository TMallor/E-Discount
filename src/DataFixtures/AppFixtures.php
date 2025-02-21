<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer un nouvel article
        $article = new Article();
        $article->setName('Nike Air Max 90');
        $article->setClass('chaussures');
        $article->setMainfeatures('Chaussures de sport confortables');
        $article->setDescription('Les Nike Air Max 90 sont des chaussures de sport emblématiques qui offrent un excellent confort et un style intemporel.');
        $article->setPrice(129.99);
        $article->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article->setAuthorId(1); // ID de l'auteur (assurez-vous qu'un utilisateur avec cet ID existe)
        $article->setImage('nike-air-max-90.jpg'); // Assurez-vous que cette image existe dans votre dossier public/image

        $manager->persist($article);
        $manager->flush();
    }
}
