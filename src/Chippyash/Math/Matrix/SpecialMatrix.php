<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2016
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix;

use Chippyash\Math\Matrix\Exceptions\MathMatrixException;
use Chippyash\Math\Matrix\Special\SpecialMatrixInterface;
use Chippyash\Type\String\StringType;


/**
 * Create and return 'special' matrices.
 * Inspired by Octave/Matlab
 * 
 * This is really a factory class
 *
 * Usage:
 * //inline creation if your version of PHP allows it
 * $mS = (new SpecialMatrix())->create(new StringType('NameOfMatrix')[, $arg1, $arg2]);
 *
 * //or as an invokable class
 * $factory = new SpecialMatrix();
 * $mS = $factory(new StringType('nameOfMatrix')[, $arg1, $arg2]);
 * //or
 * $mS = $factory('NameOfMatrix'[, $arg1, $arg2]);
 *
 */
class SpecialMatrix
{
    const ERR1 = 'You need at least the name of the special matrix to create it!';
    const ERR2 = 'Matrix named %s does not exist';
    const ERR3 = 'Matrix named %s does not implement SpecialMatrixInterface';
    const NS = '\\Chippyash\\Math\\Matrix\\Special\\';

    /**
     * Magic invokable method for class
     * Proxies to create()
     * @see create()
     *
     * @param string|StringType Matrix name
     * @param mixed Dependent on Matrix name.  Multiple arguments may be presented
     *
     * @throws MathMatrixException
     *
     * @return NumericMatrix
     */
    public function __invoke()
    {
        $args = func_get_args();
        $nArgs = func_num_args();
        if ($nArgs === 0) {
            throw new MathMatrixException(self::ERR1);
        }
        $temp = array_shift($args);
        $mName = ($temp instanceof StringType ? $temp : new StringType($temp));

        return $this->create($mName, $args);
    }

    /**
     * Create a special matrix
     *
     * @param StringType $matrixName
     * @param array $args
     *
     * @throws MathMatrixException
     *
     * @return NumericMatrix
     */
    public function create(StringType $matrixName, array $args = [])
    {
        $mName = self::NS . ucfirst($matrixName);
        if (!class_exists($mName)) {
            throw new MathMatrixException(sprintf(self::ERR2, $mName));
        }

        $mClass = new $mName();
        if (!$mClass instanceof SpecialMatrixInterface) {
            throw new MathMatrixException(sprintf(self::ERR3, $mName));
        }

        return $mClass->create($args);
    }
}
