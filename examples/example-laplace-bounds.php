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
$limit = 13;
$fDet = new Determinant(Determinant::METHOD_LAPLACE);
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

    $size ++;
}

/**
 * Results
 *
 * Before caching was implemenmted
det(M[1]) = 0 in 0.00085711479187012 secs using 0.35652160644531 MiB
det(M[2]) = -2 in 0.0028889179229736 secs using 0.58538818359375 MiB
det(M[3]) = 0 in 0.010598182678223 secs using 0.67438507080078 MiB
det(M[4]) = 0 in 0.056563854217529 secs using 0.68247985839844 MiB
det(M[5]) = 0 in 0.15500807762146 secs using 0.71510314941406 MiB
det(M[6]) = 0 in 0.90130615234375 secs using 0.85835266113281 MiB
det(M[7]) = 0 in 6.2557010650635 secs using 0.95252227783203 MiB
det(M[8]) = 0 in 52.768029928207 secs using 0.98097991943359 MiB
 *
 * With caching
det(M[1]) = 0 in 0.00142502784729 secs using 0.37363433837891 MiB
det(M[2]) = -2 in 0.0036768913269043 secs using 0.60457611083984 MiB
det(M[3]) = 0 in 0.010541915893555 secs using 0.70344543457031 MiB
det(M[4]) = 0 in 0.028731107711792 secs using 0.73818969726562 MiB
det(M[5]) = 0 in 0.095664024353027 secs using 0.79096984863281 MiB
det(M[6]) = 0 in 0.24548602104187 secs using 0.88328552246094 MiB
det(M[7]) = 0 in 0.63251781463623 secs using 1.0507659912109 MiB
det(M[8]) = 0 in 1.5667099952698 secs using 1.4335250854492 MiB
det(M[9]) = 0 in 4.1976501941681 secs using 1.9527969360352 MiB
det(M[10]) = 0 in 9.957062959671 secs using 2.9153289794922 MiB
det(M[11]) = 0 in 23.280742168427 secs using 4.7832870483398 MiB
det(M[12]) = 0 in 54.573163986206 secs using 8.4466705322266 MiB
 */