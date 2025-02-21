<?php

namespace App\Command;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:remove-duplicate-nike',
    description: 'Supprime les doublons de l\'article Nike'
)]
class RemoveDuplicateNikeCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $articles = $this->entityManager->getRepository(Article::class)
            ->findBy(['name' => 'Nike Air Max 90'], ['id' => 'ASC']);

        if (count($articles) > 1) {
            // Garder le premier, supprimer les autres
            array_shift($articles); // Enlève le premier article du tableau
            foreach ($articles as $article) {
                $this->entityManager->remove($article);
            }
            $this->entityManager->flush();
            $output->writeln('Doublons supprimés avec succès');
            return Command::SUCCESS;
        }

        $output->writeln('Aucun doublon trouvé');
        return Command::SUCCESS;
    }
} 