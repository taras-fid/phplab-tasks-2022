<?php

use PHPUnit\Framework\TestCase;

class SayHelloArgumentTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider positiveDataProvider
     */
    public function testPositive($arg, $expected)
    {
        $this->assertEquals($expected, $this->functions->sayHelloArgument($arg));
    }

    public function positiveDataProvider(): array
    {
        return [
            [false, 'Hello '],
            [true, 'Hello 1'],
            [0, 'Hello 0'],
            ['$php', 'Hello $php'],
            [1, 'Hello 1']
        ];
    }
}
