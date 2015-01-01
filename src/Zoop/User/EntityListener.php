<?php

namespace Zoop\User;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zoop\Entity\DataModel\ChildEntityInterface;
use Zoop\Entity\DataModel\EntityInterface;
use Zoop\Entity\Events as EntityEvents;
use Zoop\User\Service\SystemUserUtil;
use Zoop\User\DataModel\UserInterface;
use Zoop\Entity\DataModel\EntitiesFilterInterface;
use Zoop\Entity\DataModel\EntityFilterInterface;

class EntityListener implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const USER_COLLECTION = 'Zoop\User\DataModel\AbstractUser';

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];

    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(EntityEvents::ENTITIES_POST_PERSIST, [$this, 'addEntitiesToUsers'], 1);
        $this->listeners[] = $events->attach(EntityEvents::ENTITY_POST_PERSIST, [$this, 'addEntityToUsers'], 1);
    }

    /**
     * Detach listeners from an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function addEntityToUsers(EventInterface $event)
    {
        $entity = $event->getParams();
        if ($entity instanceof EntityFilterInterface) {
        }
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function addEntitiesToUsers(EventInterface $event)
    {
        $entity = $event->getParams();
        if ($entity instanceof ChildEntityInterface && $entity instanceof EntitiesFilterInterface) {
            $parent = $entity->getParent();
            $users = $this->getParentUsers($parent);
            foreach ($users as $user) {
                $this->addEntitiesToUser($user, $entity->getEntities());

                //this is a little slower but avoids flushing documents
                //that may have been changed duringh this process that we
                //dont have permissions for.
                $this->getDocumentManager()->flush($user);
            }
        }
    }

    /**
     * @param UserInterface $user
     * @param array $entities
     */
    protected function addEntitiesToUser(UserInterface $user, array $entities = [])
    {
        if ($user instanceof EntitiesFilterInterface) {
            foreach ($entities as $entity) {
                $user->addEntity($entity);
            }
        }
    }

    /**
     * @param EntityInterface $parent
     * @return mixed
     */
    protected function getParentUsers(EntityInterface $parent)
    {
        $systemUserUtil = $this->getSystemUserUtil();
        //set system user
        $systemUserUtil->addSystemUser();

        $users = $this->getDocumentManager()
            ->createQueryBuilder(self::USER_COLLECTION)
            ->field('entities')->in([$parent->getSlug()])
            ->getQuery()
            ->execute();

        //remove the system user and restore user if available
        $systemUserUtil->removeSystemUser();

        return $users;
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->getServiceLocator()
            ->get('doctrine.odm.documentmanager.commerce');
    }

    /**
     * @return SystemUserUtil
     */
    protected function getSystemUserUtil()
    {
        return $this->getServiceLocator()
            ->get('zoop.commerce.user.systemuserutil');
    }
}
