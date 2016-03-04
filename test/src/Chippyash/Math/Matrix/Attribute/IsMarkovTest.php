<?php
/**
 * Markov
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Test\Math\Matrix\Attribute;


use Chippyash\Math\Matrix\Attribute\IsMarkov;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\RequiredType;

class IsMarkovTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var IsMarkov
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new IsMarkov();
    }

    public function testANonNumericMatrixWillReturnFalse()
    {
        $this->assertFalse($this->sut->is(new Matrix([['A']])));
    }

    public function testANotSquareMatrixWillReturnFalse()
    {
        $this->assertFalse($this->sut->is(new Matrix([[1, 2]])));
    }

    public function testAttributeReturnsTrueForAMatrixThatHasRowsThatSumToTheSameNumber()
    {
        $mA = new NumericMatrix([[3, 0],[0, 3]]);
        $this->assertTrue($this->sut->is($mA));
    }

    public function testAttributeReturnsFalseForAMatrixThatHasRowsThatDoNotSumToTheSameNumber()
    {
        $mA = new NumericMatrix([[5, 6, 3],[1, 0, 2], [1, 1, 1]]);
        $this->assertFalse($this->sut->is($mA));
    }

    public function testAMatrixWithFewerThanTwoRowsAndTwoColumnsIsNotAMarkovChain()
    {
        $this->assertFalse($this->sut->is(new NumericMatrix([1])));
        $this->assertFalse($this->sut->is(new NumericMatrix([1, 2])));
        $this->assertFalse($this->sut->is(new NumericMatrix([[1],[1]])));
    }
}
