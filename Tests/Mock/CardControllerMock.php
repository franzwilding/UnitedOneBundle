<?php

namespace United\OneBundle\Tests\Mock;

use Doctrine\ORM\EntityRepository;
use United\CoreBundle\Tests\Mock\EntityRepositoryMock;
use United\OneBundle\Controller\CardController;

class CardControllerMock extends CardController
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new EntityRepositoryMock();
    }

    /**
     * Returns the entity repository for the CRUD operations.
     *
     * @return EntityRepository
     */
    protected function getEntityRepository() {
        return $this->repository;
    }

    /**
     * Returns a new entity.
     *
     * @return object
     */
    protected function createNewEntity() {

    }

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