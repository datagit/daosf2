<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 12/10/2015
 * Time: 06:33
 */
namespace Dao\DataSourceBundle\DataFixtures\ORM;

use Cocur\Slugify\Slugify;
use Dao\DataSourceBundle\Entity\Post;
use Dao\DataSourceBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData implements FixtureInterface, ContainerAwareInterface
{
    public static $DedaultLocale = array('en_US', 'ja_JP');

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
        $fullPathRoot = dirname($this->container->get('kernel')->getRootDir());
        // require the Faker autoloader
        require_once $fullPathRoot . '/vendor/fzaninotto/faker/src/autoload.php';
        // alternatively, use another PSR-0 compliant autoloader (like the Symfony2 ClassLoader for instance)

        // use the factory to create a Faker\Generator instance
        $faker = \Faker\Factory::create();

        $this->loadPost($manager, $faker, 100);
    }

    private function loadPost(ObjectManager $manager, \Faker\Generator $faker, $totalRecord) {
        $slugify = new Slugify();
        for($i = 0; $i < $totalRecord; $i++) {
            $post = new Post();
            $post->setContent($faker->sentence(rand(6,20)));
            $post->setAuthorEmail($faker->email);
            $title = $faker->sentence(rand(1,3));
            $post->setTitle($title);
            $post->setSlug($slugify->slugify($title));
            $post->setTags($faker->word);
            $post->setSummary($faker->text());

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }

}