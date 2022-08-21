<?php

use PHPUnit\Framework\TestCase;

class CountArgumentsWrapperTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    public function testNegative()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->functions->countArgumentsWrapper(1,2,3);
    }
}
