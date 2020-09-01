<?php declare(strict_types=1);

namespace Source\Controllers;

use Source\Controllers\BaseController;
use CoffeeCode\Uploader\Image;
use Source\Models\User;
use Source\Libs\Utils;
use Exception;

class UsersController extends BaseController
{
    public function __construct()
    {
        $params = ['id_groups','name', 'email','avatar','birthday', 'password'];
        parent::__construct(new User(), $params);
    }

    public function filterPost($params): array
    {
        if ($this->validateEmail($params['email']) && $this->validatePassword($params['password'])){
            if ($params['avatar'] && $this->validateAvatar($params['avatar'])) {
                $params['avatar'] = $this->avatar;
            }
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            $params['name'] = filter_var($params['name'], FILTER_SANITIZE_STRING);
            $params['birthday'] = filter_var($params['birthday'], FILTER_SANITIZE_STRING);
            $params['id_groups'] = filter_var((int)$params['id_groups'], FILTER_VALIDATE_INT);
            $params['token'] = md5(time() . rand(0, 9999) . time());

            return [$params, true];

        } else {
            return [$this->fail, false];
        }
    }

    public function filterUpdate($params): array
    {
        if ($this->validateEmail($params['email']) && $this->validateAvatar($params['avatar'])){
            $params['name'] = filter_var($params['name'], FILTER_SANITIZE_STRING);
            $params['birthday'] = filter_var($params['birthday'], FILTER_SANITIZE_STRING);
            $params['id_groups'] = filter_var((int)$params['id_groups'], FILTER_VALIDATE_INT);
            $params['avatar'] = $this->avatar;

            return [$params, false];
        } else {
            return [$this->fail, false];
        }
    }

    public function validateEmail(string $email): bool
    {
        if (empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $this->fail = new Exception("Informe um e-mail válido!");
            return false;

        } elseif ((Utils::emailExists(new User(), $email))){
            $this->fail = new Exception("O e-mail informado já está em uso!");
            return  false;
        } else {
            return true;
        }
    }

    public function validatePassword(string $password): bool
    {
        if (empty($password)|| strlen($password) < 5) {
            $this->fail = new Exception("Informe uma senha com pelo menos 5 caracteres");
            return false;
        }

        return true;
    }

    public function validateAvatar($avatar): bool
    {
        $upload = new Image("source/storage", "avatar");
        if (empty($avatar["type"]) || !in_array($avatar["type"], $upload::isAllowed())) {
            $this->fail = new Exception("Selecione uma imagem válida");
            return false;
        } else {
            $this->avatar = $upload->upload($avatar, pathinfo($avatar["name"], PATHINFO_FILENAME), 350);
            return true;
        }
    }
}
