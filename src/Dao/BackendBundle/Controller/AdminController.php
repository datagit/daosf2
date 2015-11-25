<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/3/15
 * Time: 2:52 PM
 */

namespace Dao\BackendBundle\Controller;

use Dao\BackendBundle\Entity\MyConfig;
use Dao\DataSourceBundle\Entity\ConfigLang;
use Dao\DataSourceBundle\Utilities\Lang;
use Dao\DataSourceBundle\Utilities\LanguageSupport;
use Dao\DataSourceBundle\Utilities\MySession;
use Dao\DataSourceBundle\Utilities\StringHelper;
use Ladybug\Plugin\Symfony2\Inspector\Object\Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends EasyAdminController
{
    /**
     * Performs a database query based on the search query provided by the user.
     * It supports pagination and field sorting.
     *
     * @param string $entityClass
     * @param string $searchQuery
     * @param array  $searchableFields
     * @param int    $page
     * @param int    $maxPerPage
     *
     * @return Pagerfanta The paginated query results
     */
    protected function findBy($entityClass, $searchQuery, array $searchableFields, $page = 1, $maxPerPage = 15)
    {
        $queryBuilder = $this->em->createQueryBuilder()->select('entity')->from($entityClass, 'entity');

        //st dat.dao custom where
        $queryBuilder = $this->addWhereForCustom($queryBuilder, $entityClass);
        //ed dat.dao custom where

        $queryConditions = $queryBuilder->expr()->orX();
        $queryParameters = array();
        foreach ($searchableFields as $name => $metadata) {
            if (in_array($metadata['dataType'], array('text', 'string'))) {
                $queryConditions->add(sprintf('entity.%s LIKE :query', $name));
                $queryParameters['query'] = '%'.$searchQuery.'%';
            } else {
                $queryConditions->add(sprintf('entity.%s IN (:words)', $name));
                $queryParameters['words'] = explode(' ', $searchQuery);
            }
        }

        $queryBuilder->add('where', $queryConditions)->setParameters($queryParameters);

        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Performs a database query to get all the records related to the given
     * entity. It supports pagination and field sorting.
     *
     * @param string      $entityClass
     * @param int         $page
     * @param int         $maxPerPage
     * @param string|null $sortField
     * @param string|null $sortDirection
     *
     * @return Pagerfanta The paginated query results
     */
    protected function findAll($entityClass, $page = 1, $maxPerPage = 15, $sortField = null, $sortDirection = null)
    {
        $query = $this->em->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity')
        ;

        //st dat.dao custom where
        $query = $this->addWhereForCustom($query, $entityClass);
        //ed dat.dao custom where

        if (null !== $sortField) {
            if (empty($sortDirection) || !in_array(strtoupper($sortDirection), array('ASC', 'DESC'))) {
                $sortDirection = 'DESC';
            }

            $query->orderBy('entity.'.$sortField, $sortDirection);
        }

        $paginator = new Pagerfanta(new DoctrineORMAdapter($query, false));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @author Dat.Dao
     * @param \Doctrine\ORM\QueryBuilder $query
     * @param $entityClass
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function addWhereForCustom(\Doctrine\ORM\QueryBuilder $query, $entityClass) {
        $session = $this
            ->container
            ->get('request_stack')
            ->getCurrentRequest()
            ->getSession();
        $idLang = $session->get(MySession::LangKey) != null ? $session->get(MySession::LangKey) : Lang::IdVietNam;
        if( ! StringHelper::isMatch($entityClass, array('User')) ) {
            $language = new ConfigLang();
            $language->setId($idLang);
            $query->where('entity.language = ?1')
                ->setParameter(1, $language);
        }
        return $query;
    }


//    public function listAction()   {
//        $logger = $this->get('logger');
//        $logger->info('event listAction in admin. ' . $this->entity['class']);
//
//        $this->dispatch(EasyAdminEvents::PRE_LIST);
//        $fields = $this->entity['list']['fields'];
//        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->config['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'));
//        //st custom logic
//        //$this->entity['class']: Dao\DataSourceBundle\Entity\Product\
//        if( ! StringHelper::isMatch($this->entity['class'], array('User')) ) {
//            $searchableFields = $this->entity['search']['fields'];
//            //var_dump($searchableFields);die;
//            //$paginator = $this->findBy($this->entity['class'], 'en', array($searchableFields['local']), $this->request->query->get('page', 1), $this->config['list']['max_results']);
//        }
//        //ed custom logic
//        //var_dump($paginator);
//        $this->dispatch(EasyAdminEvents::POST_LIST, array('paginator' => $paginator));
//
//        //var_dump($paginator);die;
//        return $this->render($this->entity['templates']['list'], array(
//            'paginator' => $paginator,
//            'fields' => $fields,
//        ));
//    }

    public function prePersistEntity($entity) {
        $logger = $this->get('logger');
        $logger->info('event prePersistEntity in admin. ');

    }

    public function preUpdateEntity($entity) {
        $logger = $this->get('logger');
        $logger->info('event preUpdateEntity in admin. ');

    }

    public function preRemoveEntity($entity) {
        $logger = $this->get('logger');
        $logger->info('event preRemoveEntity in admin. ');

    }

    public function restockAction()
    {
        // controllers extending the base AdminController can access to the
        // following variables:
        //   $this->request, stores the current request
        //   $this->em, stores the Entity Manager for this Doctrine entity

        // change the properties of the given entity and save the changes
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository('DaoDataSourceBundle:Post')->find($id);
        var_dump($entity);
        //$entity->setStock(100 + $entity->getStock());
        //$this->em->flush();

        // redirect to the 'list' view of the given entity
        return $this->redirectToRoute('admin', array(
            'view' => 'list',
            'entity' => $this->request->query->get('entity'),
        ));

        // redirect to the 'edit' view of the given entity item
//        return $this->redirectToRoute('admin', array(
//            'view' => 'edit',
//            'id' => $id,
//            'entity' => $this->request->query->get('entity'),
//        ));
    }

    public function copyAction()
    {
        // controllers extending the base AdminController can access to the
        // following variables:
        //   $this->request, stores the current request
        //   $this->em, stores the Entity Manager for this Doctrine entity

        // change the properties of the given entity and save the changes
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository('DaoDataSourceBundle:Post')->find($id);
        var_dump($entity);
        //$entity->setStock(100 + $entity->getStock());
        //$this->em->flush();

        // redirect to the 'edit' view of the given entity item
        return $this->redirectToRoute('admin', array(
            'view' => 'edit',
            'id' => $id,
            'entity' => $this->request->query->get('entity'),
        ));
    }

    /**
     * @Route(path = "/export", name = "admin_export")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function exportAction()
    {
        return array('name' => 'datdao');
    }

    /**
     * @Route(path = "/my-config", name = "admin_my_config")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function myConfigAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $session = $this
            ->container
            ->get('request_stack')
            ->getCurrentRequest()
            ->getSession();
        $idLang = $session->get(MySession::LangKey) != null ? $session->get(MySession::LangKey) : Lang::IdVietNam;

        // create a task and give it some dummy data for this example
        $myConfig = new MyConfig();
        $myConfig->setLang($idLang);


        $form = $this->createFormBuilder($myConfig)
            ->add('lang', 'choice', array(
                'choices'  => $this->buildLanguageChoices(),
                'required' => false,
            ))
            ->add('Exporting', 'submit', array('label' => 'Exporting'))
            ->add('Importing', 'submit', array('label' => 'Importing'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this
                ->container
                ->get('request_stack')
                ->getCurrentRequest()
                ->getSession()
                ->set(MySession::LangKey, $myConfig->getLang());
        }

        return array(
            'form' => $form->createView(),
        );
    }

    protected function buildLanguageChoices() {
        $choices          = [];
        $table2Repository = $this->getDoctrine()->getRepository('DaoDataSourceBundle:ConfigLang');
        $table2Objects    = $table2Repository->findAll();
        foreach ($table2Objects as $table2Obj) {
            $choices[$table2Obj->getId()] = $table2Obj->getValue();
        }
        return $choices;
    }

}