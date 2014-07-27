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
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\BoolType;

/**
 * Construct an identity matrix
 */
class IdentityMatrix extends FunctionMatrix
{
    /**
     * Construct a square matrix whose entries on the diagonal ==  1 or 1/1
     * All other entries == 0 or 0/1
     *
     * @param IntType $size Number of required rows and columns
     * @param BoolType $rationalise Turn numeric values into Rational numbers: default == false
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(IntType $size, BoolType $rationalise = null)
    {
        if ($size() < 1) {
            throw new \InvalidArgumentException('size must be >= 1');
        }
        if (is_null($rationalise)) {
            $r = false;
        } else {
            $r = $rationalise();
        }
        $f = function($row, $col) use ($r) {
            if ($r) {
                return new RationalType(new IntType($row == $col ? 1 : 0), new IntType(1));
            } else {
                return new IntType($row == $col ? 1 : 0);
            }
        };

        parent::__construct($f, $size, $size);
    }
    
    /**
     * Create and return a Rational Identity Matrix
     * 
     * @param \chippyash\Type\Number\IntType $size
     * @return \chippyash\Math\Matrix\RationalMatrix
     */
    public static function rationalIdentity(IntType $size)
    {
        $mA = new self($size, new BoolType(true));
        return new RationalMatrix($mA);
    }
}
