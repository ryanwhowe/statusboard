<?php

namespace Tests\Statusboard\Utility;

use Statusboard\Utility\StringUtility;
use PHPUnit\Framework\TestCase;

class StringUtilityTest extends TestCase {

    public static function buildUniqueStringDataProvider() {
        return [
            [['This', 'this', 'is', 'a', 'test'], false, ' ', 'This is a test'],
            [['This', 'this', 'is', 'a', 'test'], true, ' ', 'This this is a test'],
            [['This', 'is', 'is', 'a', 'test'], false, ' ', 'This is a test'],
            [['This', 'is', 'a', 'a', 'test'], false, ' ', 'This is a test'],
            [['This', 'is', 'a', 'test', 'test'], false, ' ', 'This is a test'],
            [['This', 'is', 'a', 'test', 'This'], false, ' ', 'This is a test'],
            [['This', 'this', 'is', 'a', 'test'], false, '-', 'This-is-a-test'],
            [['This', 'this', 'is', 'a', 'test'], true, '-', 'This-this-is-a-test'],
        ];
    }

    /**
     * @test
     * @dataProvider buildUniqueStringDataProvider
     *
     * @param array  $test
     * @param bool   $case_sensitive
     * @param string $delimiter
     * @param string $expected
     */
    public function buildUniqueString(array $test, bool $case_sensitive, string $delimiter, string $expected) {
        $actual = StringUtility::buildUniqueString($test, $case_sensitive, $delimiter);
        $this->assertEquals($expected, $actual);
    }
}
