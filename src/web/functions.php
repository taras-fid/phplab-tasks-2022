<?php
/**
 * The $airports variable contains array of arrays of airports (see airports.php)
 * What can be put instead of placeholder so that function returns the unique first letter of each airport name
 * in alphabetical order
 *
 * Create a PhpUnit test (GetUniqueFirstLettersTest) which will check this behavior
 *
 * @param  array  $airports
 * @return string[]
 */
function getUniqueFirstLetters(array $airports): array
{

    $lettersArr = [];
    $resArr = [];

    foreach ($airports as $airport) {
        $lettersArr[] = mb_substr($airport['name'], 0, 1);
    }

    $resArr = array_unique($lettersArr);
    sort($resArr);

    return $resArr;
}