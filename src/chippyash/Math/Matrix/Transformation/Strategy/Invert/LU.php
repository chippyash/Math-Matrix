<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Transformation\Strategy\Invert;

use chippyash\Matrix\Transformation\Decomposition\Lu as LuDecomp;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Matrix\IdentityMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Interfaces\InversionStrategyInterface;
use chippyash\Matrix\Traits\Debug;

/**
 * LU Decomposition strategy for matrix inversion
 *
 */
class LU implements InversionStrategyInterface
{
    
    use Debug;
    
   /**
     * Compute inverse using LU Decomposition method
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return Matrix
     * @throws ComputationException
     */
    public function invert(Matrix $mA)
    {
        if (!$mA->is('Nonsingular')) {
            throw new ComputationException('Can only perform inversion on non singular matrix');
        }
        $Lu = new LuDecomp();
        $LU = $Lu($mA);
        //$LU = new LUDecomposition($mA);
        //$LU->solve($mI);
        $mI = new IdentityMatrix($mA->rows());

        // Copy right hand side with pivoting
        $nx = $mI->columns();
        $fSub = new \chippyash\Matrix\Transformation\Submatrix();
//        $X  = $mI->getMatrix($LU->PivotVector, 0, $nx-1);
        $this->debug('original $mI', $mI);
        $X = $fSub->transform($mI, array($LU->PivotVector->toArray(), 0, $nx-1));
        $this->debug('transformed $mI = $X', $X);
        
    }

}
