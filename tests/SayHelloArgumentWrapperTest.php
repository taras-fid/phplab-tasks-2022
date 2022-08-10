<?php

use PHPUnit\Framework\TestCase;

class SayHelloArgumentWrapperTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    public function testNegative()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->functions->SayHelloArgumentWrapper([]);
    }
}
