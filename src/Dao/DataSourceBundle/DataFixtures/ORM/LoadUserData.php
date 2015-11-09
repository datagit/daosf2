<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 12/10/2015
 * Time: 06:33
 */
namespace Dao\DataSourceBundle\DataFixtures\ORM;

use Dao\DataSourceBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPlainPassword('admin');
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setEnabled(true);
        $admin->setCreatedAt(new \DateTime());

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($admin)
        ;
        $admin->setPassword($encoder->encodePassword('secret', $admin->getSalt()));
        $manager->persist($admin);

        //guest
        $guest = new User();
        $guest->setUsername('guest');
        $guest->setEmail('guest@gmail.com');
        $guest->setPlainPassword('123456');
        $guest->setRoles(array('ROLE_GUEST'));
        $guest->setEnabled(true);
        $guest->setCreatedAt(new \DateTime());

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($guest)
        ;
        $guest->setPassword($encoder->encodePassword('secret', $guest->getSalt()));

        $manager->persist($guest);

        //user
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@gmail.com');
        $user->setPlainPassword('123456');
        $user->setRoles(array('ROLE_USER'));
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTime());

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;
        $guest->setPassword($encoder->encodePassword('secret', $user->getSalt()));

        $manager->persist($user);

        $manager->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

}