<?php
namespace Soupmix\Cache\Tests;

use Psr\SimpleCache\InvalidArgumentException;
use Soupmix;
use PHPUnit\Framework\TestCase;
use DateInterval;
use Soupmix\Cache\APCUCache;
use Psr\SimpleCache\CacheInterface;

class APCUCacheTest extends TestCase
{
    /**
     * @var $client CacheInterface
     */
    protected $client;

    protected function setUp() : void
    {
        $this->client = new APCUCache();
        $this->client->clear();
    }

    /**
     * @test
     */
    public function shouldSetGetAndDeleteAnItemSuccessfully() : void
    {
        $ins1 = $this->client->set('test1', 'value1', new DateInterval('PT60S'));
        $this->assertTrue($ins1);
        $value1 = $this->client->get('test1');
        $this->assertEquals('value1', $value1);
        $delete = $this->client->delete('test1');
        $this->assertTrue($delete);
    }

    public function testSetGetAndDeleteMultipleItems() : void
    {
        $cacheData = [
            'test1' => 'value1',
            'test2' => 'value2',
            'test3' => 'value3',
            'test4' => 'value4'
        ];
        $insMulti = $this->client->setMultiple($cacheData, new DateInterval('PT60S'));
        $this->assertTrue($insMulti);

        $getMulti = $this->client->getMultiple(array_keys($cacheData));

        foreach ($cacheData as $key => $value) {
            $this->assertArrayHasKey($key, $getMulti);
            $this->assertEquals($value, $getMulti[$key]);
        }
        $deleteMulti = $this->client->deleteMultiple(array_keys($cacheData));

        foreach ($cacheData as $key => $value) {
            $this->assertTrue($deleteMulti[$key]);
        }
    }

    public function testIncrementAndDecrementACounterItem() : void
    {
        $this->client->set('counter', 0);
        $counter_i_1 = $this->client->increment('counter', 1);
        $this->assertEquals(1, $counter_i_1);
        $counter_i_3 = $this->client->increment('counter', 2);
        $this->assertEquals(3, $counter_i_3);
        $counter_i_4 = $this->client->increment('counter');
        $this->assertEquals(4, $counter_i_4);
        $counter_d_3 = $this->client->decrement('counter');
        $this->assertEquals(3, $counter_d_3);
        $counter_d_1 = $this->client->decrement('counter', 2);
        $this->assertEquals(1, $counter_d_1);
        $counter_d_0 = $this->client->decrement('counter', 1);
        $this->assertEquals(0, $counter_d_0);
    }

    public function testHasItem() : void
    {
        $has = $this->client->has('has');
        $this->assertFalse($has);
        $this->client->set('has', 'value');
        $has = $this->client->has('has');
        $this->assertTrue($has);
    }

    /**
     * @test
     */
    public function failForReservedCharactersInKeyNames() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->set('@key', 'value');
    }

    /**
     * @test
     */
    public function failForInvalidStringInKeyNames() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->set(1, 'value');
    }

    public function testClear() : void
    {
        $clear = $this->client->clear();
        $this->assertTrue($clear);
    }
}
