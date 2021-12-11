<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use taobig\helpers\RandomHelper;

class RandomHelperTest extends \TestCase
{

    // ASCII
    // 65-90 => A-Z
    // 97–122 => a–z
    public function testRandom()
    {
        $char = (new RandomHelper())->getRandomEnglishCharacters(1);
        $this->assertGreaterThanOrEqual(97, ord($char));
        $this->assertLessThanOrEqual(122, ord($char));

        $char = (new RandomHelper())->getRandomEnglishCharacters(1, RandomHelper::CASE_UPPER);
        $this->assertGreaterThanOrEqual(65, ord($char));
        $this->assertLessThanOrEqual(90, ord($char));


        $count = 5;
        $chars = (new RandomHelper())->getRandomEnglishCharacters($count);
        $this->assertSame($count, strlen($chars));
        foreach (str_split($chars) as $_char) {
            $this->assertGreaterThanOrEqual(97, ord($_char));
            $this->assertLessThanOrEqual(122, ord($_char));
        }

        $chars = (new RandomHelper())->getRandomEnglishCharacters($count, RandomHelper::CASE_UPPER);
        $this->assertSame($count, strlen($chars));
        foreach (str_split($chars) as $_char) {
            $this->assertGreaterThanOrEqual(65, ord($_char));
            $this->assertLessThanOrEqual(90, ord($_char));
        }
    }


    public function testStr()
    {
        $char = RandomHelper::str(1, RandomHelper::LOWER_CASE_ALPHABET);
        $this->assertGreaterThanOrEqual(97, ord($char));
        $this->assertLessThanOrEqual(122, ord($char));

        $char = RandomHelper::str(1, RandomHelper::UPPER_CASE_ALPHABET);
        $this->assertGreaterThanOrEqual(65, ord($char));
        $this->assertLessThanOrEqual(90, ord($char));

        $count = 50;
        $chars = RandomHelper::str($count, RandomHelper::LOWER_CASE_ALPHABET);
        $this->assertSame($count, strlen($chars));
        foreach (str_split($chars) as $_char) {
            $this->assertGreaterThanOrEqual(97, ord($_char));
            $this->assertLessThanOrEqual(122, ord($_char));
        }
        $this->assertMatchesRegularExpression("/^[a-z]{{$count}}$/", $chars);

        $chars = RandomHelper::str($count, RandomHelper::UPPER_CASE_ALPHABET);
        $this->assertSame($count, strlen($chars));
        foreach (str_split($chars) as $_char) {
            $this->assertGreaterThanOrEqual(65, ord($_char));
            $this->assertLessThanOrEqual(90, ord($_char));
        }
        $this->assertMatchesRegularExpression("/^[A-Z]{{$count}}$/", $chars);

        $chars = RandomHelper::str($count, RandomHelper::DIGITS);
        $this->assertSame($count, strlen($chars));
        foreach (str_split($chars) as $_char) {
            $this->assertGreaterThanOrEqual(0, (int)($_char));
            $this->assertLessThanOrEqual(9, (int)($_char));
        }
        $this->assertMatchesRegularExpression("/^[0-9]{{$count}}$/", $chars);

        $chars = RandomHelper::str($count, RandomHelper::LOWER_CASE_ALPHABET | RandomHelper::UPPER_CASE_ALPHABET | RandomHelper::DIGITS);
        $this->assertSame($count, strlen($chars));
        $this->assertMatchesRegularExpression("/^[0-9a-zA-Z]{{$count}}$/", $chars);

        $this->expectException(\InvalidArgumentException::class);
        $chars = RandomHelper::str($count, 0);
    }

}
