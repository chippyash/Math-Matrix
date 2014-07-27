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
use chippyash\Math\Matrix\Traits\ConvertNumberToRational;
use chippyash\Math\Matrix\Exceptions\MathMatrixException;

/**
 * Construct a matrix whose entries are Rational numbers
 *
 */
class RationalMatrix extends NumericMatrix
{
    use ConvertNumberToRational;

    /**
     * Construct a complete Matrix with all entries set to a rational number
     * Takes a source matrix or array (which can be incomplete and converts each
     * entry to rational number, setting a default value if entry does not exist.
     *
     * If a Matrix is supplied as $source, the data is cloned into the RationalMatrix
     * converting to rational number values, with no further checks, although you
     * may get exceptions thrown if conversion is not possible.
     *
     * @param Matrix|array $source Array to initialise the matrix with
     * @param mixed $normalizeDefault Value to set missing vertices
     *
     */
    public function __construct($source, $normalizeDefault = 0)
    {
        $default = $this->convertNumberToRational($normalizeDefault);
        parent::__construct($source, $default);
    }

    /**
     * Store the data - rationalising it if required
     *
     * @param array $data
     *
     * @return void
     *
     * @throws \chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    protected function store(array $data) {
        try {
            foreach ($data as &$row) {
                foreach ($row as &$item) {
                    $item = $this->convertNumberToRational($item);
                }
            }
            $this->data = $data;
            $this->rationalised = true;
        } catch (ArithmeticException $e) {
            throw new MathMatrixException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
