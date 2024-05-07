<?php

namespace App\Util;

class RandomPasswordGenerator
{
    public function generate(int $length = 10): string
    {
        // Caractères autorisés pour le mot de passe
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';

        // Générer le mot de passe aléatoire
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $password;
    }
}