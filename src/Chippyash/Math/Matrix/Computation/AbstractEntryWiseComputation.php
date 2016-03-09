<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Computation;


use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use Chippyash\Matrix\Traits\AssertParameterIsMatrix;
use Chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual;
use Chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;
use Chippyash\Math\Matrix\ZeroMatrix as ZMatrix;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Type\TypeFactory;
use Chippyash\Math\Type\Calculator;

/**
 * Abstract base to run an entrywise computation on a matrix
 * Two standard Matrix calcs are Addition and Subtraction
 * You can also do entrywise multiplication and division
 */
abstract class AbstractEntryWiseComputation extends AbstractComputation
{
    use AssertParameterIsMatrix;
    use AssertMatrixRowsAreEqual;
    use AssertMatrixColumnsAreEqual;
    use CreateCorrectMatrixType;

    /**
     * @inheritDoc
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter is not a matrix');

        if ($mA->is('empty') || $extra->is('empty')) {
            return $this->createCorrectMatrixType($mA, []);
        }

        $this->assertMatrixColumnsAreEqual($mA, $extra)
            ->assertMatrixRowsAreEqual($mA, $extra);

        $mZ = new ZMatrix(TypeFactory::createInt($mA->rows()), TypeFactory::createInt($mA->columns()));
        $data = $mZ->toArray();

        $dA = $mA->toArray();
        $dB = $extra->toArray();

        $cols = $mA->columns();
        $rows = $mA->rows();
        $calc = new Calculator();

        for ($row=0; $row<$rows; $row++) {
            for ($col=0; $col<$cols; $col++) {
                $data[$row][$col] = $this->doCompute($dA[$row][$col], $dB[$row][$col], $calc);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

    /**
     * Do the actual computation on the two matrix vertices
     *
     * @param NumericTypeInterface $a
     * @param NumericTypeInterface $b
     * @param Calculator $calc
     *
     * @return NumericTypeInterface
     */
    abstract protected function doCompute(NumericTypeInterface $a, NumericTypeInterface $b, Calculator $calc);
}