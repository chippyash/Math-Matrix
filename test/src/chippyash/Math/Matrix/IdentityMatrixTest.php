<?php
namespace chippyash\Test\Math\Matrix;
use chippyash\Math\Matrix\IdentityMatrix;

/**
 * Unit test for IdentityMatrix Class
 */
class IdentityMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'chippyash\Matrix\IdentityMatrix';

    public function testConstructProperlyGivesIdentityMatrix()
    {

        $mA = new IdentityMatrix(2);
        $this->assertInstanceOf(self::NSUT, $mA);
        $this->assertFalse($mA->is('empty'));
        $this->assertTrue($mA->is('square'));
        $this->assertEquals(
                array(
                    array(1,0),
                    array(0,1)),
                $mA->toArray());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $size must be int >= 1
     */
    public function testConstructSizeNotIntRaisesException()
    {
        $this->object = new IdentityMatrix(1.123, 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $size must be int >= 1
     */
    public function testConstructSizelessThanOneRaisesException()
    {
        $this->object = new IdentityMatrix(0);
    }

}
