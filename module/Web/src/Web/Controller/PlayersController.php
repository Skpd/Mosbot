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

        $builder->field('lastUpdate')->exists(true);

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

        $separator = $parameters['sRangeSeparator'];

        foreach ($columns as $key => $column) {
            if ($parameters['bSearchable_' . $key]) {
                $columnSearchValue = $parameters['sSearch_' . $key];

                if ($columnSearchValue !== null && $columnSearchValue != '' && $columnSearchValue != $separator) {

                    $separatorPosition = strpos($columnSearchValue, $separator);

                    if ($separatorPosition !== false) {
                        $columnSearchValue = explode($separator, $columnSearchValue);

                        foreach ($columnSearchValue as $k => $v) {
                            if ($v == '') {
                                unset($columnSearchValue[$k]);
                            }
                        }

                    }

                    if ($metadata->getTypeOfField($column) == 'int' || $metadata->getTypeOfField($column) == 'custom_id') {
                        $columnSearchValue = is_array($columnSearchValue) ? array_map('intval', $columnSearchValue) : intval($columnSearchValue);
                    }

                    switch ($column) {
                        default:
                            if (is_array($columnSearchValue)) {
                                if ($separatorPosition != 0 && isset($columnSearchValue[1])) {
                                    $builder->field($column)->lte($columnSearchValue[1]);
                                }

                                if ($separatorPosition != strlen($parameters['sSearch_' . $key]) && isset($columnSearchValue[0])) {
                                    $builder->field($column)->gte($columnSearchValue[0]);
                                }
                            } else {
                                $builder->field($column)->equals($columnSearchValue);
                            }
                            break;
                    }
                }
            }
        }
        $debug = $builder->getQuery()->debug();

        if (!empty($debug)) {
//            var_dump(json_encode($debug));
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