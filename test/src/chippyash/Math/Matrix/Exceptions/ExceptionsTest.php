<?php
namespace chippyash\Test\Math\Matrix\Exceptions;
use chippyash\Math\Matrix\Exceptions;

/**
 * Unit test for alll Exception Classes
 */
class ExceptionsTest extends \PHPUnit_Framework_TestCase
{

    protected $exceptions = array();

    public function setUp()
    {
        $this->exceptions = array(
            'Matrix' => new Exceptions\MatrixException('foo'),
            'Computation' => new Exceptions\ComputationException('foo'),
            'FormatterNotSet' => new Exceptions\FormatterNotSetException('foo'),
            'NoInverse' => new Exceptions\NoInverseException('foo'),
            'NotAnAttributeInterface' => new Exceptions\NotAnAttributeInterfaceException('foo'),
            'NotCompleteMatrix' => new Exceptions\NotCompleteMatrixException(2),
            'UndefinedComputation' => new Exceptions\UndefinedComputationException('foo'),
            'VerticeNotFound' => new Exceptions\VerticeNotFoundException(2,2),
            'VerticeOutOfBounds' => new Exceptions\VerticeOutOfBoundsException('foo', 2)
        );
    }

    /**
     *
     * @param \Exception $ex
     */
    public function testExceptionsDerivedFromComputationException()
    {
        foreach ($this->exceptions as $ex) {
            $this->assertInstanceOf('chippyash\Matrix\Exceptions\MatrixException', $ex);
        }
    }


}
