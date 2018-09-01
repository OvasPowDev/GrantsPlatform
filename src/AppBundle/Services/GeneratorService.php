<?php

namespace AppBundle\Services;

class GeneratorService
{
    public function password($length = 8){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function validatePassword($password ){

        if (strlen($password) < 8) {
            $errors = "Password too short!";
            return [false, $errors];
        }

        if (!preg_match("#[0-9]+#", $password)) {
            $errors= "Password must include at least one number!";
            return [false, $errors];
        }

        if (!preg_match("#[a-zA-Z]+#", $password)) {
            $errors = "Password must include at least one letter!";
            return [false, $errors];
        }

        return [true, null];
    }
}