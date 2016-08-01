<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */
namespace Chippyash\Math\Matrix\Special;

use Chippyash\Math\Matrix\Exceptions\MathMatrixException;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\String\StringType;
use Chippyash\Validation\Common\Lambda;
use Chippyash\Validation\Exceptions\InvalidParameterException;
use Chippyash\Validation\Logical\LAnd;
use Chippyash\Validation\Logical\LOr;
use Chippyash\Validation\Pattern\HasTypeMap;

/**
 * Cauchy Matrix
 * @link https://en.wikipedia.org/wiki/Cauchy_matrix
 */
class Cauchy extends AbstractSpecial
{
    const ERR1 = 'x and y must be vectors of same length for cauchy matrix';
    const ERR2 = 'x and y must be vectors';

    /**
     * Map of argument names
     * @var array
     */
    protected $map = ['x', 'y'];
    
    /**
     * @inheritDoc
     */
    protected function validateArguments(array $args)
    {
        $valA = new HasTypeMap([
                'x' => 'integer'
            ]
        );
        $valB = new HasTypeMap([
                'x' => 'Chippyash\Math\Matrix\NumericMatrix',
                'y' => 'Chippyash\Math\Matrix\NumericMatrix'
            ]
        );
        $valB1 = new Lambda(function($args) {
            return $args['x']->is('Vector') && $args['y']->is('Vector');
        },
            new StringType(self::ERR2));
        $valB2 = new Lambda(function($args) {
            return $args['x']->vertices() == $args['y']->vertices();
        },
            new StringType(self::ERR1)
        );
        $validator = new LOr(
            $valA,
            new LAnd(
                $valB,
                new LAnd(
                    $valB1, $valB2
                )
            )
        );

        if (!$validator->isValid($args)) {
            throw new InvalidParameterException(implode(':', $validator->getMessages()));
        }
    }

    /**
     * @inheritDoc
     */
    protected function createMatrix(array $args)
    {
        if (is_int($args['x'])) {
            return $this->createFromInt($args['x']);
        }

        return $this->createFromMatrices($args['x'], $args['y']);
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
     * @param NumericMatrix $mX Row Vector
     * @param NumericMatrix $mY Columnvector
     * @return NumericMatrix
     * @throws MathMatrixException
     */
    protected function createFromMatrices(NumericMatrix $mX, NumericMatrix $mY)
    {
        $mX = ($mX->is('columnvector') ? $mX : $mX = $mX('Transpose'));
        $mY = ($mY->is('rowvector') ? $mY : $mY = $mY('Transpose'));

        $ones = new Ones();
        $mVertices = $mX->vertices();
        $onesRow = $ones->create([1, $mVertices]);
        $onesCol = $ones->create([$mVertices, 1]);
        //C = x * ones (1, n) + ones (n, 1) * y
        $m1 = $mX('Mul\Matrix', $onesRow);
        $m2 = $onesCol('Mul\Matrix', $mY);
        $mC = $m1('Add\Matrix', $m2);
        $onesSquare = $ones->create([$mVertices, $mVertices]);
        //C = ones (n) ./ C;
        return $onesSquare('Div\Entrywise', $mC);
    }
}