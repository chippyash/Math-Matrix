<?php

namespace Chippyash\Test\Math\Matrix\Computation\Mul;

use Chippyash\Math\Matrix\Computation\Mul\Entrywise as CMatrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\RequiredType;
use Chippyash\Type\TypeFactory;

class EntrywiseTest extends \PHPUnit_Framework_TestCase
{

    protected $object;
    protected $empty;
    protected $rowVector;
    protected $colVector;
    protected $square;
    protected $single;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new CMatrix();
        $this->empty = new NumericMatrix([]);
        $this->rowVector = new NumericMatrix(
                [
            [1, 2, 3]]);
        $this->colVector = new NumericMatrix(
                [
            [1],
            [2],
            [3]]);
        $this->square = new NumericMatrix(
                [
            [1, 2, 3],
            [1, 2, 3],
            [1, 2, 3]]);
        $this->single = new NumericMatrix([1]);
    }

    /**
     * @expectedException \Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter is not a matrix
     */
    public function testComputeRejectsSecondParamNotBeingMatrix()
    {
        $this->object->compute($this->empty, 'foo');
    }

    public function testComputeOnlyAcceptsMatrixValues()
    {
        $this->assertInstanceOf(
                '\Chippyash\Math\Matrix\NumericMatrix', $this->object->compute(
                        $this->empty, $this->empty));
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $test = $this->object->compute($this->empty, new NumericMatrix([1]));
        $this->assertTrue($test->is('empty'));
        $test = $this->object->compute(new NumericMatrix([1]), $this->empty);
        $this->assertTrue($test->is('empty'));
    }

    public function testSingleItemMatricesReturnSingleItemProduct()
    {
        $this->assertTrue($this->object->compute($this->single, $this->single)->is('singleitem'));
    }

    public function testSquareXSquareReturnsSquareMatrix()
    {
        $test = $this->object->compute($this->square, $this->square);
        $this->assertTrue($test->is('square'));
        $this->assertEquals(
                $this->toStrongType([[1, 4, 9], [1, 4, 9], [1, 4, 9]]),
                $test->toArray());
    }

    /**
     * @expectedException \Chippyash\Matrix\Exceptions\MatrixException
     */
    public function testMultiplyingTwoMatricesThatAreNotTheSameShapeWillThrowAnException()
    {
        $this->object->compute($this->colVector, $this->rowVector);
    }

    private function toStrongType(array $values)
    {
        $ret = [];
        foreach ($values as $r => $row) {
            foreach ($row as $c => $item) {
                $ret[$r][$c] = TypeFactory::createInt($item);
            }
        }
        return $ret;
    }
}
