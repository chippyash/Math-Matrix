<?php
namespace Chippyash\Test\Math\Matrix\Exceptions;
use Chippyash\Math\Matrix\Exceptions;

/**
 * Unit test for alll Exception Classes
 */
class ExceptionsTest extends \PHPUnit_Framework_TestCase
{

    protected $exceptions = array();

    public function setUp()
    {
        $this->exceptions = array(
            'Computation' => new Exceptions\ComputationException('foo'),
            'NoInverse' => new Exceptions\NoInverseException('foo'),
            'UndefinedComputation' => new Exceptions\UndefinedComputationException('foo'),
            'MathMatrix' => new Exceptions\MathMatrixException('foo'),
            'SingularMatrix' => new Exceptions\SingularMatrixException('foo'),
            'NotMarkovException' => new Exceptions\NotMarkovException('foo'),
        );
    }

    /**
     *
     * @param \Exception $ex
     */
    public function testExceptionsDerivedFromComputationException()
    {
        foreach ($this->exceptions as $ex) {
            $this->assertInstanceOf('Chippyash\Math\Matrix\Exceptions\MathMatrixException', $ex);
        }

        $base = new Exceptions\MathMatrixException();
        $this->assertInstanceOf('\Chippyash\Matrix\Exceptions\MatrixException', $base);
    }


}
