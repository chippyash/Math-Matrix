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
 * Chebspec Matrix
 * CHEBSPEC  Chebyshev spectral differentiation matrix.
 *            C = CHEBSPEC(N, K) is a Chebyshev spectral differentiation
 *            matrix of order N.  K = 0 (the default) or 1.
 *            For K = 0 (`no boundary conditions'), C is nilpotent, with
 *                C^N = 0 and it has the null vector ONES(N,1).
 *                C is similar to a Jordan block of size N with eigenvalue zero.
 *            For K = 1, C is nonsingular and well-conditioned, and its eigenvalues
 *                have negative real parts.
 *            For both K, the computed eigenvector matrix X from EIG is
 *                ill-conditioned (MESH(REAL(X)) is interesting).
 * 
 *            References:
 *            C. Canuto, M.Y. Hussaini, A. Quarteroni and T.A. Zang, Spectral
 *               Methods in Fluid Dynamics, Springer-Verlag, Berlin, 1988; p. 69.
 *            L.N. Trefethen and M.R. Trummer, An instability phenomenon in
 *               spectral methods, SIAM J. Numer. Anal., 24 (1987), pp. 1008-1023.
 *            D. Funaro, Computing the inverse of the Chebyshev collocation
 * 
 * @link https://www.gnu.org/software/octave/doc/v4.0.0/Famous-Matrices.html#Famous-Matrices
 */
class Chebspsec extends AbstractSpecial
{
    const ERR1 = 'x and y must be vectors of same length for cauchy matrix';
    const ERR2 = 'x and y must be vectors';

    /**
     * Map of argument names
     * @var array
     */
    protected $map = ['N', 'K'];
    
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