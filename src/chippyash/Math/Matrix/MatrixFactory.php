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
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Type\Number\Complex\ComplexType;
use chippyash\Type\Number\Complex\ComplexTypeFactory;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Math\Matrix\Exceptions\MathMatrixException;

/**
 * Static factory to create the various standard numerical matrices
 * 
 */
abstract class MatrixFactory
{
 
    /**
     * 
     * @param string $type
     * @param array $data
     * @return chippyash\Math\Matrix\NumericMatrix
     */
    public static function create($type, array $data)
    {
        switch (strtolower($type)) {
            case 'complex':
                return self::createComplex($data);
                break;
            case 'rational':
                return self::createRational($data);
                break;
            case 'numeric':
            default:
                return self::createNumeric($data);
                break;
        }
    }

    /**
     * Create and return a complex number matrix
     * $data elements are either:
     *  - a ComplexType
     *  - string representations of complex number
     *  - a 2 item array representing r & i e.g. [2,-4] = '2-4i'
     * 
     * @param array $data
     * 
     * @return \chippyash\Math\Matrix\ComplexMatrix
     * 
     * @throws chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public static function createComplex(array $data)
    {
        foreach ($data as &$row) {
            foreach ($row as &$item) {
                if (!$item instanceof ComplexType) {
                    if (is_array($item)) {
                        $item = ComplexTypeFactory::create($item[0], $item[1]);
                    } elseif (is_string($item)) {
                        $item = ComplexTypeFactory::create($item);
                    } else {
                        throw new MathMatrixException('Invalid item type for Rational Matrix');
                    }
                }
            }
        }
        return new ComplexMatrix($data);
    }

    /**
     * Create and return a rational number matrix
     * $data elements are either:
     *  - a RationalType
     *  - string representations of rational number
     *  - a PHP float
     *  - a 2 item array representing numerator & denominator e.g. [2,-4] = '-2/4'
     * 
     * @param array $data
     * 
     * @return \chippyash\Math\Matrix\RationalMatrix
     * 
     * @throws chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public static function createRational(array $data)
    {
        foreach ($data as &$row) {
            foreach ($row as &$item) {
                if (!$item instanceof RationalType) {
                    if (is_array($item) && count($item) == 2) {
                        $item = RationalTypeFactory::create($item[0], $item[1]);
                    } elseif (is_string($item)) {
                        $item = RationalTypeFactory::fromString($item);
                    } elseif(is_float($item)) {
                        $item = RationalTypeFactory::fromFloat($item);
                    } else {
                        throw new MathMatrixException('Invalid item type for Rational Matrix');
                    }
                }
            }
        }

        return new RationalMatrix($data);
    }

    /**
     * Create and return a numeric value matrix
     * 
     * @param array $data
     * @return \chippyash\Math\Matrix\NumericMatrix
     */
    public static function createNumeric(array $data)
    {
        return new NumericMatrix($data);        
    }

}
