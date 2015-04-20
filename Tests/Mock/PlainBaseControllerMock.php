<?php

namespace United\OneBundle\Tests\Mock;

use United\OneBundle\Controller\PlainBaseController;

class PlainBaseControllerMock extends PlainBaseController
{
    protected function templateForIndexAction()
    {
        return "UnitedCoreBundle:Tests:DumpContext.html.twig";
    }
}