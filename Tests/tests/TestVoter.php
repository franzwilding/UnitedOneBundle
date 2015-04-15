<?php

namespace United\OneBundle\Tests\tests;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\Role;

class TestVoter implements VoterInterface
{

    private $attributes = array(
      'access',
      'update',
      'view',
      'delete',
    );
    private $classes = array(
      'United\CoreBundle\Model\EntityInterface',
      'United\CoreBundle\Util\UnitedStructureItem',
    );

    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return bool true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, $this->attributes);
    }

    /**
     * Checks if the voter supports the given class.
     *
     * @param string $class A class name
     *
     * @return bool true if this Voter can process the class
     */
    public function supportsClass($class)
    {
        foreach ($this->classes as $supported) {
            if ($class == $supported || is_subclass_of($class, $supported)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @param object|null $object The object to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object))) {
            return self::ACCESS_ABSTAIN;
        }

        if (!$this->supportsAttribute($attributes[0])) {
            return self::ACCESS_ABSTAIN;
        }

        if (in_array(new Role('ROLE_ADMIN'), $token->getRoles())) {
            return self::ACCESS_GRANTED;
        } else {
            return self::ACCESS_DENIED;
        }
    }
}