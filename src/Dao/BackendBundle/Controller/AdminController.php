<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/3/15
 * Time: 2:52 PM
 */

namespace Dao\BackendBundle\Controller;

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

        //dat.dao custom where
        if( ! StringHelper::isMatch($entityClass, array('User')) ) {
            $query->where('entity.local = ?1')
                ->setParameter(1, 'en');
        }

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
        $logger->info('event prePersistEntity in admin. ' . serialize($entity));

    }

    public function preUpdateEntity($entity) {
        $logger = $this->get('logger');
        $logger->info('event preUpdateEntity in admin. ' . serialize($entity));

    }

    public function preRemoveEntity($entity) {
        $logger = $this->get('logger');
        $logger->info('event preRemoveEntity in admin. ' . serialize($entity));

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
    public function myConfigAction()
    {
        return array('name' => 'datdao');
    }

}