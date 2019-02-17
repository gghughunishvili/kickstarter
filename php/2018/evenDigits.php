<?php

function readFromSDTIn(&$testCases, &$numbers)
{
    $f = fopen('php://stdin', 'r');
    $testCases = fgets($f);
    for ($i = 0; $i < $testCases; $i++) {
        $numbers[] = trim(fgets($f));
    }
    fclose($f);
}

function writeAnswersInFile($answers)
{
    $outString = '';
    foreach ($answers as $key => $item) {
        $index = $key + 1;
        $outString .= "Case #{$index}: {$item}\n";
    }
    fwrite(STDOUT, rtrim($outString, '\n'));
}

///////////////////////////////////////////////////////

$testCases = 0;
$numbers = [];
readFromSDTIn($testCases, $numbers);
$answers = [];
foreach ($numbers as $number) {
    $answers[] = getAnswer($number);
}
writeAnswersInFile($answers);

function getAnswer($number)
{
    if ($number < 10) {
        return $number % 2 == 0 ? 0 : 1;
    }

    $numArray = transformNumToArray($number);
    foreach ($numArray as $i => $n) {
        if ($n % 2 == 1) {
            // Second outcome lower bound
            $lower = getLowerBoundAnswer($numArray, $i, $n, $number);
            if ($n == 9) {
                return $lower;
            }
            // First outcome upper bound
            $upper = getUpperBoundAnswer($numArray, $i, $n, $number);

            return $upper < $lower ? $upper : $lower;
        }
    }

    return 0;
}

function transformNumToArray($n) {
    $arr = str_split($n);
    return $arr;
}

function getUpperBoundAnswer($numArray, $i, $n, $number) {
    $arrLen = count($numArray);
    // slice from left side
    $firstPart = '';
    if ($i !== 0) {
        $sl = array_slice($numArray, 0, $i);
        $firstPart = join('', $sl);
    }
    $upperNum = intval($firstPart . (intval($n) + 1) . str_repeat('0', $arrLen - $i - 1));
    return $upperNum - $number;
}

function getLowerBoundAnswer($numArray, $i, $n, $number) {
    $arrLen = count($numArray);
    // slice from left side
    $firstPart = '';
    if ($i !== 0) {
        $sl = array_slice($numArray, 0, $i);
        $firstPart = join('', $sl);
    }
    $lowerNum = intval($firstPart . (intval($n) - 1) . str_repeat('8', $arrLen - $i - 1));
    return $number - $lowerNum;
}
