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
use Chippyash\Type\Number\Complex\ComplexTypeFactory;
use Chippyash\Type\Number\Rational\RationalType;
use Chippyash\Type\Number\Rational\RationalTypeFactory;
use Chippyash\Math\Matrix\Exceptions\MathMatrixException;
use Chippyash\Type\Number\IntType;

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
     * @return \Chippyash\Math\Matrix\NumericMatrix
     */
    public static function create($type, array $data)
    {
        switch (strtolower($type)) {
            case 'complex':
                return self::createComplex($data);
            case 'rational':
                return self::createRational($data);
            case 'numeric':
            default:
                return self::createNumeric($data);
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
     * @return \Chippyash\Math\Matrix\ComplexMatrix
     *
     * @throws \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public static function createComplex(array $data)
    {
        foreach ($data as &$row) {
            foreach ($row as &$item) {
                if (!$item instanceof ComplexType) {
                    if (is_array($item) && count($item) == 2) {
                        $item = ComplexTypeFactory::create($item[0], $item[1]);
                    } elseif (is_string($item)) {
                        try {
                            $item = ComplexTypeFactory::fromString($item);
                        } catch (\InvalidArgumentException $e) {
                            throw new MathMatrixException('Invalid item type for Complex Matrix');
                        }
                    } else {
                        throw new MathMatrixException('Invalid item type for Complex Matrix');
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
     * @return \Chippyash\Math\Matrix\RationalMatrix
     *
     * @throws \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public static function createRational(array $data)
    {
        foreach ($data as &$row) {
            foreach ($row as &$item) {
                if (!$item instanceof RationalType) {
                    if (is_array($item) && count($item) == 2) {
                        $item = RationalTypeFactory::create($item[0], $item[1]);
                    } elseif (is_string($item)) {
                        try {
                            $item = RationalTypeFactory::fromString($item);
                        } catch (\InvalidArgumentException $e) {
                            throw new MathMatrixException('Invalid item type for Rational Matrix');
                        }
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
     * @return \Chippyash\Math\Matrix\NumericMatrix
     */
    public static function createNumeric(array $data)
    {
        return new NumericMatrix($data);
    }

    /**
     * Construct a complete matrix whose entries are a result of a function
     *
     * The function must accept two parameters
     *  e.g. $function($row, $col) {return $row - $col;}
     *
     * $row and $col are 1 based
     *
     * @param callable $fn
     * @param IntType $rows Number of required rows
     * @param IntType $cols Number or required columns
     * @param string $type ['numeric'|'rational'|'complex']
     *
     * @return \Chippyash\Math\Matrix\ComplexMatrix|\Chippyash\Math\Matrix\NumericMatrix|\Chippyash\Math\Matrix\RationalMatrix
     *
     * @throws \InvalidArgumentException
     */
    public static function createFromFunction(callable $fn, IntType $rows, IntType $cols, $type = 'numeric')
    {
        if ($rows() < 1) {
            throw new \InvalidArgumentException('$rows must be >= 1');
        }
        if ($cols() < 1) {
            throw new \InvalidArgumentException('$cols must be >= 1');
        }

        $source = array();
        $rc = $rows();
        $cc = $cols();
        for ($r = 0; $r < $rc; $r++) {
            for ($c = 0; $c < $cc; $c++) {
                $source[$r][$c] = $fn($r + 1, $c + 1);
            }
        }

        return self::create($type, $source);
    }
    
    /**
     * Create a matrix representation of a complex number
     * For z = a+bi
     * Returns [[a, -b]
     *          [b, a]]
     * 
     * @param \Chippyash\Type\Number\Complex\ComplexType $c
     * @return \Chippyash\Math\Matrix\RationalMatrix
     */
    public static function createFromComplex(ComplexType $c)
    {
        $a = clone $c->r();
        $b = clone $c->i();
        $b2 = clone $c->i();
        $bi = new RationalType($b2->numerator()->negate(), $b->denominator());
        return self::createRational([[$a, $bi],[$b, $a]]);
    }
}
