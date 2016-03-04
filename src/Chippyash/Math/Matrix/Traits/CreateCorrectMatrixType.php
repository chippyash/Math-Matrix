<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Traits;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\ComplexMatrix;

/**
 * Create a new matrix based on type of original matrix
 */
Trait CreateCorrectMatrixType
{

    /**
     * Create a new matrix based on type of original matrix
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $originalMatrix
     * @param array $data
     * 
     * @return NumericMatrix|RationalMatrix|ComplexMatrix
     */
    protected function createCorrectMatrixType(NumericMatrix $originalMatrix , array $data = [])
    {
        if ($originalMatrix instanceof ComplexMatrix) {
            return new ComplexMatrix($data);
        }
        if ($originalMatrix instanceof RationalMatrix) {
            return new RationalMatrix($data);
        }
        
        return new NumericMatrix($data);
    }
}
