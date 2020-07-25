<?php

namespace taobig\tests\helpers\generic;

use taobig\helpers\generic\ArrayList;
use taobig\helpers\generic\exception\OffsetTypeErrorException;
use taobig\helpers\generic\exception\IndexOutOfBoundsException;
use taobig\helpers\generic\exception\UnsupportedOperationException;
use TestCase;

class ArrayListTest extends TestCase
{

    public function testArrayList()
    {
        $list = new ArrayList();
        $addList1 = new ArrayList(['a' => 1, 2, 3]);
        $addList2 = new ArrayList([4, 'hello']);
        $list->addList($addList1)->addList($addList2);
        $this->assertSame($addList1->count() + $addList2->count(), $list->count());
        $this->assertSame([1, 2, 3, 4, 'hello'], $list->toArray());
        $this->assertSame(1, $list->first());
        $this->assertSame('hello', $list->last());
        $this->assertSame(true, $list->contains('hello'));
        $this->assertSame(4, $list->indexOf('hello'));
        $this->assertSame(3, $list[2]);
        $this->assertSame(true, isset($list[2]));
        $this->assertSame(false, isset($list[222222]));
        $this->assertSame(5, $list->count());
        $this->assertSame(false, $list->isEmpty());
        $this->assertSame(true, $list->isNotEmpty());
        $arr = [];
        foreach ($list as $item) {
            $arr[] = $item;
        }
        $this->assertSame($arr, $list->toArray());

        $list2 = clone $list;
        $this->assertSame([3], $list2->remove(2, 1)->toArray());
        $list22 = clone $list;
        $this->assertSame([2, 3], $list22->remove(1, 2)->toArray());
        $list23 = clone $list;
        $this->assertSame(false, $list23->removeElement("hellooo"));
        $this->assertSame(true, $list23->removeElement(2));
        $this->assertSame([1, 3, 4, 'hello'], $list23->toArray());

        $list4 = clone $list;
        $this->assertSame('aaa', $list4->set(1, 'aaa')->get(1));
        $this->assertSame('aaa', $list4->set(1, 'aaa')->get(1));

        $list5 = new ArrayList(['2', '3', 'a']);
        $this->assertSame('23a', $list5->join());
        $this->assertSame('2,3,a', $list5->join(','));

        $list6 = new ArrayList(['a', 'b',]);
        $list6->add('c');
        $this->assertSame(3, $list6->count());
        $list6->addList(new ArrayList(['d', 'e']));
        $this->assertSame(5, $list6->count());
        $list6->addList(new ArrayList(['a' => 'f', 'g']));
        $this->assertSame(7, $list6->count());
        $this->assertSame('f', $list6[5]);
        $list6->clear();
        $this->assertSame(true, $list6->isEmpty());
        $list6 = new ArrayList(['a', 'b',]);
        $list6->push('c');
        $this->assertSame(['a', 'b', 'c'], $list6->toArray());
        $list6->pop();
        $this->assertSame(['a', 'b'], $list6->toArray());
        $list6->shift();
        $this->assertSame(['b'], $list6->toArray());
        $list6->prepend('a');
        $this->assertSame(['a', 'b'], $list6->toArray());
        $list6->push('a');
        $list6->push('a');
        $this->assertSame(['a', 'b', 'a', 'a'], $list6->toArray());
        $list7 = $list6->unique();
        $this->assertNotSame(['a', 'b', 'a', 'a'], $list7->toArray());
        $this->assertSame(['a', 'b'], $list7->toArray());

        $list8 = new ArrayList(['a', 'b', 1, 2, 3, 4]);
        $newList = $list8->slice(1, 3);
        $this->assertSame(['b', 1, 2], $newList->toArray());
        $this->assertSame(['a', 'b', 1, 2, 3, 4], $list8->toArray());
    }

    public function testArrayListSet1()
    {
        $list = new ArrayList([1, 2]);
        $this->expectException(OffsetTypeErrorException::class);
        $list->set('1', 1);
    }

    public function testArrayListSet2()
    {
        $list = new ArrayList([1, 2]);
        $list[0] = 'aaa';
        $this->assertSame('aaa', $list->first());

        $list->set(0, 'bbb');
        $this->assertNotSame('aaa', $list->first());
        $this->assertSame('bbb', $list->first());

        $this->expectException(IndexOutOfBoundsException::class);
        $list->set(3, 1);
    }

    public function testArrayListUnset()
    {
        $list = new ArrayList([1, 2]);
        $this->expectException(UnsupportedOperationException::class);
        unset($list[0]);
    }


    public function testArrayListGet1()
    {
        $list = new ArrayList([1, 2]);
        $this->expectException(OffsetTypeErrorException::class);
        $list->get('1');
    }

    public function testArrayListGet2()
    {
        $list = new ArrayList([1, 2]);
        $list[0] = 'aaa';
        $this->assertSame('aaa', $list->get(0));

        $this->expectException(IndexOutOfBoundsException::class);
        $list->get(33);
    }

}