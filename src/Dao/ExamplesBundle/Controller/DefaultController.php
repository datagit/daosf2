<?php

namespace Dao\ExamplesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Yaml\Parser;

class DefaultController extends Controller
{
    /**
     * @Route("/examples")
     * @Template()
     */
    public function indexAction()
    {

        $t = $this->get('translator')->trans('Symfony2 is great');
        var_dump($t);

        $t2 = $this->get('translator')->trans('category.is.required');
        var_dump($t2);


        $yaml = new Parser();

        $value = $yaml->parse(file_get_contents('/var/www/html/dao/daosf2/app/config/parameters.yml'));
        echo '<pre>';
        print_r($value);
        return array('name' => 'examples');
    }
}
