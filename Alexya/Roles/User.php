<?php
namespace Alexya\Roles;

use Alexya\Tools\Collection;

/**
 * User class.
 * ===========
 *
 * This class represents an user, where the roles are assigned.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class User
{
    /**
     * User roles.
     *
     * @var Collection
     */
    public $roles;

    /**
     * Whether the collection needs to be resorted or not.
     *
     * @var bool
     */
    private $_sort;

    /**
     * Constructor.
     *
     * @param Role[] $roles User roles.
     */
    public function __construct(array $roles = [])
    {
        $this->roles = new Collection($roles);

        $this->_sort();
    }

    /**
     * Sorts the collection.
     */
    private function _sort()
    {
        $this->roles->sort(function($a, $b) {
            if($a->priority == $b->priority) {
                return 0;
            }

            return ($a->priority < $b->priority) ? -1 : 1;
        });
    }

    /**
     * Checks that this user has has granted specified permission.
     *
     * @param mixed $permission Permission to check.
     *
     * @return bool Whether this user has `$permission` granted or not.
     */
    public function hasPermission($permission) : bool
    {
        if($this->_sort) {
            $this->_sort();

            $this->_sort = false;
        }

        /**
         * Roles.
         *
         * @var Role[] $roles
         */
        $roles = $this->roles->getAll();

        foreach($roles as $role) {
            if($role->getPermission($permission) == null) {
                continue;
            }

            return $role->can($permission);
        }

        return false;
    }

    /**
     * Checks that this user has has granted specified permission.
     *
     * @param mixed $permission Permission to check.
     *
     * @return bool Whether this user has `$permission` granted or not.
     */
    public function can($permission) : bool
    {
        return $this->hasPermission($permission);
    }

    /**
     * Adds a new role to the user.
     *
     * @param Role $role Role to add to the user.
     */
    public function addRole(Role $role)
    {
        $this->roles->set($role->title, $role);

        $this->_sort = true;
    }

    /**
     * Removes a role from the user.
     *
     * @param mixed $role Role to remove.
     */
    public function removeRole($role)
    {
        $this->roles = $this->roles->filter(function($key, $value) use($role) {
            if(is_numeric($role)) {
                return $value->id == $role;
            }

            if(is_string($role)) {
                return $value->title == $role;
            }

            if($role instanceof Role) {
                return ($value instanceof $role);
            }

            return false;
        });
    }



    /**
     * Returns specified role.
     *
     * @param mixed $role Role to retrieve.
     *
     * @return Role|null Role for `$role`.
     */
    public function getRole($role)
    {
        /**
         * The role.
         *
         * @var Role $r
         */
        $r = null;

        if(is_numeric($role)) {
            $r = $this->roles->find(function($key, $value) use($role) {
                return $value->id == $role;
            });
        }

        if(
            is_string($role) &&
            $r == null
        ) {
            $r = $this->roles->find(function($key, $value) use($role) {
                return $value->title == $role;
            });
        }

        if(
            $r == null &&
            ($role instanceof Role)
        ) {
            $r = $this->roles->find(function($key, $value) use($role) {
                return ($value instanceof $role);
            });
        }

        return $r;
    }
}
