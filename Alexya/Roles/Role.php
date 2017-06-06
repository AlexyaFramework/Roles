<?php
namespace Alexya\Roles;

use Alexya\Tools\Collection;

/**
 * Role class.
 * ===========
 *
 * This class represents a role by itself.
 *
 * It has an identifier for the role, a title
 * and an array of `Permission` with the granted
 * permissions for this role.
 * Alternatively, a role can have a parent role for inheritance.
 *
 * The method `hasPermission` accepts an identifier of
 * a permission or an instance of the permission and
 * checks that this role has been granted with the permission.
 * For a shorter version, you can use `can`, 'cuz shorter > longer.
 *
 * @property int        $id          Role identifier.
 * @property string     $title       Role title.
 * @property int        $priority    Role priority.
 * @property Collection $permissions Granted permissions.
 * @property Role       $parent      Parent role.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Role extends Collection
{
    /**
     * Constructor.
     *
     * @param int          $id          Role identifier.
     * @param string       $title       Role title.
     * @param int          $priority    Role priority.
     * @param Permission[] $permissions Granted permissions.
     * @param Role         $parent      Parent role.
     */
    public function __construct(int $id, string $title, int $priority = 0, array $permissions = [], Role $parent = null)
    {
        parent::__construct([
                "id"          => $id,
                "title"       => $title,
                "priority"    => $priority,
                "permissions" => new Collection($permissions),
                "parent"      => $parent
        ]);
    }

    /**
     * Checks that this role has has granted specified permission.
     *
     * @param mixed $permission Permission to check.
     *
     * @return bool Whether this role has `$permission` granted or not.
     */
    public function hasPermission($permission) : bool
    {
        /**
         * The permission instance.
         *
         * @var Permission $permission
         */
        $permission = $this->getPermission($permission);

        if(
            $permission != null &&
            $permission->status != Permission::STATUS_INHERITED
        ) {
            return ($permission->status == Permission::STATUS_ENABLED);
        }

        if($this->parent != null) {
            return $this->parent->hasPermission($permission);
        }

        return false;
    }

    /**
     * Checks that this role has has granted specified permission.
     *
     * @param mixed $permission Permission to check.
     *
     * @return bool Whether this role has `$permission` granted or not.
     */
    public function can($permission) : bool
    {
        return $this->hasPermission($permission);
    }

    /**
     * Adds a new permission to the role.
     *
     * @param Permission $permission Permission to add to the role.
     */
    public function addPermission(Permission $permission) : void
    {
        $this->permissions->set($permission->title, $permission);
    }

    /**
     * Removes a permission from the role.
     *
     * @param mixed $permission Permission to remove.
     */
    public function removePermission($permission) : void
    {
        $permission = $this->getPermission($permission);

        $this->permissions = $this->permissions->filter(function($key, $value) use($permission) {
            return ($value == $permission);
        });
    }

    /**
     * Returns specified permission.
     *
     * @param mixed $permission Permission to retrieve.
     *
     * @return Permission|null Permission for `$permission`.
     */
    public function getPermission($permission) : ?Permission
    {
        /**
         * The permission.
         *
         * @var Permission $p
         */
        $p = null;

        if(is_numeric($permission)) {
            $p = $this->permissions->find(function($key, $perm) use($permission) {
                return $perm->id == $permission;
            });
        }

        if(
            is_string($permission) &&
            $p == null
        ) {
            $p = $this->permissions->find(function($key, $perm) use($permission) {
                return $perm->title == $permission;
            });
        }

        if(
            $p == null &&
            ($permission instanceof Permission)
        ) {
            $p = $this->permissions->find(function($key, $perm) use($permission) {
                return ($perm instanceof $permission);
            });
        }

        if(
            $p == null &&
            $this->parent != null
        ) {
            $p = $this->parent->getPermission($permission);
        }

        return $p;
    }
}
