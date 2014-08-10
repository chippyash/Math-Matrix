<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsIdentity;
use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Math\Matrix\MatrixFactory;

/**
 */
class IsIdentityTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsIdentity();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    public function testNonNumericMatrixCanNeverBeAnIdentityMatrix()
    {
        $testBad = [[1,0,0], [0,1,0]];
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    public function testNumericMatrixCanBeAnIdentityMatrix()
    {
        $test = [[1,0,0.0], [0,1,0], [0,0,1.0]];
        $mA = new NumericMatrix($test);
        $this->assertTrue($this->object->is($mA));
    }

    public function testMatrixHasNonZeroInWrongPlaceIsNotAnIdentityMatrix()
    {
        $testBad = [[1,0,2], [0,1,0], [0,0,1]];
        $mA = new NumericMatrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    public function testMatrixHasNonOneInWrongPlaceIsNotIdentityMatrix()
    {
        $testBad = [[2,0,0], [0,1,0], [0,0,1]];
        $mA = new NumericMatrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    public function testComplexNumberIdentityMatrixIsRecognised()
    {
        $test = [['1+0i','0+0i','0+0i'],['0+0i','1+0i','0+0i'],['0+0i','0+0i','1+0i']];
        $mA = MatrixFactory::createComplex($test);
        $this->assertTrue($this->object->is($mA));
    }

    public function testComplexNumberNonIdentityMatrixIsRecognised()
    {
//        $test1 = [['0+0i','0+0i','0+0i'],['0+0i','1+0i','0+0i'],['0+0i','0+0i','1+0i']];
//        $mA = MatrixFactory::createComplex($test1);
//        $this->assertFalse($this->object->is($mA));

        $test2 = [['0+3i','0+0i','0+0i'],['0+0i','1+0i','0+0i'],['0+0i','0+0i','1+0i']];
        $mA = MatrixFactory::createComplex($test2);
        $this->assertFalse($this->object->is($mA));
    }

    public function testRationalNumberIdentityMatrixIsRecognised()
    {
        $test = [['1/1','0/1','0/1'],['0/1','1/1','0/1'],['0/1','0/1','1/1']];
        $mA = MatrixFactory::createRational($test);
        $this->assertTrue($this->object->is($mA));
    }

    public function testRationalNumberNonIdentityMatrixIsRecognised()
    {
        $test = [['0/1','0/1','0/1'],['0/1','1/1','0/1'],['0/1','0/1','1/1']];
        $mA = MatrixFactory::createRational($test);
        $this->assertFalse($this->object->is($mA));
    }

}
