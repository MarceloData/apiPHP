<?php

namespace Source\Models;

use Source\Models\Base;

class User extends Base
{
    public function __construct()
    {
        $params = ['id_groups','name', 'email','birthday', 'password', 'token'];
        parent::__construct('users', $params ?? []);
    }
}