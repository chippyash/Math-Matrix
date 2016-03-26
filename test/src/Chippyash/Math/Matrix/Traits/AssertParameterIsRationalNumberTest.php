<?php
namespace Chippyash\Test\Math\Matrix\Traits;
use Chippyash\Math\Matrix\Traits\AssertParameterIsRationalNumber;
use Chippyash\Type\RequiredType;
use Chippyash\Type\TypeFactory;

class stubTraitAssertParameterIsRationalNumber
{
    use AssertParameterIsRationalNumber;

    public function test($param, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertParameterIsRationalNumber($param)
                : $this->assertParameterIsRationalNumber($param, $msg);
    }
}

class AssertParameterIsRationalNumberTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new stubTraitAssertParameterIsRationalNumber();
    }

    public function testTestingARationalMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'Chippyash\Test\Math\Matrix\Traits\stubTraitAssertParameterIsRationalNumber',
                $this->object->test(TypeFactory::createRational(2)));
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\ComputationException
     */
    public function testTestingANonRationalNumberThrowsAnException()
    {
        $this->object->test('foo');
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\ComputationException
     */
    public function testTestingANonRationalNumberThrowsAnExceptionWithUserMessage()
    {
        $this->object->test('foo', 'bar');
    }
}
