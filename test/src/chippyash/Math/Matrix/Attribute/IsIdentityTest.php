<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsIdentity;
use chippyash\Math\Matrix\Matrix;

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

    /**
     * @covers chippyash\Matrix\Attribute\IsIdentity::is()
     */
    public function testNonSquareMatrixCanNeverBeAnIdentity()
    {
        $testBad = array(array(1,0,0), array(0,1,0));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsIdentity::is()
     */
    public function testNonIntegerMatrixCanNeverBeAnIdentity()
    {
        $testBad = array(array(1,0,0), array(0,1,0), array(0,0,1.0));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsIdentity::is()
     */
    public function testIsIdentityRecognisesAnIdentityMatrix()
    {
        $testGood = array(array(1,0,0), array(0,1,0), array(0,0,1));
        $mA = new Matrix($testGood);
        $this->assertTrue($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsIdentity::is()
     */
    public function testMatrixHasNonZeroInWrongPlaceIsNotAnIdentityMatrix()
    {
        $testBad = array(array(1,0,2), array(0,1,0), array(0,0,1));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsIdentity::is()
     */
    public function testMatrixHasNonOneInWrongPlaceIsNotIdentityMatrix()
    {
        $testBad = array(array(2,0,0), array(0,1,0), array(0,0,1));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }
}
