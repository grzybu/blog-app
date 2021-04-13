<?php

namespace App\Libs;

class SessionManager extends \SessionHandler
{
    protected string $name;
    protected array $cookie;

    public function __construct(string $name = 'MY_SESSION')
    {
        $this->name = $name;
        $this->cookie = [
            'lifetime' => 0,
            'path' => ini_get('session.cookie_path'),
            'domain' => ini_get('session.cookie_domain'),
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true
        ];

        $this->setup();
    }

    private function setup(): void
    {
        if ($this->hasStarted()) {
            return;
        }
        ini_set('session.use_cookies', 'true');
        ini_set('session.use_only_cookies', 'true');
        session_name($this->name);
        session_set_cookie_params(
            $this->cookie['lifetime'],
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
    }

    protected function hasStarted(): bool
    {
        return session_id() !== '';
    }

    public function start(): bool
    {
        if (session_id() === '') {
            return session_start();
        }
        return false;
    }

    public function forget(): bool
    {
        if (session_id() === '') {
            return false;
        }
        $_SESSION = [];
        setcookie(
            $this->name,
            '',
            time() - 42000,
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
        return session_destroy();
    }

    public function refresh(): bool
    {
        return session_regenerate_id(true);
    }

    public function isExpired(int $ttl = 30): bool
    {
        $last = $_SESSION['_last_activity'] ?? false;
        if ($last !== false && time() - $last > $ttl * 60) {
            return true;
        }
        $_SESSION['_last_activity'] = time();
        return false;
    }

    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    public function getSessionId(): ?string
    {
        return session_id();
    }

    /**
     * @param string $name
     * @return array|mixed|null
     */
    public function get(string $name)
    {
        $parsed = explode('.', $name);
        $result = $_SESSION;
        while ($parsed) {
            $next = array_shift($parsed);
            if (isset($result[$next])) {
                $result = $result[$next];
            } else {
                return null;
            }
        }
        return $result;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function put(string $name, $value): void
    {
        if ('' === session_id()) {
            $this->start();
        }

        $parsed = explode('.', $name);
        $session =& $_SESSION;
        $count = count($parsed);
        while ($count > 1) {
            $next = array_shift($parsed);
            $count = count($parsed);
            if (!isset($session[$next]) || !is_array($session[$next])) {
                $session[$next] = [];
            }
            $session =& $session[$next];
        }
        $session[array_shift($parsed)] = $value;
    }
}
