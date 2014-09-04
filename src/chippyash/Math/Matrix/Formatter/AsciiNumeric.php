<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Formatter;

use chippyash\Matrix\Formatter\Ascii as BaseAscii;
use chippyash\Matrix\Matrix;
use chippyash\Type\Interfaces\NumericTypeInterface;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Type\Number\Complex\ComplexTypeFactory;

/**
 * Format matrix as an ascii diagram
 * Extension to base Ascii formatter to allow setting of display type
 * for entries.  Should only be used for Numeric matrices.
 */
class AsciiNumeric extends BaseAscii
{

    //Type of numeric output required
    const TP_NONE     = 0; //behave as base formatter
    const TP_INT      = 1; //convert all entries to int.  This will floor() if possible
    const TP_FLOAT    = 2; //convert all entries to float if possible
    const TP_RATIONAL = 3; //convert all entries to rational if possible
    const TP_COMPLEX  = 4; //convert all entries to complex (always possible)

    /**
     * Set outputType to one of self::TP_...
     * @var array
     */
    protected $extendedOptions = array(
        'outputType' => self::TP_NONE,
    );

    /**
     * Format the matrix contents for outputting.
     *
     * @param chippyash\Math\Matrix\NumericMatrix $mA Matrix to format
     * @param array $options Options for formatter
     *
     * @return string
     */
    public function format(Matrix $mA, array $options = array())
    {
        $this->setOptions($options);
        return parent::format($this->convertEntries($mA), $options);
    }

    /**
     * Set formatting optionst
     *
     * @param array $options
     */
    protected function setOptions(array $options = array())
    {
        $this->options = array_merge($this->options, $this->extendedOptions, $options);
    }

    /**
     * Convert matrix entries if required
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return \chippyash\Matrix\Matrix
     */
    protected function convertEntries(Matrix $mA)
    {
        switch ($this->options['outputType']) {
            case self::TP_INT:
                return $this->toInt($mA->toArray());
            case self::TP_FLOAT:
                return $this->toFloat($mA->toArray());
            case self::TP_RATIONAL:
                return $this->toRational($mA->toArray());
            case self::TP_COMPLEX:
                return $this->toComplex($mA->toArray());
            case self::TP_NONE:
            default :
                return $mA;
        }
    }

    protected function toInt(array $dA)
    {
        foreach ($dA as &$row) {
            foreach ($row as &$entry) {
                if ($entry instanceof NumericTypeInterface) {
                    $entry = $entry->asIntType()->get();
                } else {
                    $entry = intval($entry);
                }
            }
        }
        return new Matrix($dA);
    }

    protected function toFloat(array $dA)
    {
        foreach ($dA as &$row) {
            foreach ($row as &$entry) {
                if ($entry instanceof NumericTypeInterface) {
                    $entry = $entry->asFloatType()->get();
                } else {
                    $entry = floatval($entry);
                }
            }
        }
        return new Matrix($dA);
    }

    protected function toRational(array $dA)
    {
        foreach ($dA as &$row) {
            foreach ($row as &$entry) {
                if ($entry instanceof NumericTypeInterface) {
                    $entry = $entry->asRational();
                } else {
                    $entry = RationalTypeFactory::fromFloat(floatval($entry));
                }
            }
        }
        return new Matrix($dA);
    }

    protected function toComplex(array $dA)
    {
        foreach ($dA as &$row) {
            foreach ($row as &$entry) {
                if ($entry instanceof NumericTypeInterface) {
                    $entry = $entry->asComplex();
                } else {
                    $entry = ComplexTypeFactory::create($entry);
                }
            }
        }
        return new Matrix($dA);
    }

}
