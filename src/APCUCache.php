<?php

namespace Soupmix\Cache;


class APCUCache implements CacheInterface
{



    /**
     * Fetch a value from the cache.
     *
     * @param string $key The unique key of this item in the cache
     *
     * @return mixed The value of the item from the cache, or null in case of cache miss
     */
    public function get($key)
    {
        $value = apcu_fetch($key);
        return ($value) ? $value : null;
    }
    /**
     * Persist data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key The key of the item to store
     * @param mixed $value The value of the item to store
     * @param null|integer|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and the driver supports TTL
     *                                       then the library may set a default value for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure
     */
    public function set($key, $value, $ttl = null){
        return apcu_store($key, $value, intval($ttl));
    }
    /**
     * Delete an item from the cache by its unique key
     *
     * @param string $key The unique cache key of the item to delete
     *
     * @return bool True on success and false on failure
     */
    public function delete($key){
        return (bool) apcu_delete($key);
    }
    /**
     * Wipe clean the entire cache's keys
     *
     * @return bool True on success and false on failure
     */
    public function clear(){
        return apcu_clear_cache();
    }
    /**
     * Obtain multiple cache items by their unique keys
     *
     * @param array|Traversable $keys A list of keys that can obtained in a single operation.
     *
     * @return array An array of key => value pairs. Cache keys that do not exist or are stale will have a value of null.
     */
    public function getMultiple($keys)
    {
        $ret = [];
        foreach ($keys as $key){
            $ret[$key] = apcu_fetch($key);
        }
        return $ret;
    }
    /**
     * Persisting a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array|Traversable         $items An array of key => value pairs for a multiple-set operation.
     * @param null|integer|DateInterval $ttl   Optional. The amount of seconds from the current time that the item will exist in the cache for.
     *                                         If this is null then the cache backend will fall back to its own default behaviour.
     *
     * @return bool True on success and false on failure
     */
    public function setMultiple($items, $ttl = null)
    {
        $ret = true;
        foreach ($items as $key => $value){
            $ret = $ret && $this->set($key, $value, $ttl);
        }
        return $ret;
    }
    /**
     * Delete multiple cache items in a single operation
     *
     * @param array|Traversable $keys The array of string-based keys to be deleted
     *
     * @return bool True on success and false on failure
     */
    public function deleteMultiple($keys)
    {
        $ret = [];
        foreach ($keys as $key ){
            $ret[$key] = apcu_delete($key);
        }
        return $ret;
    }
    /**
     * Increment a value atomically in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to increment by, defaulting to 1
     *
     * @return int|bool The new value on success and false on failure
     */
    public function increment($key, $step = 1)
    {
        return apcu_inc($key, $step);
    }
    /**
     * Decrement a value atomically in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to decrement by, defaulting to 1
     *
     * @return int|bool The new value on success and false on failure
     */
    public function decrement($key, $step = 1)
    {
        return apcu_dec($key, $step);
    }
}