<?php
declare(strict_types=1);

namespace Paket\Fram\Examples\Common\Util;

use LogicException;

final class CsrfTokenService
{
    /** @var Database */
    private $database;
    /** @var string */
    private $secret;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function generate(): string
    {
        $secret = $this->getSecret();
        $now = date('c');
        $hash = hash_hmac('sha1', $now, $secret);
        if ($hash === false) {
            throw new LogicException('hash_hmac failed');
        }

        $str = "{$hash};{$now}";
        return base64_encode($str);
    }

    public function validate(string $token, ?string &$error): bool
    {
        if (empty($token)) {
            $error = 'Csrf token missing';
            return false;
        }

        $secret = $this->getSecret();
        $decoded = base64_decode($token);
        if ($decoded === false) {
            $error = 'Csrf token invalid';
            return false;
        }

        $parts = explode(';', $decoded, 2);
        if (count($parts) < 2) {
            $error = 'Csrf token invalid';
            return false;
        }

        $hash = $parts[0];
        $date = $parts[1];

        $control = hash_hmac('sha1', $date, $secret);
        if (!hash_equals($control, $hash)) {
            $error = 'Csrf token tampered with';
            return false;
        }

        $time = strtotime($date);
        if ($time === false) {
            $error = 'Csrf token invalid';
            return false;
        }

        if (time() > $time + (24 * 60 * 60)) {
            $error = 'Csrf token expired';
            return false;
        }
        return  true;
    }

    private function getSecret(): string
    {
        if (isset($this->secret)) {
            return $this->secret;
        }

        $sql = "SELECT value FROM setting WHERE key = ?";
        $stmt = $this->database->execute($sql, ['secret']);
        $secret = $stmt->fetchColumn();
        if ($secret === false) {
            throw new LogicException('secret missing from setting');
        }
        return $this->secret = $secret;
    }
}