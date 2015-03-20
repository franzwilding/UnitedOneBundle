<?php

namespace United\OneBundle\Tests\Mock;

use Doctrine\ORM\EntityRepository;
use United\OneBundle\Controller\TableController;

class TableControllerMock extends TableController
{

    /**
     * Returns the entity repository for the CRUD operations.
     *
     * @return EntityRepository
     */
    protected function getEntityRepository() {}

    /**
     * Returns a new entity.
     *
     * @return object
     */
    protected function createNewEntity() {}

    /**
     * Calls any internal function.
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function getM($name, $parameters = array())
    {
        return call_user_func_array(array($this, $name), $parameters);
    }
}