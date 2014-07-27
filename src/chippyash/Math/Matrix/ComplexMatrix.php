<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Exceptions\MathMatrixException;
use chippyash\Type\Number\Complex\ComplexType;
use chippyash\Type\Number\FloatType;

/**
 * Construct a matrix whose entries are complex numbers
 */
class ComplexMatrix extends NumericMatrix
{
    /**
     * Construct a complete Matrix with all entries set to a complex number
     * Takes a source matrix or array (which can be incomplete and converts each
     * entry to complex number type, setting a default value if entry does not exist.
     *
     * If a Matrix is supplied as $source, the data is cloned into the ComplexMatrix
     * converting to complex number values, with no further checks, although you
     * may get exceptions thrown if conversion is not possible.
     * 
     * If you don't supply a default value, then 0+0i will be used
     *
     * @param Matrix|array $source Array to initialise the matrix with
     * @param ComplexType $normalizeDefault Value to set missing vertices
     *
     */
    public function __construct($source, ComplexType $normalizeDefault = null)
    {
        if (is_null($normalizeDefault)) {
            $ri = new FloatType(0);
            $normalizeDefault = new ComplexType($ri, $ri);
        }
        parent::__construct($source, $normalizeDefault);
    }

    /**
     * Store the data - checking that it is complex
     *
     * @param array $data
     *
     * @return void
     *
     * @throws \chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    protected function store(array $data) {
        try {
            foreach ($data as $row) {
                foreach ($row as $item) {
                    if (!$item instanceof ComplexType) {
                        throw new MathMatrixException('Non complex numbers not allowed in a complex matrix');
                    }
                }
            }
            $this->data = $data;
            $this->rationalised = true;
        } catch (ArithmeticException $e) {
            throw new MathMatrixException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
