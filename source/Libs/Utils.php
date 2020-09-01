<?php

namespace Source\Libs;

class Utils
{
    public static function emailExists($class,string $email)
    {
        $verify = ($class)->find("email = :email","email={$email}")->count();
        if ($verify) {
            return true;
        } else {
            return false;
        }
    }

    public static function birthday($birthday)
    {
        $birthday = explode('-', $birthday);

        if (count($birthday) != 3) {
            return '<div>Data de Nascimento inválida!</div>';
        }

        $birthday = $birthday[2] . '/' . $birthday[1] . '/' . $birthday[0];

        if (strtotime($birthday) === false) {
            return '<div>Data de Nascimento inválida!</div>';

        }
        return $birthday;
    }
}