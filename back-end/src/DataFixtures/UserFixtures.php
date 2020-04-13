<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername('bob');
        $user->setUsernameCanonical('Bobby');
        $user->setEmail('bob@doe.com');
        $user->setEnabled(true);
        // Password is Abc123
        $user->setSalt('e4TNCCgLvPbDRh7ih+pK58pab0NToFzdZHuPmA0e');
        $user->setPassword('sM1GM+zlChZH4xlLokuCueeWSDq+4I7XGtn+GErMF1ehMVrFRXWklCK0/LtTZ4gQEebbLW9lrSS0ocA/9/12Gw==');

        $manager->persist($user);
        $manager->flush();
    }
}
