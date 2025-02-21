<?php

namespace App\Command;

use App\Entity\Article;
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
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $article = new Article();
        $article->setName('Nike Phantom Gx 2 Elite Erling Haaland Fg Bleu');
        $article->setClass('chaussures');
        $article->setMainfeatures('Chaussures de football professionnelles');
        $article->setDescription('Les Nike Phantom GX 2 Elite, signature Erling Haaland, offrent une précision et un contrôle exceptionnels sur le terrain.');
        $article->setPrice(289.99);
        $article->setPublicationDate((new \DateTime())->format('Y-m-d H:i:s'));
        $article->setAuthorId(1);
        $article->setImage('image/chaussure.png');

        $this->entityManager->persist($article);
        $this->entityManager->flush();
        
        $output->writeln('Article Nike Phantom ajouté avec succès');
        return Command::SUCCESS;
    }
} 