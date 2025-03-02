<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:initialize-stocks',
    description: 'Initialise les stocks pour tous les articles'
)]
class InitializeStocksCommand extends Command
{
    protected static $defaultName = 'app:initialize-stocks';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        foreach ($articles as $article) {
            if (!$article->getStock()) {
                $stock = new Stock();
                $stock->setArticle($article);
                $stock->setQuantity(10); // Quantité par défaut
                $this->entityManager->persist($stock);
            }
        }

        $this->entityManager->flush();
        $output->writeln('Stocks initialisés avec succès');

        return Command::SUCCESS;
    }
} 