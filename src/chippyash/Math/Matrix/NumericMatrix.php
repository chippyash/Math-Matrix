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

use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\Traits\ConvertNumberToNumeric;
use chippyash\Math\Matrix\Exceptions\MathMatrixException;
use chippyash\Math\Matrix\Interfaces\ComputatationInterface;
use chippyash\Math\Matrix\Interfaces\DerivativeInterface;
use chippyash\Matrix\Interfaces\TransformationInterface;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\NumericTypeInterface;

/**
 * Construct a matrix whose entries are numeric, i.e int, float, IntType, 
 * FloatType or RationalType
 * 
 * On construction PHP native ints are converted to IntType and native
 * float types are converted to RationalType
 *
 */
class NumericMatrix extends Matrix
{
    use ConvertNumberToNumeric;

    const NS_NUMERIC_ATTRIBUTE = 'chippyash\Maths\Matrix\Attribute\Is';
    const NS_COMPUTATION = 'chippyash\Math\Matrix\Computation\\';
    const NS_RTRANSFORMATION = 'chippyash\Math\Matrix\Transformation\\';
    const NS_DERIVATIVE = 'chippyash\Math\Matrix\Derivative\\';

    /**
     * Construct a complete Matrix with all entries set to chippyash/Type
     * Takes a source matrix or array (which can be incomplete and converts each
     * entry to chippyash/Type), setting a default value if entry does not exist.
     *
     * If a NumericMatrix is supplied as $source, the data is cloned into the Matrix
     * with no further checks.
     *
     * @param NumericMatrix|array $source Array to initialise the matrix with
     * @param mixed $normalizeDefault Value to set missing vertices
     * @throws chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public function __construct($source, $normalizeDefault = 0)
    {
        if ($source instanceof self) {
            $this->store($source->toArray());
            return;
        }
        if (is_array($source)) {
            if (is_int($normalizeDefault)) {
                $default = new IntType($normalizeDefault);
            } elseif (is_float($normalizeDefault)) {
                $default = RationalTypeFactory::fromFloat($normalizeDefault);
            } elseif (!$normalizeDefault instanceof NumericTypeInterface) {
                throw new MathMatrixException('NumericMatrix expects numeric default value');
            } else {
                $default = $normalizeDefault;
            }
            parent::__construct($source, false, true, $default);
        } else {
            throw new MathMatrixException('NumericMatrix expects NumericMatrix or array as source data');
        }
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
            $class = self::NS_NUMERIC_ATTRIBUTE . $attribute;
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
        return new self(parent::transform($transformation, $extra)->toArray());
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
            return $this->compute(new $cName(), $extra);
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
     * Store the data converting to equivalent strong types
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
                    $item = $this->convertNumberToNumeric($item);
                }
            }
            $this->data = $data;
        } catch (ArithmeticException $e) {
            throw new MathMatrixException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
