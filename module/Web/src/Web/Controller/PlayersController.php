<?php

namespace Web\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class PlayersController extends AbstractActionController
{
    private $documentManager;
    private $hydrator;
    private $entityName = 'Runner\Document\Player';

    public function indexAction()
    {

    }

    public function getListAction()
    {
        /** @var \Zend\Stdlib\Parameters $parameters */
        $parameters = $this->request->getQuery();
        $builder = $this->getDocumentManager()->createQueryBuilder($this->entityName);

        $builder->limit($parameters['iDisplayLength']);
        $builder->skip($parameters['iDisplayStart']);

        $builder->field('alignment')->notEqual(null);

        $columns = array();

        /** @var \Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata */
        $metadata = $this->getDocumentManager()->getClassMetadata($this->entityName);

        for ($i = 0; $i < $parameters['iColumns']; $i++) {
            $field = $parameters['mDataProp_' . $i];
            $field = current(explode('.', $field));

            if ($metadata->hasField($field)) {
                $columns[$i] = $field;
            } else {
                throw new \RuntimeException("Field '$field' not found in class metadata.");
            }
        }

        for ($i = 0; $i < $parameters['iSortingCols']; $i++) {
            if ($parameters['bSortable_' . $i]) {
                $builder->sort($columns[$parameters['iSortCol_' . $i]], $parameters['sSortDir_' . $i]);
            }
        }

        $data = array();

        foreach ($builder->getQuery()->getIterator() as $record) {
            $data[] = $this->getHydrator()->extract($record);
        }

        return new JsonModel(
            array(
                 'aaData'               => $data,
                 'iTotalRecords'        => $builder->getQuery()->count(),
                 'iTotalDisplayRecords' => $builder->getQuery()->count(),
            )
        );
    }

    /**
     * @return DoctrineObject
     */
    public function getHydrator()
    {
        if (null === $this->hydrator) {
            $this->hydrator = new DoctrineObject($this->getDocumentManager(), $this->entityName);
        }

        return $this->hydrator;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        if (null === $this->documentManager) {
            $this->documentManager = $this->serviceLocator->get('doctrine.documentmanager.odm_default');
        }

        return $this->documentManager;
    }
}