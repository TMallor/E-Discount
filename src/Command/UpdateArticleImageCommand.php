<?php

namespace App\Command;

use App\Entity\Article;
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
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Met à jour l\'image de l\'article Nike');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $article = $this->entityManager->getRepository(Article::class)
            ->findOneBy(['name' => 'Nike Air Max 90']);

        if ($article) {
            $article->setImage('image/nike.png');
            $this->entityManager->flush();
            $output->writeln('Image mise à jour avec succès');
            return Command::SUCCESS;
        }

        $article = new Article();
        $article->setName('Nike Air Max 90');
        $article->setClass('chaussures');
        $article->setMainfeatures('Chaussures de sport confortables');
        $article->setDescription('Les Nike Air Max 90 sont des chaussures de sport emblématiques qui offrent un excellent confort et un style intemporel.');
        $article->setPrice(129.99);
        $article->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article->setAuthorId(1);
        $article->setImage('image/nike.png');

        $this->entityManager->persist($article);
        $this->entityManager->flush();
        
        $output->writeln('Article Nike créé avec succès');
        return Command::SUCCESS;
    }
} 