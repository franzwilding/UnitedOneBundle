<?php

namespace United\OneBundle\Controller;

use GuzzleHttp\Message\Response;
use United\CoreBundle\Controller\ControllerViewInterface;
use United\CoreBundle\Model\EntityInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CollectionController
 * Defines a controller to display collections containing content provided by
 * another controller.
 * @package United\OneBundle\Controller
 */
abstract class CollectionController extends CRUDBaseController implements ControllerViewInterface
{

    /**
     * Returns the template for the given action. For the base implementation,
     * $action can be: index|create|update|delete.
     *
     * @param string $action the action to get the twig template for
     * @return string the twig template to render
     */
    protected function getTemplateForAction($action)
    {
        switch ($action) {
            case 'index':
                return 'UnitedOneBundle:Collection:index.html.twig';
                break;
            case 'view':
                return 'UnitedOneBundle:Collection:view.html.twig';
                break;
            case 'ajax':
                return 'UnitedOneBundle:Collection:ajax.html.twig';
                break;
            case 'item':
                return 'UnitedOneBundle:Collection:item.html.twig';
                break;
            default:
                return parent::getTemplateForAction($action);
                break;
        }
    }

    protected function alterContextForAction($action, &$context)
    {
        parent::alterContextForAction($action, $context);

        if ($action == 'view') {
            $context['itemTemplate'] = $this->getTemplateForAction('item');
        }
    }

    /**
     * Redirects to the first entity or renders a get started text.
     *
     * @Route("/")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function indexAction()
    {
        $this->checkActionAccess(); // Check if we can access this action

        // Redirect to first entity view
        if (count($items = $this->findIndexEntities()) > 0) {
            $unitedStructure = $this->get('united.core.structure');
            $currentItem = $unitedStructure->getItemFromRequest($this->get('request'));
            $route = substr($currentItem->getRoute(), 0, -5) . 'view';
            return $this->redirectToRoute($route, array('id' => $items[0]->getId()));
        } // Render a get started text
        else {
            $context = array();
            $this->alterContextForAction('index', $context);
            return $this->render($this->getTemplateForAction('index'), $context);
        }
    }

    /**
     * Renders the items of one collection.
     *
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function viewAction($id)
    {
        $this->checkActionAccess(); // Check if we can access this action

        $entity = $this->findEntityById($id);
        $entities = $this->findIndexEntities();

        $context = array('entity' => $entity, 'entities' => $entities);
        $this->alterContextForAction('view', $context);
        return $this->render($this->getTemplateForAction('view'), $context);
    }

    public function viewEntity($id)
    {
        return $this->findEntityById($id);
    }
}