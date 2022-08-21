<?php


use PHPUnit\Framework\TestCase;

class GetUniqueFirstLettersTest extends TestCase
{

    /**
     * @dataProvider positiveDataProvider
     */
    public function testPositive($input, $expected)
    {
        include_once 'src/web/functions.php';
        $this->assertEquals($expected, getUniqueFirstLetters($input));
    }

    public function positiveDataProvider(): array
    {
        return [
            [[], []],
            [[
                [
                    'name' => 'Albuquerque Sunport International Airport',
                    'name' => 'albuquerque Sunport International Airport',
                    'NAme' => 'albuquerque Sunport International Airport'
                ]
            ], ['a']],
            [[
                [
                    'name' => '123',
                ],
                [
                    'name' => '234'
                ],
                [
                    'name' => '1234'
                ]
            ], ['1','2']],
            [[
                [
                    'name' => 'Тарас',
                ],
                [
                    'name' => 'тарас'
                ],
                [
                    'name' => 'Їжак'
                ]
            ], ['Т', 'т', 'Ї']],
        ];
    }
}
