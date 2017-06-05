Roles
=====

Alexya's Role Based Access System utilities.

Contents
--------
 - [Creating permissions](#creating_permissions)
 - [Creating roles](#creating_roles)
 - [Adding permissions to roles](#adding_permissions_to_roles)
 - [Creating users](#creating_users)
 
<a name="creating_permissions"></a>
Creating permissions
--------------------
Permissions are the way to authorized users to perform certain actions.

The class `\Alexya\Roles\Permission` represents a permission that can be assigned to a specific role.

A permission consists of an identifier, a title and a status flag.
   
You can easily extend this class to provide more functionality such as alternative names, ranks...

For example, in a file system, each user should have permissions to read/write to certain files:

```php
<?php
namespace Application\RBAC\Permissions;

use Alexya\Roles\Permission;

class Read extends Permission
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(1, "read", Permission::STATUS_INHERITED);
    }
}

class Write extends Permission
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(2, "write", Permission::STATUS_INHERITED);
    }
}
```

<a name="creating_roles"></a>
Creating roles
--------------

Roles are the containers of various permissions.

The class `\Alexya\Roles\Role` represents a role by itself.

It has an identifier for the role, a title and an array of `Permission` with the granted permissions for this role.
Alternatively, a role can have a parent role for inheritance.

It also has an integer representing the priority of the role, the bigger the number, the most priority it has.

The method `hasPermission` accepts an identifier of a permission or an instance of the permission and checks that this role has been granted with the permission.

For a shorter version, you can use `can`, 'cuz shorter > longer.

Example:

```php
namespace Application\RBAC\Roles;

use Alexya\Roles\Role;

class CanRead extends Role
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(1, "can_read", 1);
    }
}

class CanWrite extends Role
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(2, "can_write", 1);
    }
}

class CanReadAndWrite extends Role
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(3, "can_read_and_write", 2);
    }
}
```

<a name="adding_permissions_to_roles"></a>
Adding permissions to roles
---------------------------

Once you have the permissions and roles you need to specify which roles have granted which permissions.

A permission can be enabled, disabled or inherited. If a permission is inherited the role will check the status
of that permission in the parent role, if there's no parent role it will be treated as disabled.

Adding permissions to roles is as easy as calling the `addPermission` method with the permission to add.

Example:
```php
$canRead = new CanRead();
$canRead->addPermission(new Read());

$canWrite = new CanWrite();
$canWrite->addPermission(new Write());

$canReadAndWrite = new CanReadAndWrite();
$canReadAndWrite->addPermission(new Read());
$canReadAndWrite->addPermission(new Write());

// Alternatively you could have set the parent role like this:
// $canReadAndWrite = new CanReadAndWrite();
// $canReadAndWrite->parent = $canRead;
// $canReadAndWrite->addPermission(new Write());
```

To check if a role has granted a certain permission you can use the `hasPermission` or `can` methods:

```php
$canRead->can("read"); // true
$canRead->can(2); // false (Write permission has ID 2)
$canRead->can(new Read()); // true
```

The method `getPermission` returns a permission from the role:

```php
$read  = $canRead->getPermission(new Read()); // The Read permission sent through `addPermission`
$write = $canRead->getPermission("write");    // null;
```

<a name="creating_users"></a>
Creating users
--------------

Now that we have the roles and permissions we need users to assign them.

The class `\Alexya\Roles\User` represents an user, where the roles are assigned.

It's the class that you should extend in order to add roles to your users as it provides the `hasPermission` and `can` methods for checking if this user has any permission granted.

It also has the methods `addRole` and `removeRole` to add/remove roles.

Example:

```php
$user = new User();

$user->addRole(2);

$user->can(new Read());  // false
$user->can(new Write()); // true

$user->addRole(new CanRead());

$user->can("read");  // true
$user->can(new Write()); // true

$user->getRole("can_write")
     ->getPermission("write")
     ->status = Permission::STATUS_DISABLED;

$user->can(new Read());  // true
$user->can(new Write()); // false

$user->addRole(new CanReadAndWrite());

$user->can(new Read());  // true
$user->can(new Write()); // true because the priority of `CanReadAndWrite` is bigger than the `CanWrite` priority.
```
