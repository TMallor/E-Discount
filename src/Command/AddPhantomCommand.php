<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Stock;
use App\Enum\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:add-phantom',
    description: 'Ajoute l\'article Nike Phantom'
)]
class AddPhantomCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $article = new Article();
        $article->setName('Nike Phantom Gx 2 Elite Erling Haaland Fg Bleu');
        $article->setCategory(ArticleCategory::CHAUSSURES->value);
        $article->setMainfeatures('Chaussures de football professionnelles');
        $article->setDescription('Les Nike Phantom GX 2 Elite, signature Erling Haaland, offrent une précision et un contrôle exceptionnels sur le terrain.');
        $article->setPrice(289.99);
        $article->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article->setAuthorId('1');
        $article->setImage('chaussure.png');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        // Créer le stock pour l'article
        $stock = new Stock();
        $stock->setArticleId($article->getId());
        $stock->setQuantity('10');
        
        $this->entityManager->persist($stock);
        $this->entityManager->flush();
        
        $output->writeln('Article Nike Phantom et son stock ajoutés avec succès');
        return Command::SUCCESS;
    }
} 