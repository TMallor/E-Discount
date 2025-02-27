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
    name: 'app:update-nike-image',
    description: 'Met à jour l\'image de l\'article Nike'
)]
class UpdateArticleImageCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $article = $this->entityManager->getRepository(Article::class)
            ->findOneBy(['name' => 'Nike Air Max 90']);

        if ($article) {
            $article->setImage('nike.png');
            $this->entityManager->flush();
            $output->writeln('Image mise à jour avec succès');
            return Command::SUCCESS;
        }

        $article = new Article();
        $article->setName('Nike Air Max 90');
        $article->setCategory(ArticleCategory::CHAUSSURES->value);
        $article->setMainfeatures('Chaussures de sport confortables');
        $article->setDescription('Les Nike Air Max 90 sont des chaussures de sport emblématiques qui offrent un excellent confort et un style intemporel.');
        $article->setPrice(129.99);
        $article->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article->setAuthorId('1');
        $article->setImage('nike.png');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        // Créer le stock pour l'article
        $stock = new Stock();
        $stock->setArticleId($article->getId());
        $stock->setQuantity('15');
        
        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        $output->writeln('Article Nike Air Max 90 et son stock créés avec succès');
        return Command::SUCCESS;
    }
} 