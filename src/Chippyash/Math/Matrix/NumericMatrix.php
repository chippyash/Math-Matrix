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

use Chippyash\Matrix\Matrix;
use Chippyash\Math\Matrix\Traits\ConvertNumberToNumeric;
use Chippyash\Math\Matrix\Exceptions\MathMatrixException;
use Chippyash\Math\Matrix\Interfaces\ComputatationInterface;
use Chippyash\Math\Matrix\Interfaces\DerivativeInterface;
use Chippyash\Math\Matrix\Interfaces\DecompositionInterface;
use Chippyash\Matrix\Interfaces\TransformationInterface;
use Chippyash\Type\Number\Rational\RationalTypeFactory;
use Chippyash\Type\Number\IntType;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Math\Type\Comparator;

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

    const NS_NUMERIC_ATTRIBUTE = 'Chippyash\Math\Matrix\Attribute\Is';
    const NS_COMPUTATION = 'Chippyash\Math\Matrix\Computation\\';
    const NS_NTRANSFORMATION = 'Chippyash\Math\Matrix\Transformation\\';
    const NS_DERIVATIVE = 'Chippyash\Math\Matrix\Derivative\\';
    const NS_DECOMPOSITION = 'Chippyash\Math\Matrix\Decomposition\\';

    /**
     * Construct a complete Matrix with all entries set to Chippyash/Type
     * Takes a source matrix or array (which can be incomplete and converts each
     * entry to Chippyash/Type), setting a default value if entry does not exist.
     *
     * If a NumericMatrix is supplied as $source, the data is cloned into the Matrix
     * with no further checks.
     *
     * @param NumericMatrix|array $source Array to initialise the matrix with
     * @param mixed $normalizeDefault Value to set missing vertices
     * @throws Chippyash\Math\Matrix\Exceptions\MathMatrixException
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
     * @param \Chippyash\Math\Matrix\Interfaces\ComputationInterface $computation
     * @param mixed $extra
     * @return \Chippyash\Math\Matrix\NumericMatrix
     */
    public function compute(ComputatationInterface $computation, $extra = null)
    {
        return $computation->compute($this, $extra);
    }

    /**
     * Find a derivative of this matrix as first argument and an
     * optional second argument
     *
     * @param \Chippyash\Math\Matrix\Interfaces\DerivativeInterface $derivative
     * @param mixed $extra
     * @return numeric
     */
    public function derive(DerivativeInterface $derivative, $extra = null)
    {
        return $derivative->derive($this, $extra);
    }

    /**
     * Decompose this matrix
     *
     * @param \Chippyash\Math\Matrix\Interfaces\DecompositionInterface $decomposition
     * @param mixed $extra
     * @return \Chippyash\Math\Matrix\Interfaces\DecompositionInterface
     */
    public function decompose(DecompositionInterface $decomposition, $extra = null)
    {
        return $decomposition->decompose($this, $extra);
    }

    /**
     *
     * @param \Chippyash\Matrix\Interfaces\TransformationInterface $transformation
     * @param mixed $extra
     *
     * @return NumericMatrix
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
     * @return \Chippyash\Math\Matrix\NumericMatrix
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

        //Numeric transformations
        $tName = self::NS_NTRANSFORMATION . $operationName;
        if (class_exists($tName, true)) {
            return $this->transform(new $tName(), $extra);
        }

        $dName = self::NS_DERIVATIVE . $operationName;
        if (class_exists($dName, true)) {
            return $this->derive(new $dName(), $extra);
        }

        $dcName = self::NS_DECOMPOSITION . $operationName;
        if (class_exists($dcName, true)) {
            return $this->decompose(new $dcName(), $extra);
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
     * Check equality of each matrix entry
     * Also check that matrices are same type if $strict
     *
     * @override ancestor
     *
     * @param \Chippyash\Matrix\Matrix $mB
     * @param boolean $strict
     *
     * @return boolean
     */
    protected function checkEntryEquality(Matrix $mB, $strict)
    {
        if ($strict) {
            if (get_class($this) !== get_class($mB)) {
                return false;
            }
        }

        $dA = $this->toArray();
        $dB = $mB->toArray();
        $m = $this->rows();
        $n = $this->columns();
        $comp = new Comparator();

        for ($i=0; $i<$m; $i++) {
            for ($j=0; $j<$n; $j++) {
                if ($strict) {
                    if ($dA[$i][$j] !== $dB[$i][$j]) {
                        return false;
                    }
                } else {
                    if ($comp->neq($dA[$i][$j], $dB[$i][$j])) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Store the data converting to equivalent strong types
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
                $item = $this->convertNumberToNumeric($item);
            }
        }
        $this->data = $data;
    }
}
