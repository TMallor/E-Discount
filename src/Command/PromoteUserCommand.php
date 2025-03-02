<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:promote-user',
    description: 'Promouvoir un utilisateur en admin'
)]
class PromoteUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        
        if (!$user) {
            $output->writeln('Utilisateur non trouvé');
            return Command::FAILURE;
        }

        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->entityManager->flush();

        $output->writeln('Utilisateur promu admin avec succès');
        return Command::SUCCESS;
    }
} 