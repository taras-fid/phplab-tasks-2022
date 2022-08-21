<?php

use PHPUnit\Framework\TestCase;

class SayHelloTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider positiveDataProvider
     */
    public function testPositive($expected)
    {
        $this->assertEquals($expected, $this->functions->sayHello());
    }

    public function positiveDataProvider(): array
    {
        return [['Hello']];
    }
}
