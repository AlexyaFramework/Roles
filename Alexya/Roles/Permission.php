<?php
namespace Alexya\Roles;

use Alexya\Tools\Collection;

/**
 * Permission class.
 * =================
 *
 * This class represents a permission that can be assigned
 * to a specific role.
 *
 * A permission consists of an identifier and a description.
 *
 * You can easily extend this class to provide more functionality such
 * as alternative names, ranks...
 *
 * @property int    $id          Permission ID.
 * @property string $description Permission description.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Permission extends Collection
{
    /**
     * Constructor.
     *
     * @param int    $id          Permission ID.
     * @param string $description Permission description.
     */
    public function __construct(int $id, string $description)
    {
        parent::__construct([
            "id"          => $id,
            "description" => $description
        ]);
    }
}
