<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix;

use Chippyash\Type\Number\Complex\ComplexType;
use Chippyash\Math\Matrix\Traits\ConvertNumberToComplex;
use Chippyash\Type\Number\Rational\RationalTypeFactory;
use Chippyash\Type\Number\Complex\ComplexTypeFactory;

/**
 * Construct a matrix whose entries are complex numbers
 */
class ComplexMatrix extends NumericMatrix
{
    use ConvertNumberToComplex;

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
            $ri = RationalTypeFactory::create(0, 1);
            $normalizeDefault = ComplexTypeFactory::create($ri, clone $ri);
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
     * @throws \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    protected function store(array $data) {
        foreach ($data as &$row) {
            foreach ($row as &$item) {
                $item = $this->convertNumberToComplex($item);
            }
        }
        $this->data = $data;
    }

}
