<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */
namespace Chippyash\Math\Matrix\Special;

use Chippyash\Math\Matrix\Computation\Add\Matrix as AddMatrix;
use Chippyash\Math\Matrix\Computation\Div\Entrywise as DivEntrywise;
use Chippyash\Math\Matrix\Computation\Mul\Matrix as MulMatrix;
use Chippyash\Math\Matrix\Exceptions\MathMatrixException;
use Chippyash\Math\Matrix\Formatter\AsciiNumeric;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Validation\Exceptions\InvalidParameterException;
use Chippyash\Validation\Logical\LOr;
use Chippyash\Validation\Pattern\HasTypeMap;

/**
 * Cauchy Matrix
 * @link https://en.wikipedia.org/wiki/Cauchy_matrix
 */
class Cauchy extends AbstractSpecial
{
    const ERR1 = 'X and Y must be vectors of same length for cauchy matrix';
    const ERR2 = 'X and Y must be vectors';

    /**
     * Map of argument names
     * @var array
     */
    protected $map = ['X', 'Y'];
    
    /**
     * @inheritDoc
     */
    protected function validateArguments(array $args)
    {
        $valA = new HasTypeMap([
                'X' => 'integer'
            ]
        );
        $valB = new HasTypeMap([
                'X' => 'Chippyash\Math\Matrix\NumericMatrix',
                'Y' => 'Chippyash\Math\Matrix\NumericMatrix'
            ]
        );
        $validator = new LOr($valA, $valB);

        if (!$validator->isValid($args)) {
            throw new InvalidParameterException(implode(':', $validator->getMessages()));
        }
    }

    /**
     * @inheritDoc
     */
    protected function createMatrix(array $args)
    {
        if (is_int($args['X'])) {
            return $this->createFromInt($args['X']);
        }

        return $this->createFromMatrices($args['X'], $args['Y']);
    }

    /**
     * @param $val
     * @return NumericMatrix
     */
    protected function createFromInt($val)
    {
        $mX = new NumericMatrix(range(1, $val));
        $mY = clone $mX;

        return $this->createFromMatrices($mX, $mY);
    }

    /**
     * @param NumericMatrix $mX
     * @param NumericMatrix $mY
     * @return NumericMatrix
     * @throws MathMatrixException
     */
    protected function createFromMatrices(NumericMatrix $mX, NumericMatrix $mY)
    {
        $mVertices = $mX->vertices();
        if ($mVertices != $mY->vertices()) {
            throw new MathMatrixException(self::ERR1);
        }
        if (!($mX->is('columnvector') || $mX->is('rowvector'))) {
            throw new MathMatrixException(self::ERR2);
        }
        if (!($mY->is('columnvector') || $mY->is('rowvector'))) {
            throw new MathMatrixException(self::ERR2);
        }

        $mX = ($mX->is('columnvector') ? $mX : $mX = $mX('Transpose'));
        $mY = ($mY->is('rowvector') ? $mY : $mY = $mY('Transpose'));

        $onesRow = $this->ones(1, $mVertices);
        $a = $onesRow->setFormatter(new AsciiNumeric())->display();
        $onesCol = $onesRow('Transpose');
        $a = $onesCol->setFormatter(new AsciiNumeric())->display();
        //C = x * ones (1, n) + ones (n, 1) * y
        $m1 = $mX('Mul\Matrix', $onesRow);
        $a = $m1->setFormatter(new AsciiNumeric())->display();
        $m2 = $onesCol('Mul\Matrix', $mY);
        $a = $m2->setFormatter(new AsciiNumeric())->display();
        $mC = $m1('Add\Matrix', $m2);
        $a = $mC->setFormatter(new AsciiNumeric())->display();
        $onesSquare = $this->ones($mVertices, $mVertices);
        //C = ones (n) ./ C;
        $mC = $onesSquare('Div\Entrywise', $mC);
        $b = $mC->setFormatter(new AsciiNumeric())->display();
        return $mC;
    }

    private function ones($rows, $cols)
    {
        $ret = [];
        for ($i = 0; $i < $rows; $i++) {
            $ret[] = range(1, $cols);
        }
        return new NumericMatrix($ret);
    }
}