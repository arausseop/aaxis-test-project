<?php

namespace App\Command\User;

use App\Service\User\CreateUserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    public function __construct(
        private readonly CreateUserService $createUserService
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Create new user in the system')
            ->addArgument('name', InputArgument::REQUIRED, 'User name')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $this->createUserService->create($name, $email, $password);

        $output->writeln(sprintf('User [%s] has been created', $name));

        return Command::SUCCESS;
    }
}
