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

use chippyash\Math\Matrix\FunctionMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\Complex\ComplexType;

/**
 * Construct an identity matrix
 */
class IdentityMatrix extends FunctionMatrix
{
    const IDM_TYPE_INT = 0;
    const IDM_TYPE_RATIONAL = 1;
    const IDM_TYPE_COMPLEX = 2;

    /**
     * Available types of identity matrix
     *
     * @var array
     */
    private $availableTypes = [
        self::IDM_TYPE_INT,
        self::IDM_TYPE_RATIONAL,
        self::IDM_TYPE_COMPLEX ];

    /**
     * Construct a square NumericMatrix whose entries on the diagonal ==  1, 1/1 or 1+0i
     * All other entries == 0, 0/1 or 0+0i
     *
     * @param IntType $size Number of required rows and columns
     * @param IntType $identityType Type of identity entries: default == IDM_TYPE_INT
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(IntType $size, IntType $identityType = null)
    {
        if (is_null($identityType)) {
            $idt = self::IDM_TYPE_INT;
        } else {
            $idt = $identityType();
        }
        if (!in_array($idt, $this->availableTypes)) {
            throw new \InvalidArgumentException('Identity type invalid');
        }

        if ($size() < 1) {
            throw new \InvalidArgumentException('size must be >= 1');
        }

        $f = function($row, $col) use ($idt) {
            if ($idt == self::IDM_TYPE_RATIONAL) {
                return new RationalType(new IntType($row == $col ? 1 : 0), new IntType(1));
            } elseif ($idt == self::IDM_TYPE_COMPLEX) {
                return new ComplexType(
                        new RationalType(new IntType($row == $col ? 1 : 0), new IntType(1)),
                        new RationalType(new IntType(0), new IntType(1)));
            } else {
                return new IntType($row == $col ? 1 : 0);
            }
        };

        parent::__construct($f, $size, $size);
    }

    /**
     * Create and return a Rational Identity Matrix with RationalType entries
     *
     * @param \chippyash\Type\Number\IntType $size
     * @return \chippyash\Math\Matrix\RationalMatrix
     */
    public static function rationalIdentity(IntType $size)
    {
        $mA = new self($size, new IntType(self::IDM_TYPE_RATIONAL));
        return new RationalMatrix($mA);
    }

    /**
     * Create and return a Complex Identity Matrix with ComplexType entries
     *
     * @param \chippyash\Type\Number\IntType $size
     * @return \chippyash\Math\Matrix\RationalMatrix
     */
    public static function complexIdentity(IntType $size)
    {
        $mA = new self($size, new IntType(self::IDM_TYPE_COMPLEX));
        return new ComplexMatrix($mA);
    }

    /**
     * Create and return a Numeric Identity Matrix with IntType entries
     *
     * @param \chippyash\Type\Number\IntType $size
     * @return \chippyash\Math\Matrix\NumericMatrix
     */
    public static function numericIdentity(IntType $size)
    {
        return new self($size, new IntType(self::IDM_TYPE_INT));
    }


}
