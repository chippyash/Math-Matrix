<?php
namespace Chippyash\Test\Math\Matrix\Computation\Sub;
use Chippyash\Math\Matrix\Computation\Sub\Matrix as CMatrix;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\RequiredType;

/**
 * Description of MatrixTest
 *
 * @author akitson
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new CMatrix();
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter is not a matrix
     */
    public function testComputeRejectsSecondParamNotBeingMatrix()
    {
        $m = new NumericMatrix(array());
        $p = 'foo';
        $this->object->compute($m, $p);
    }

    public function testComputeOnlyAcceptsMatrixValues()
    {
        $m = new NumericMatrix(array());
        $this->assertInstanceOf('Chippyash\Matrix\Matrix', $this->object->compute($m, $m));
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new NumericMatrix(array());
        $test = $this->object->compute($m, new NumericMatrix(array(1)));
        $this->assertTrue($test->is('empty'));
        $test = $this->object->compute(new NumericMatrix(array(1)), $m);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage mA->cols != mB->cols
     */
    public function testComputeThrowsExceptionIfBothOperandsNotSameSize()
    {
        $mA = new NumericMatrix(array(1));
        $mB = new NumericMatrix(array(array(1,2),array(2,1)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage NumberToNumeric expects int, float, string or Rational
     * @dataProvider nonScalarValues
     */
    public function testComputeThrowsExceptionIfFirstOperandVerticeNotScalar($nonScalar)
    {
        $mA = $mB = new NumericMatrix(array($nonScalar));
        $mB = new NumericMatrix(array(array(1,)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage NumberToNumeric expects int, float, string or Rational
     * @dataProvider nonScalarValues
     */
    public function testComputeThrowsExceptionIfSecondOperandVerticeNotScalar($nonScalar)
    {
        $mA = new NumericMatrix([[1]]);
        $mB = new NumericMatrix(array($nonScalar));
        $this->object->compute($mA, $mB);
    }

    public function nonScalarValues()
    {
        return array(
            array([[[]]]),  //array
            array(new \stdClass()), //object
            array(tmpfile())        //resource
        );
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($op1, $op2, $test)
    {
        $mA = new NumericMatrix($op1);
        $mB = new NumericMatrix($op2);
        $mT = new NumericMatrix($test);
        $this->assertEquals($mT, $this->object->compute($mA, $mB));
    }

    public function computeMatrices()
    {
        return array(
            array(
                array(array(1,2,3)),
                array(array(1,2,3)),
                array(array(0,0,0))
            ),
              array(
                array(array(-1.12,2.12,3.12)),
                array(array(-1.12,2.12,3.12)),
                array(array(0.0,0.0,0.0)),
            ),
            array(
                array(array(true,false,true,false)),
                array(array(true,false,false,true)),
                array(array(0,0,1,-1)),
            ),
        );
    }

}
