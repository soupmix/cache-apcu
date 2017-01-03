<?php

namespace Soupmix\Cache;

use Soupmix\Cache\Exceptions\InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

class APCUCache implements CacheInterface
{
    const PSR16_RESERVED_CHARACTERS = ['{','}','(',')','/','@',':'];

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
    public function set($key, $value, $ttl = null)
    {
        $this->checkReservedCharacters($key);
        return apcu_store($key, $value, (int) $ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $this->checkReservedCharacters($key);
        return (bool) apcu_delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        return apcu_clear_cache();
    }
    /**
     * {@inheritDoc}
     */
    public function getMultiple($keys, $default = null)
    {
        $defaults = array_fill(0, count($keys), $default);
        foreach ($keys as $key){
            $this->checkReservedCharacters($key);
        }
        return array_merge(apcu_fetch($keys), $defaults);
    }

    /**
     * {@inheritDoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value ){
            $this->checkReservedCharacters($key);
        }
        $result =  apcu_store($values, null , $ttl);
        return empty($result);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMultiple($keys)
    {
        $ret = [];
        foreach ($keys as $key ){
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
    public function has($key) {
        $this->checkReservedCharacters($key);
        return apcu_exists($key);
    }

    private function checkReservedCharacters($key)
    {
        foreach (self::PSR16_RESERVED_CHARACTERS as $needle) {
            if (strpos($key, $needle) !== false) {
                $message = sprintf('%s string is not a legal value.', $key);
                throw new InvalidArgumentException($message);
            }
        }
    }
}