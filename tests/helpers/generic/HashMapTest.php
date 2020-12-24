<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers\generic;

use taobig\helpers\generic\HashMap;
use taobig\helpers\generic\exception\KeyTypeErrorException;
use TestCase;

class HashMapTest extends TestCase
{

    public function testHashMap()
    {
        $hashMap = new HashMap();
        $hashMap->put("1", 1);//注意：PHP内部数组在存储时会把"1"存储为int的1
        $hashMap->put("2", 2);
        $hashMap->put(22, 22);
        $hashMap->put("3", 3);
        $hashMap->put("a", "a");
        $hashMap->put("b", "b");

        $this->assertSame(1, $hashMap->get(1));
        $this->assertSame(1, $hashMap->get("1"));
        $this->assertSame(22, $hashMap->get("22"));
        $this->assertSame(22, $hashMap->get(22));
        $this->assertSame(null, $hashMap->get(2222222));

        $oldValue = $hashMap->remove("2");
        $this->assertSame(2, $oldValue);
        $oldValue = $hashMap->remove("2");
        $this->assertSame(null, $oldValue);

        $this->assertSame(5, $hashMap->size());
        $this->assertSame(true, $hashMap->containsKey(1));
        $this->assertSame(true, $hashMap->containsKey("1"));
        $this->assertSame(false, $hashMap->containsKey("aaaaa"));
        $this->assertSame(true, $hashMap->containsValue(1));
        $this->assertSame(true, $hashMap->containsValue("a"));
        $this->assertSame(false, $hashMap->containsValue("aaaaaaa"));
        $this->assertSame([1, 22, 3, "a", "b"], $hashMap->values());
        $this->assertSame(true, $hashMap->isNotEmpty());
        $this->assertSame('{"1":1,"22":22,"3":3,"a":"a","b":"b"}', $hashMap->toJson());

        $hashMap2 = new HashMap();
        $hashMap2->put("0", 0);
        $hashMap2->put("1", 1111);
        $hashMap2->put("2", 2222);
        $this->assertSame(3, $hashMap2->size());
        $this->assertSame('{"0":0,"1":1111,"2":2222}', $hashMap2->toJson());
        $hashMap2->putAll($hashMap);
        $this->assertSame('{"0":0,"1":1,"2":2222,"22":22,"3":3,"a":"a","b":"b"}', $hashMap2->toJson());
        $hashMap2->removeAll();
        $this->assertSame(0, $hashMap2->size());
        $this->assertSame(true, $hashMap2->isEmpty());
        $this->assertSame('null', $hashMap2->toJson());

        $this->expectException(KeyTypeErrorException::class);
        $hashMap->put(true, "true");

    }


}