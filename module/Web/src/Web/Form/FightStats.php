<?php

namespace Web\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class FightStats extends Form implements InputFilterProviderInterface
{
    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'url' => [
                'validators' => [
                    ['name' => 'Uri'],
                    ['name' => 'Regex', 'options' => ['pattern' => '|^.*\.ru/fight/\d+[/]*$|i']]
                ],
                'filters' => [
                    ['name' => 'StringTrim', 'options' => ['charlist' => '/']]
                ]
            ]
        ];
    }


    public function init()
    {
        $this->add(['name' => 'url', 'type' => 'text']);
    }
}