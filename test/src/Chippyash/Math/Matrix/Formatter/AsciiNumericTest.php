<?php
namespace Chippyash\Test\Math\Matrix\Formatter;
use Chippyash\Math\Matrix\Formatter\AsciiNumeric;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\Number\FloatType;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\RequiredType;
use Chippyash\Type\TypeFactory;

/**
 * Unit test for Matrix AsciiNumeric formatter
 */
class AsciiNumericTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ascii
     */
    protected $object;

    protected $rationalOne;
    protected $rationalHalf;
    protected $complexTwo;
    protected $complexThree;

    public function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new AsciiNumeric();
        $this->rationalOne = TypeFactory::createRational(TypeFactory::createInt(1), TypeFactory::createInt(1));
        $this->rationalHalf = TypeFactory::createRational(TypeFactory::createInt(1), TypeFactory::createInt(2));
        $this->complexTwo = TypeFactory::createComplex(
                TypeFactory::createRational(TypeFactory::createInt(2), TypeFactory::createInt(1)),
                TypeFactory::createRational(TypeFactory::createInt(0), TypeFactory::createInt(1))
                );
        $this->complexThree = TypeFactory::createComplex(
                TypeFactory::createRational(TypeFactory::createInt(3), TypeFactory::createInt(1)),
                TypeFactory::createRational(TypeFactory::createInt(-3), TypeFactory::createInt(2))
                );
    }

    public function testConstructGivesFormatterInterface()
    {
        $this->assertInstanceOf('Chippyash\Math\Matrix\Formatter\AsciiNumeric', $this->object);
        $this->assertInstanceOf('Chippyash\Matrix\Interfaces\FormatterInterface', $this->object);
    }

    public function testFormatAsIntTypeReturnsIntegers()
    {
        $mA = new NumericMatrix([[$this->rationalOne,$this->complexTwo,10.4]]);
        $test = <<<EOF
+---------+
|  1  2 10|
+---------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_INT]));
    }

    public function testFormatAsFloatTypeReturnsFloats()
    {
        $mA = new NumericMatrix([[$this->rationalOne,$this->complexTwo,10.4]]);
        $test = <<<EOF
+---------------+
|    1    2 10.4|
+---------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_FLOAT]));
    }

    public function testFormatAsRationalReturnsRational()
    {
        $mA = new NumericMatrix([[1/2,2,10.4]]);
        $test = <<<EOF
+---------------+
|  1/2    2 52/5|
+---------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_RATIONAL]));
    }

    public function testFormatAsComplexReturnsComplex()
    {
        $mA = new NumericMatrix([[1/2,$this->complexTwo,10.4, $this->complexThree]]);
        $test = <<<EOF
+----------------------------+
|    1/2      2   52/5 3-3/2i|
+----------------------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_COMPLEX]));
    }

    public function testFormatDefaultReturnsOriginalContentWithBaseMatrix()
    {
        $mA = new Matrix([['foo',$this->complexTwo,10.4, $this->complexThree]]);
        $test = <<<EOF
+----------------------------+
|    foo      2   10.4 3-3/2i|
+----------------------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_NONE]));
        $this->assertEquals($test, $this->object->format($mA));

    }

    public function testFormatIntReturnsIntsWithBaseMatrix()
    {
        $mA = new Matrix([[23, $this->rationalOne,$this->complexTwo,10.4]]);
        $test = <<<EOF
+------------+
| 23  1  2 10|
+------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_INT]));

    }

    public function testFormatFloatReturnsFloatsWithBaseMatrix()
    {
        $mA = new Matrix([[$this->rationalOne,$this->complexTwo,10.4, $this->rationalHalf]]);
        $test = <<<EOF
+--------------------+
|    1    2 10.4  0.5|
+--------------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_FLOAT]));

    }

    public function testFormatRationalReturnsRationalsWithBaseMatrix()
    {
        $mA = new Matrix([[$this->rationalHalf,$this->complexTwo,10.4]]);
        $test = <<<EOF
+---------------+
|  1/2    2 52/5|
+---------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_RATIONAL]));

    }

    public function testFormatComplexReturnsComplexWithBaseMatrix()
    {
        $mA = new Matrix([[$this->rationalHalf,$this->complexTwo,new FloatType(10.4), $this->complexThree, '2+3i']]);
        $test = <<<EOF
+-----------------------------------+
|    1/2      2   52/5 3-3/2i   2+3i|
+-----------------------------------+

EOF;
        $this->assertEquals($test, $this->object->format($mA, ['outputType' => AsciiNumeric::TP_COMPLEX]));

    }

    /**
     * @dataProvider outputType
     * @expectedException Chippyash\Type\Exceptions\NotRealComplexException
     * @expectedExceptionMessage Not a Real complex type
     */
    public function testFormatWithNonRealComplexThrowsException($outputType)
    {
        $mA = new Matrix([[$this->complexThree]]);
        $this->object->format($mA, ['outputType' => $outputType]);

    }

    public function outputType()
    {
        return [
            //[AsciiNumeric::TP_NONE], //no exception as no conversion done
            [AsciiNumeric::TP_INT],
            [AsciiNumeric::TP_FLOAT],
            [AsciiNumeric::TP_RATIONAL],
            //[AsciiNumeric::TP_COMPLEX] //no exception as we are formatting as complex
        ];
    }
}
