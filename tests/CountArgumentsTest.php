<?php

use PHPUnit\Framework\TestCase;

class CountArgumentsTest extends TestCase
{
    protected \functions\Functions $functions;

    protected function setUp(): void
    {
        $this->functions = new functions\Functions();
    }

    /**
     * @dataProvider positiveDataProviderEmpty
     */
    public function testPositiveEmpty($expected)
    {
        $this->assertEquals($expected, $this->functions->countArguments());
    }

    public function positiveDataProviderEmpty(): array
    {
        return [
            [
                [
                    'argument_count' => 0,
                    'argument_values' => Array (),
                ]
            ],
        ];
    }

    /**
     * @dataProvider positiveDataProviderArg
     */
    public function testPositiveArg($arg, $expected)
    {
        $this->assertEquals($expected, $this->functions->countArguments($arg));
    }

    public function positiveDataProviderArg(): array
    {
        return [
            [
                'str',
                [
                    'argument_count' => 1,
                    'argument_values' => Array (0 => 'str'),
                ]
            ],
        ];
    }

    /**
     * @dataProvider positiveDataProviderArgs
     */
    public function testPositiveArgs($arg1, $arg2, $arg3, $expected)
    {
        $this->assertEquals($expected, $this->functions->countArguments($arg1, $arg2, $arg3));
    }

    public function positiveDataProviderArgs(): array
    {
        return [
            [
                'str1', 'str2', 'str3',
                [
                    'argument_count' => 3,
                    'argument_values' => Array (0 => 'str1', 1 => 'str2', 2 => 'str3'),
                ]
            ],
        ];
    }
}
