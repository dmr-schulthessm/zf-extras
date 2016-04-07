<?php

namespace ZfExtra\User\Command;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use ZfExtra\Acl\AclRoleProviderInterface;
use ZfExtra\Console\Command\AbstractServiceLocatorAwareCommand;
use ZfExtra\User\UserManager;

class CreateUserCommand extends AbstractServiceLocatorAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('user:create')
                ->setDescription('Create new user.')
                ->addArgument('email', InputArgument::REQUIRED, 'Email')
                ->addArgument('password', InputArgument::OPTIONAL, 'Password')
                ->addOption('role', 'r', InputOption::VALUE_OPTIONAL, sprintf('Role (if implements %s', AclRoleProviderInterface::class))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        /* @var $userManager UserManager */
        $userManager = $this->serviceLocator->getServiceLocator()->get('user_manager');

        if ($userManager->existsBy(['email' => $email])) {
            return $output->writeln(sprintf('<error>The user with email "%s" is already registered.</error>', $email));
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $question = new Question('Password: ');
            $helper = new QuestionHelper;
            $password = $helper->ask($input, $output, $question);
        }
        if (!$password) {
            return $output->writeln('<error>The password has not been provided.</error>');
        }

        $user = $userManager->create(['email' => $email], true);
        $user->setPlainPassword($password);

        if ($user instanceof AclRoleProviderInterface) {
            $role = $input->getOption('role');
            if (!$role) {
                return $output->writeln(sprintf('<error>Entity implements %s therefore the --role switch must be set.</error>', AclRoleProviderInterface::class));
            }
            $user->setRole($role);
        }

        $userManager->update($user);
        $output->writeln(sprintf('Created user <comment>%s</comment> with id <comment>%d</comment>.', $email, $user->getId()));
    }

}
