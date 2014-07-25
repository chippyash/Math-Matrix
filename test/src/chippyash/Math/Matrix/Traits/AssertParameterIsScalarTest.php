<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertParameterIsScalar;

class stubTraitAssertParameterIsScalar
{
    use AssertParameterIsScalar;

    public function test($param, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertParameterIsScalar($param)
                : $this->assertParameterIsScalar($param, $msg);
    }
}

class AssertParameterIsScalarTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitAssertParameterIsScalar();
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertParameterIsScalar::assertParameterIsScalar
     * @dataProvider scalarData
     */
    public function testScalarParamReturnsClass($scalar)
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertParameterIsScalar',
                $this->object->test($scalar));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not scalar!
     * @covers chippyash\Matrix\Traits\AssertParameterIsScalar::assertParameterIsScalar
     * @dataProvider nonscalarData
     */
    public function testNonScalarParamThrowsException($nonScalar)
    {
        $this->object->test($nonScalar);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertParameterIsScalar::assertParameterIsScalar
     * @dataProvider nonscalarData
     */
    public function testNonScalarParamThrowsExceptionWithUserMessage($nonScalar)
    {
        $this->object->test($nonScalar, 'foo');
    }

    /**
     * Scalar types
     * @see http://www.php.net/manual/en/function.is-scalar.php
     * @return mixed [[scalar],...]
     */
    public function scalarData()
    {
        return [
            [2],        //int
            [1.4],      //real
            [true],     //bool
            ['foo'],    //string
            [''],       //empty string
        ];
    }

    /**
     * Non Scalar types
     * @see http://www.php.net/manual/en/function.is-scalar.php
     * @return mixed [[nonscalar],...]
     */
    public function nonscalarData()
    {
        return [
            [tmpfile()],        //resource
            [null],             //null
            [new \stdClass()],  //object
            [[]]                //array
        ];
    }
}
