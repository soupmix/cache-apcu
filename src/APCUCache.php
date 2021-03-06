<?php

namespace Soupmix\Cache;

use Soupmix\Cache\Exceptions\InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use DateInterval;
use DateTime;

use function apcu_fetch;
use function apcu_store;
use function apcu_delete;
use function apcu_clear_cache;
use function apcu_dec;
use function apcu_inc;

class APCUCache implements CacheInterface
{
    private const PSR16_RESERVED_CHARACTERS = ['{','}','(',')','/','@',':'];

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $this->checkReservedCharacters($key);
        $value = apcu_fetch($key);
        return $value ?: $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = null) : bool
    {
        $this->checkReservedCharacters($key);
        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }
        return apcu_store($key, $value, (int) $ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key) : bool
    {
        $this->checkReservedCharacters($key);
        return (bool) apcu_delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function clear() : bool
    {
        return apcu_clear_cache();
    }
    /**
     * {@inheritDoc}
     */
    public function getMultiple($keys, $default = null)
    {
        $defaults = array_fill(0, count($keys), $default);
        foreach ($keys as $key) {
            $this->checkReservedCharacters($key);
        }
        return array_merge(apcu_fetch($keys), $defaults);
    }

    /**
     * {@inheritDoc}
     */
    public function setMultiple($values, $ttl = null) : bool
    {
        foreach ($values as $key => $value) {
            $this->checkReservedCharacters($key);
        }
        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
        }
        $result =  apcu_store($values, null, $ttl);
        return empty($result);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMultiple($keys) : array
    {
        $ret = [];
        foreach ($keys as $key) {
            $this->checkReservedCharacters($key);
            $ret[$key] = apcu_delete($key);
        }
        return $ret;
    }

    public function increment($key, $step = 1)
    {
        $this->checkReservedCharacters($key);
        return apcu_inc($key, $step);
    }

    public function decrement($key, $step = 1)
    {
        $this->checkReservedCharacters($key);
        return apcu_dec($key, $step);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        $this->checkReservedCharacters($key);
        return apcu_exists($key);
    }

    private function checkReservedCharacters($key) : void
    {
        if (!is_string($key)) {
            $message = sprintf('key %s is not a string.', $key);
            throw new InvalidArgumentException($message);
        }
        foreach (self::PSR16_RESERVED_CHARACTERS as $needle) {
            if (strpos($key, $needle) !== false) {
                $message = sprintf('%s string is not a legal value.', $key);
                throw new InvalidArgumentException($message);
            }
        }
    }
}
