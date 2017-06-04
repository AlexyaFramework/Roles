<?php
namespace Alexya\Roles;

use Alexya\Tools\Collection;

/**
 * Role class.
 * ===========
 *
 * This class represents a role by itself.
 *
 * It has an identifier for the role, a description
 * and an array of `Permission` with the granted
 * permissions for this role.
 * Alternatively, a role can have a parent role for inheritance.
 *
 * The method `hasPermission` accepts an identifier of
 * a permission or an instance of the permission and
 * checks that this role has been granted with the permission.
 * For a shorter version, you can use `can`, 'cuz shorter > longer.
 *
 * @property int          $id          Role identifier.
 * @property string       $description Role description.
 * @property Permission[] $permissions Granted permissions.
 * @property Role         $parent      Parent role.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Role extends Collection
{
    /**
     * Constructor.
     *
     * @param int          $id          Role identifier.
     * @param string       $description Role description.
     * @param Permission[] $permissions Granted permissions.
     * @param Role         $parent      Parent role.
     */
    public function __construct(int $id, string $description, array $permissions, Role $parent = null)
    {
        parent::__construct([
            "id"          => $id,
            "description" => $description,
            "permissions" => $permissions,
            "parent"      => $parent
        ]);
    }
}
