<?php
/*
 * Math matrix
 * Demonstrates maximum size of arrays that can have determinant found by the
 * Laplace expansion method, which relies on recursion
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 */

include "../vendor/autoload.php";

use chippyash\Math\Matrix\MatrixFactory;
use chippyash\Math\Matrix\Derivative\Determinant;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Type\Number\IntType;
use chippyash\Type\String\StringType;

function createMatrix($size)
{
    $c = 0;
    $fn = function($r, $c) use(&$c) {
        return RationalTypeFactory::create($c++,1);
    };
    $iSize = new IntType($size);
    return MatrixFactory::createFromFunction($fn, $iSize, $iSize, new StringType('rational'));
}

$size = 1;
//$limit = 256; //powers of 2
$limit = 21; //simple increment
$fDet = new Determinant(Determinant::METHOD_LU);
$startMem = memory_get_peak_usage(false);
while ($size < $limit) {
    echo "det(M[{$size}]) = ";
    $mA = createMatrix($size);
    $startTime = microtime(true);
    $det = $fDet($mA);
    $stopTime = microtime(true);
    $stopMem = memory_get_peak_usage(false);
    $time = $stopTime - $startTime;
    $usedMem = ($stopMem - $startMem)/1024/1024;
    echo "{$det} in {$time} secs using {$usedMem} MiB\n";

//    $size *= 2; //powers of 2
    $size += 1; //simple increment
}

/**
 * Results
 *
 * Powers of 2
det(M[1]) = 0 in 0.0059440135955811 secs using 0.73914337158203 MiB
det(M[2]) = -2 in 0.0045619010925293 secs using 0.75355529785156 MiB
det(M[4]) = 0 in 0.017019033432007 secs using 0.78729248046875 MiB
det(M[8]) = 0 in 0.087040901184082 secs using 0.90128326416016 MiB
det(M[16]) = 0 in 0.56507706642151 secs using 1.6134872436523 MiB
det(M[32]) = 0 in 4.1006209850311 secs using 3.774543762207 MiB
det(M[64]) = 0 in 32.925673961639 secs using 8.570686340332 MiB
det(M[128]) = 0 in 269.43128013611 secs using 31.682861328125 MiB
 *
 * Simple increment to get to about a second
det(M[1]) = 0 in 0.0053620338439941 secs using 0.73895263671875 MiB
det(M[2]) = -2 in 0.0039839744567871 secs using 0.75336456298828 MiB
det(M[3]) = 0 in 0.008842945098877 secs using 0.77567291259766 MiB
det(M[4]) = 0 in 0.016411066055298 secs using 0.80818176269531 MiB
det(M[5]) = 0 in 0.026541948318481 secs using 0.85467529296875 MiB
det(M[6]) = 0 in 0.043558120727539 secs using 0.91778564453125 MiB
det(M[7]) = 0 in 0.063967227935791 secs using 1.1160888671875 MiB
det(M[8]) = 0 in 0.089948892593384 secs using 1.1708068847656 MiB
det(M[9]) = 0 in 0.11775302886963 secs using 1.3099975585938 MiB
det(M[10]) = 0 in 0.15595316886902 secs using 1.6052703857422 MiB
det(M[11]) = 0 in 0.20357704162598 secs using 1.8125457763672 MiB
det(M[12]) = 0 in 0.25411796569824 secs using 2.3078765869141 MiB
det(M[13]) = 0 in 0.33252191543579 secs using 2.5543975830078 MiB
det(M[14]) = 0 in 0.39200901985168 secs using 2.8061981201172 MiB
det(M[15]) = 0 in 0.47928309440613 secs using 3.0908966064453 MiB
det(M[16]) = 0 in 0.57221293449402 secs using 3.8670501708984 MiB
det(M[17]) = 0 in 0.6955578327179 secs using 3.8670501708984 MiB
det(M[18]) = 0 in 0.80566906929016 secs using 3.8670501708984 MiB
det(M[19]) = 0 in 0.93530082702637 secs using 3.8670501708984 MiB
det(M[20]) = 0 in 1.0820400714874 secs using 3.9884490966797 MiB
 */