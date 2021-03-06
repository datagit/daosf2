<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 12/10/2015
 * Time: 06:33
 */
namespace Dao\DataSourceBundle\DataFixtures\ORM;

use Cocur\Slugify\Slugify;
use Dao\DataSourceBundle\Entity\Category;
use Dao\DataSourceBundle\Entity\ConfigLang;
use Dao\DataSourceBundle\Entity\Post;
use Dao\DataSourceBundle\Entity\Product;
use Dao\DataSourceBundle\Entity\User;
use Dao\DataSourceBundle\Utilities\Lang;
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

        $this->loadLanguage($manager);
        $this->loadCategory($manager, $faker, 100);
        $this->loadProduct($manager, $faker, 100);

    }

    private function loadLanguage(ObjectManager $manager) {
        $vietNam = new ConfigLang();
        $vietNam->setValue('Viet Nam');
        $manager->persist($vietNam);

        $english = new ConfigLang();
        $english->setValue('English');
        $manager->persist($english);

        $manager->flush();
    }

    private function loadCategory(ObjectManager $manager, \Faker\Generator $faker, $totalRecord) {
        $langVN = $manager->getRepository('DaoDataSourceBundle:ConfigLang')->findOneBy(array('id' => Lang::IdVietNam ));
        $langEN = $manager->getRepository('DaoDataSourceBundle:ConfigLang')->findOneBy(array('id' => Lang::IdEnglish ));

        for($i = 0; $i < $totalRecord; $i++) {
            $cat = new Category();
            $cat->setName($this->getRandomPostTitle());
            $cat->setLanguage($this->getRandomValueInArray(array($langVN, $langEN)));
            $cat->setEnabled($this->getRandomValueInArray(array(true, false)));
            $cat->setParent(null);
            $manager->persist($cat);
        }

        $manager->flush();
    }

    private function loadProduct(ObjectManager $manager, \Faker\Generator $faker, $totalRecord) {
        $langVN = $manager->getRepository('DaoDataSourceBundle:ConfigLang')->findOneBy(array('id' => Lang::IdVietNam ));
        $langEN = $manager->getRepository('DaoDataSourceBundle:ConfigLang')->findOneBy(array('id' => Lang::IdEnglish ));

        for($i = 0; $i < $totalRecord; $i++) {
            $title = $faker->sentence(rand(1,3));
            $product = new Product();
            $product->setName($title);
            $product->setDescription($faker->text());
            $product->setTags($faker->word);
            $product->setEan($faker->ean13);
            $product->setEnabled($this->getRandomValueInArray(array(true, false)));
            $product->setLanguage($this->getRandomValueInArray(array($langVN, $langEN)));
            $product->setPrice($faker->randomDigit);

            $manager->persist($product);
        }

        $manager->flush();
    }



    private function loadPost(ObjectManager $manager, \Faker\Generator $faker, $totalRecord) {
        $slugify = new Slugify();
        for($i = 0; $i < $totalRecord; $i++) {
            $title = $faker->sentence(rand(1,3));

            $post = new Post();
            $post->setContent($faker->sentence(rand(6,20)));
            $post->setAuthorEmail($faker->email);
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

    private function getPostContent()
    {
        return <<<MARKDOWN
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

  * Ut enim ad minim veniam
  * Quis nostrud exercitation *ullamco laboris*
  * Nisi ut aliquip ex ea commodo consequat

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
Sed in egestas erat.

Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
tincidunt, faucibus nisl in, aliquet libero.
MARKDOWN;
    }

    private function getPhrases()
    {
        return array(
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
        );
    }

    private function getRandomValueInArray($value) {
        return $value[array_rand($value)];
    }

    private function getRandomPostTitle()
    {
        $titles = $this->getPhrases();

        return $titles[array_rand($titles)];
    }

    private function getRandomPostSummary()
    {
        $phrases = $this->getPhrases();

        $numPhrases = rand(6, 12);
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, $numPhrases-1));
    }

    private function getRandomCommentContent()
    {
        $phrases = $this->getPhrases();

        $numPhrases = rand(2, 15);
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, $numPhrases-1));
    }

}