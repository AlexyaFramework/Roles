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
 * A permission consists of an identifier, a title and
 * a status flag.
 *
 * You can easily extend this class to provide more functionality such
 * as alternative names, ranks...
 *
 * @property int    $id     Permission ID.
 * @property string $title  Permission title.
 * @property int    $status Permission status.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Permission extends Collection
{
    /////////////////////
    // Start constants //
    /////////////////////
    public const STATUS_INHERITED = 0;
    public const STATUS_DISABLED  = 1;
    public const STATUS_ENABLED   = 2;
    ///////////////////
    // End constants //
    ///////////////////

    /**
     * Constructor.
     *
     * @param int    $id     Permission ID.
     * @param string $title  Permission title.
     * @param int    $status Permission status.
     */
    public function __construct(int $id, string $title, int $status)
    {
        parent::__construct([
                "id"     => $id,
                "title"  => $title,
                "status" => $status
        ]);
    }
}
