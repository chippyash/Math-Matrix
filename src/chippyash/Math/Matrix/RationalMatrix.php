<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix;

use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\Traits\ConvertNumberToRational;
use chippyash\Math\Matrix\Exceptions\MathMatrixException;
use chippyash\Math\Matrix\Interfaces\ComputatationInterface;
use chippyash\Math\Matrix\Interfaces\DerivativeInterface;
use chippyash\Matrix\Interfaces\TransformationInterface;

/**
 * Construct a matrix whose entries are Rational numbers
 *
 * Rational matrices are required by all the Computations.
 */
class RationalMatrix extends Matrix
{
    use ConvertNumberToRational;

    const NS_RATIONAL_ATTRIBUTE = 'chippyash\Maths\Matrix\Attribute\Is';
    const NS_COMPUTATION = 'chippyash\Math\Matrix\Computation\\';
    const NS_RTRANSFORMATION = 'chippyash\Math\Matrix\Transformation\\';
    const NS_DERIVATIVE = 'chippyash\Math\Matrix\Derivative\\';

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
        if ($source instanceof Matrix) {
            $this->store($source->toArray());
            return;
        }

        parent::__construct($source, false, true, (boolean) $normalizeDefault);
    }

    /**
     * Raw form of is() method. You can use this to test for attributes
     * not supplied with the library by passing in $attribute conforming to
     * AttributeInterface.  If it's something you think is important , consider
     * contributing it to the library.
     *
     * @extendAncestor
     *
     * @param string|AttributeInterface $attribute
     *
     * @return boolean
     *
     * @throws NotAnAttributeInterfaceException
     * @throws \BadMethodCallException
     */
    public function test($attribute)
    {
        if (is_string($attribute)) {
            $attribute = ucfirst(strtolower($attribute));
            $class = self::NS_RATIONAL_ATTRIBUTE . $attribute;
            if (class_exists($class)) {
                $obj = new $class();
            } else {
                //let parent try to find the class
                return parent::test($attribute);
            }
        } else {
            $obj = $attribute;
        }

        //pass object to parent for testing
        return parent::test($obj);
    }

    /**
     * Carry out a computation with this matrix as first argument and an
     * optional second argument
     *
     * @param \chippyash\Math\Matrix\Interfaces\ComputationInterface $computation
     * @param mixed $extra
     * @return \chippyash\Math\Matrix\RationalMatrix
     */
    public function compute(ComputatationInterface $computation, $extra = null)
    {
        return $computation->compute($this, $extra);
    }

    /**
     * Find a derivative of this matrix as first argument and an
     * optional second argument
     *
     * @param \chippyash\Math\Matrix\Interfaces\DerivativeInterface $derivative
     * @param mixed $extra
     * @return numeric
     */
    public function derive(DerivativeInterface $derivative, $extra = null)
    {
        return $derivative->derive($this, $extra);
    }

    /**
     *
     * @param \chippyash\Matrix\Interfaces\TransformationInterface $transformation
     * @param mixed $extra
     *
     * @return RationalMatrix
     */
    public function transform(TransformationInterface $transformation, $extra = null)
    {
        return new RationalMatrix(parent::transform($transformation, $extra)->toArray());
    }

    /**
     * Invokable interface - allows object to be called as function
     * Proxies to compute e.g.
     * $matrix("Add\Matrix", $mB)
     * Proxies to transform e.g.
     * $matrix("Invert")
     * Proxies to derive e.g.
     * $matrix("Trace")
     *
     * @overideAncestor
     *
     * @param string $operationName Name of operation to perform
     * @param mixed $extra Additional parameter required by the operation
     *
     * @return \chippyash\Math\Matrix\RationalMatrix
     *
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        //argument arbitrage
        $numArgs = func_num_args();
        if ($numArgs == 1) {
            $operationName = func_get_arg(0);
            $extra = null;
        } elseif($numArgs == 2) {
            $operationName = func_get_arg(0);
            $extra = func_get_arg(1);
        } else {
            throw new \InvalidArgumentException(self::ERR_INVALID_INVOKE_ARG);
        }

        $cName = self::NS_COMPUTATION . $operationName;
        if (class_exists($cName, true)) {
            return $this->compute(new $oName(), $extra);
        }

        //Rational transformations
        $tName = self::NS_RTRANSFORMATION . $operationName;
        if (class_exists($tName, true)) {
            return $this->transform(new $tName(), $extra);
        }

        $dName = self::NS_DERIVATIVE . $operationName;
        if (class_exists($dName, true)) {
            return $this->derive(new $dName(), $extra);
        }

        //parent transformations
        $tName = self::NS_TRANSFORMATION . $operationName;
        if (class_exists($tName, true)) {
            return $this->transform(new $tName(), $extra);
        }

        //else
        throw new \InvalidArgumentException(self::ERR_INVALID_OP_NAME);
    }


    /**
     * Store the data - rationalising it if requested
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
