<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0 ; $i < 60; $i++){
            $user = new User();
            $user->setUsername('user_'.$i);
            $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
            $user->setEmail('user'.$i.'@fake.fr');
            $user->setRegisterDate(new \DateTime('-'.$i.' days'));
            $user->setRoles('ROLE_USER');
            $manager->persist($user); // Il demande a doctrine de preparer l'insertion de $user en base de donnees
        }
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword(password_hash('admin', PASSWORD_BCRYPT));
        $admin->setEmail('admin@fake.fr');
        $admin->setRegisterDate(new \DateTime('now'));
        $admin->setRoles('ROLE_ADMIN');
        $manager->persist($admin);
        $manager->flush();
         // flush valide les requetes et les executes 
    }
}