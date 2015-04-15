<?php

namespace United\OneBundle\EventListener;

use Doctrine\ORM\PersistentCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ExistingTagsEventListener implements EventSubscriberInterface
{

    private $options;
    private $existing_ids;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;
        $this->existing_ids = array();
    }

    public function preSubmit(FormEvent $event)
    {
        // If existing entities are added, we need to save the array position for them.
        if (is_array($event->getData())) {
            foreach ($event->getData() as $key => $data) {
                if (array_key_exists('id', $data)) {
                    $this->existing_ids[$key] = $data['id'];
                }
            }
        }
    }

    public function postSubmit(FormEvent $event)
    {
        /**
         * @var PersistentCollection $collection
         */
        $collection = $event->getData();

        /**
         * To avoid creating of the same entity again, we need to replace the new entity with an existing one.
         */
        foreach ($collection as $key => $item) {
            if (array_key_exists($key, $this->existing_ids)) {
                $collection->remove($key);
                $collection->add($this->options[$this->existing_ids[$key]]);
            }
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
          FormEvents::PRE_SUBMIT => 'preSubmit',
          FormEvents::POST_SUBMIT => 'postSubmit',
        );
    }
}