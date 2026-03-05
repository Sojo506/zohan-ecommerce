<?php

class Security
{
    public static function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public static function generateOtp(int $length = 6): string
    {
        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;
        return (string) random_int($min, $max);
    }
}
