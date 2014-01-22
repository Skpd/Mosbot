<?php

namespace Blog\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class Entry extends DocumentRepository
{
    public function getRecent($onlyPublished = true)
    {
        return $this->findBy(['published' => $onlyPublished], ['created' => 'desc']);
    }
}