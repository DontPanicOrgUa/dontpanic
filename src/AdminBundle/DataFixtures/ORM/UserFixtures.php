<?php

namespace WebBundle\DataFixtures\ORM;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class UserFixtures implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var $user \AdminBundle\Entity\User */
        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@example.com');
        $user->setPlainPassword('123456789');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPhone('0507095075');

        /** @var $user \AdminBundle\Entity\User */
        $user2 = $userManager->createUser();
        $user2->setUsername('manager');
        $user2->setEmail('mamger@example.com');
        $user2->setPlainPassword('987654321');
        $user2->setEnabled(true);
        $user2->setRoles(['ROLE_MANAGER']);
        $user2->setPhone('0507095075');

        $userManager->updateUser($user, true);
        $userManager->updateUser($user2, true);
    }
}