<?php
/**
 * Created by PhpStorm.
 * User: dmitriyt
 * Date: 2019-06-18
 * Time: 22:59
 */

namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class InitProjectCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('admin:init')
            ->setDescription('Разворачивание проекта, создает роли, адмиdoнистраторов, сессию');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Create admin</info>');
        $question = array(
            "Yes",
            "No",
        );

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Create admin?',
            $question,
            0
        );
        $type = $helper->ask($input, $output, $question);
        $output->writeln("<info>$type</info>");
        if ($type == 'Y' || $type == 'Yes') {
            $output->writeln('<info>Create admin</info>');

            $user = new User();
            $user->setRole('ROLE_ADMIN');
            $question = new Question(
                'NickName:?',
                'NickName'
            );
            $name = $helper->ask($input, $output, $question);
            $user->setNickname($name);

            $question = new Question(
                'login:?',
                'admin'
            );
            $email = $helper->ask($input, $output, $question);
            $user->setLogin($email);

            $question = new Question(
                'Password:?',
                'admin'
            );
            $password = $helper->ask($input, $output, $question);
            $user->setPassword($password);
            $defaultEncoder = new MessageDigestPasswordEncoder('sha512', true, 5000);
            $encoders = [User::class => $defaultEncoder];
            $encoderFactory = new EncoderFactory($encoders);
            $encoder = $encoderFactory->getEncoder($user);
            $user->setSalt(md5(time() . $user->getPassword()));
            $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));
            $this->getManager()->persist($user);
        }
        $this->getManager()->flush();
        $output->writeln('<info>Done</info>');

    }

    /**
     * @return EntityManager|object
     */
    private function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
