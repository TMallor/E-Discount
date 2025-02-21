<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Premier article - Nike
        $article1 = new Article();
        $article1->setName('Nike Air Max 90');
        $article1->setClass('chaussures');
        $article1->setMainfeatures('Chaussures de sport confortables');
        $article1->setDescription('Les Nike Air Max 90 sont des chaussures de sport emblématiques qui offrent un excellent confort et un style intemporel.');
        $article1->setPrice(129.99);
        $article1->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article1->setAuthorId(1);
        $article1->setImage('image/nike.png');

        // Deuxième article - Adidas
        $article2 = new Article();
        $article2->setName('Adidas Superstar');
        $article2->setClass('chaussures');
        $article2->setMainfeatures('Chaussures de sport classiques');
        $article2->setDescription('Les Adidas Superstar sont des chaussures iconiques avec leur design distinctif à trois bandes et leur embout en caoutchouc.');
        $article2->setPrice(99.99);
        $article2->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article2->setAuthorId(1);
        $article2->setImage('image/adidas.png');

        // Troisième article - Puma
        $article3 = new Article();
        $article3->setName('Puma RS-X');
        $article3->setClass('chaussures');
        $article3->setMainfeatures('Chaussures de sport modernes');
        $article3->setDescription('Les Puma RS-X offrent un style rétro-futuriste avec une technologie de pointe pour un confort optimal.');
        $article3->setPrice(119.99);
        $article3->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article3->setAuthorId(1);
        $article3->setImage('image/puma.png');

        // Quatrième article - Ballon
        $article4 = new Article();
        $article4->setName('Nike Strike');
        $article4->setClass('accessoires');
        $article4->setMainfeatures('Ballon de football professionnel');
        $article4->setDescription('Le ballon Nike Strike est conçu pour une précision et une durabilité optimales sur tous les terrains.');
        $article4->setPrice(29.99);
        $article4->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article4->setAuthorId(1);
        $article4->setImage('image/ballon.png');

        $manager->persist($article1);
        $manager->persist($article2);
        $manager->persist($article3);
        $manager->persist($article4);
        $manager->flush();
    }
}
