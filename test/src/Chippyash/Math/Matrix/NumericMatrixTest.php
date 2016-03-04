<?php

namespace Chippyash\Test\Math\Matrix;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\Number\FloatType;
use Chippyash\Type\Number\WholeIntType;
use Chippyash\Type\Number\NaturalIntType;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Type\RequiredType;
use Chippyash\Type\TypeFactory;

/**
 * Unit test for NumericMatrix Class
 */
class NumericMatrixTest extends \PHPUnit_Framework_TestCase
{

    const NSUT = 'Chippyash\Math\Matrix\NumericMatrix';

    /**
     * @var Matrix
     */
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage NumericMatrix expects NumericMatrix or array as source data
     */
    public function testConstructWithBaseMatrixThrowsException()
    {
        $this->object = new NumericMatrix(new Matrix([]));
    }

    public function testConstructWithNumericMatrixIsAllowed()
    {
        $this->assertInstanceOf(self::NSUT, new NumericMatrix(new NumericMatrix([])));
    }

    public function testConstructNonEmptyArrayGivesNonEmptyMatrix()
    {
        $this->object = new NumericMatrix(array(2));
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    public function testConstructSingleItemArrayGivesSingleItemMatrix()
    {
        $test = [1];
        $expected = [[TypeFactory::createInt(1)]];

        $this->object = new NumericMatrix($test);
        $this->assertEquals($expected, $this->object->toArray());
    }

    /**
     * @dataProvider completeArrays
     */
    public function testConstructWithGoodArraysGivesNumericMatrix($testArray)
    {
        $mA = new NumericMatrix($testArray);
        $this->assertInstanceOf(self::NSUT, $mA);
    }

    /**
     *
     * @return array [[testArray], ...]
     */
    public function completeArrays()
    {
        return [
            [[]], //shorthand empty array
            [[[]]], //longhand empty array
            [[1]], //shorthand single vertice array
            [[[1]]], //longhand single vertice array
            [[[1, 2], [2, 1]]], //even number array
            [[[1.12, 2, 3], [3, 2, 1], [2, 1, 3]]], //odd number array
        ];
    }

    /**
     * @dataProvider differentNumberTypeArrays
     */
    public function testConstructWithDifferentNumberTypesGivesNumericMatrix($testArray, $expectedArray)
    {
        $this->object = new NumericMatrix($testArray);
        $this->assertEquals($expectedArray, $this->object->toArray());
    }

    /**
     *
     * @return array [[testArray, expectedArray], ...]
     */
    public function differentNumberTypeArrays()
    {
        return [
            [[1], [[TypeFactory::createInt(1)]]], //php ints cast to IntType
            [[2.5], [[TypeFactory::createRational(TypeFactory::createInt(5),TypeFactory::createInt(2))]]], //php floats cast to RationalType
            [[TypeFactory::createInt(1)], [[TypeFactory::createInt(1)]]],
            [[new FloatType(2.3)], [[new FloatType(2.3)]]],
            [[new WholeIntType(1)], [[new WholeIntType(1)]]],
            [[new NaturalIntType(1)], [[new NaturalIntType(1)]]],
            [[TypeFactory::createRational(TypeFactory::createInt(5),TypeFactory::createInt(2))],[[TypeFactory::createRational(TypeFactory::createInt(5),TypeFactory::createInt(2))]]],
        ];
    }

    /**
     * @dataProvider nonCompleteArrays
     */
    public function testConstructGivesNormalizedMatrix($testArray, $expectedArray)
    {
        $this->object = new NumericMatrix($testArray, 1);
        $this->assertEquals($expectedArray, $this->object->toArray());
    }

    /**
     *
     * @return array [[$testArray, $expectedArray],...]
     */
    public function nonCompleteArrays()
    {
        return [
            [[], [[]]], //empty array
            [[2], [[TypeFactory::createInt(2)]]], //single vertice
            [[[2, 1], [2]], [[TypeFactory::createInt(2), TypeFactory::createInt(1)], [TypeFactory::createInt(2), TypeFactory::createInt(1)]]], //missing X2.Y2
            [[[2], [2, 1]], [[TypeFactory::createInt(2), TypeFactory::createInt(1)], [TypeFactory::createInt(2), TypeFactory::createInt(1)]]], //missing X1.Y2
            [[[], [2, 1]], [[TypeFactory::createInt(1), TypeFactory::createInt(1)], [TypeFactory::createInt(2), TypeFactory::createInt(1)]]], //missing X1.Y1, X1.Y2
            [[[2, 1], []], [[TypeFactory::createInt(2), TypeFactory::createInt(1)], [TypeFactory::createInt(1), TypeFactory::createInt(1)]]], //missing X2.Y1, X2.Y2
        ];
    }

    public function testComputeReturnsCorrectResult()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $three = TypeFactory::createInt(3);
        $four = TypeFactory::createInt(4);
        $five = TypeFactory::createInt(5);
        $expectedArray = [[$three, $four, $five], [$five, $four, $three], [$four, $three, $five]];
        $object = new NumericMatrix($testArray);
        $computation = new \Chippyash\Math\Matrix\Computation\Add\Scalar();
        $this->assertEquals($expectedArray, $object->compute($computation, 2)->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid operation name
     */
    public function testInvokeWithBadComputationNameThrowsException()
    {
        $mA = new NumericMatrix([]);
        $mA('foobar');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid number of arguments to invoke method
     */
    public function testInvokeWithMoreThanTwoParameterThrowsException()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $mA = new NumericMatrix($testArray);
        $mA('foo','bar','baz');
    }

    public function testInvokeProxiesToCompute()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $three = TypeFactory::createInt(3);
        $four = TypeFactory::createInt(4);
        $five = TypeFactory::createInt(5);
        $expectedArray = [[$three, $four, $five], [$five, $four, $three], [$four, $three, $five]];
        $object = new NumericMatrix($testArray);
        $this->assertEquals($expectedArray, $object("Add\\Scalar", 2)->toArray());
    }

    public function testInvokeProxiesToDerive()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $object = new NumericMatrix($testArray);
        $this->assertEquals(6, $object("Trace")->get());
    }

    public function testInvokeProxiesToTransform()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $object = new NumericMatrix($testArray);
        $this->assertInstanceOf(self::NSUT, $object("Invert"));
    }

    public function testInvokeProxiesToDecompose()
    {
        $object = new NumericMatrix([1]);
        $this->assertInstanceOf(
                'Chippyash\Math\Matrix\Interfaces\DecompositionInterface',
                $object('Lu'));
    }

    public function testInvokeProxiesToParentClassTransform()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $object = new NumericMatrix($testArray);
        $this->assertInstanceOf(self::NSUT, $object("Reflect", 0));
    }

    public function testConstructWithIncompleteDataAndFloatDefaultReturnsMatrix()
    {
        $testArray = [[2, 1], []];
        $mA = new NumericMatrix($testArray, 12.3);
        $this->assertInstanceOf(self::NSUT, $mA);
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage NumericMatrix expects numeric default value
     */
    public function testConstructWithIncompleteDataAndNonNumericDefaultThrowsException()
    {
        $testArray = [[2, 1], []];
        $mA = new NumericMatrix($testArray, new \stdClass());
    }

    public function testConstructWithIncompleteDataAndNumericTypeDefaultReturnsMatrix()
    {
        $testArray = [[2, 1], []];
        $mA = new NumericMatrix($testArray, new FloatType(12.3));
        $this->assertInstanceOf(self::NSUT, $mA);
    }

    public function testTestMethodAcceptsKnownAttributeClassName()
    {
        $attrName = 'Numeric';
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $mA = new NumericMatrix($testArray);
        $this->assertTrue($mA->test($attrName));

    }

    public function testTestMethodWIllPassUnknownAttributeClassToParentForResolution()
    {
        $attr = new \Chippyash\Matrix\Attribute\IsSquare();
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $mA = new NumericMatrix($testArray);
        $this->assertTrue($mA->test($attr));
    }

    public function testDeriveWillReturnValue()
    {
        $derivative = new \Chippyash\Math\Matrix\Derivative\Determinant();
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $mA = new NumericMatrix($testArray);
        $this->assertEquals(-12, $mA->derive($derivative)->get());
    }

    public function testTransformWillReturnValue()
    {
        $transformation = new \Chippyash\Math\Matrix\Transformation\Invert();
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $mA = new NumericMatrix($testArray);
        $this->assertInstanceOf(self::NSUT, $mA->transform($transformation));
    }

    public function testDecomposeReturnsDecomposition()
    {
        $mock = $this->getMock('Chippyash\Math\Matrix\Interfaces\DecompositionInterface');
        $mock->expects($this->once())
                ->method('decompose')
                ->will($this->returnValue($mock));
        $mA = new NumericMatrix([]);
        $this->assertEquals($mock, $mA->decompose($mock));
    }

    public function testEqualityWithStrictSettingReturnsTrueForSameClassAndContent()
    {
        $mN = new NumericMatrix([[2]]);

        $this->assertTrue($mN->equality($mN));
    }

    public function testEqualityWithStrictSettingReturnsFalseForDifferentClassAndSameContent()
    {
        $mN = new NumericMatrix([[2]]);
        $mR = new RationalMatrix([[2]]);

        $this->assertFalse($mN->equality($mR));
    }

    public function testEqualityWithStrictSettingReturnsFalseForSameClassAndDifferentContent()
    {
        $mN = new RationalMatrix([[3]]);
        $mR = new RationalMatrix([[2]]);

        $this->assertFalse($mN->equality($mR));
    }

    public function testEqualityWithLooseSettingReturnsTrueForSameClassAndContent()
    {
        $mN = new NumericMatrix([[2]]);

        $this->assertTrue($mN->equality($mN, false));
    }

    public function testEqualityWithLooseSettingReturnsTrueForDifferentClassAndSameContent()
    {
        $mN = new NumericMatrix([[2]]);
        $mR = new RationalMatrix([[2]]);

        $this->assertTrue($mN->equality($mR, false));
    }

    public function testEqualityWithLooseSettingReturnsFalseForSameClassAndDifferentContent()
    {
        $mA = new NumericMatrix([[12]]);
        $mB = new NumericMatrix([[2]]);

        $this->assertFalse($mA->equality($mB, false));
    }

    public function testEqualityWithLooseSettingReturnsFalseForDifferentClassAndDifferentContent()
    {
        $mN = new NumericMatrix([[2]]);
        $mR = new RationalMatrix([[12]]);

        $this->assertFalse($mN->equality($mR, false));
    }


}
