<?php

namespace chippyash\Test\Math\Matrix;

use chippyash\Math\Matrix\RationalMatrix;

/**
 * Unit test for RationalMatrix Class
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{

    const NSUT = 'chippyash\Math\Matrix\RationalMatrix';

    /**
     * @var Matrix
     */
    protected $object;

    public function testConstructWithMatrixGivesRationalMatrix()
    {
        $this->object = new RationalMatrix(new \chippyash\Matrix\Matrix([]));
        $this->assertInstanceOf(self::NSUT, $this->object);
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     */
    public function testConstructNonEmptyArrayGivesNonEmptyMatrix()
    {
        $this->object = new RationalMatrix(array(2));
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     */
    public function testConstructSingleItemArrayGivesSingleItemMatrix()
    {
        $test = array(1);
        $expected = array($test);

        $this->object = new RationalMatrix($test);
        $this->assertEquals($expected, $this->object->toArray());
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     * @covers chippyash\Matrix\Matrix::enforceCompleteness()
     * @dataProvider completeArrays
     */
    public function testConstructEnforcingCompletenessWithGoodArraysGivesMatrix($testArray)
    {
        $mA = new RationalMatrix($testArray, true);
        $this->assertInstanceOf(self::NSUT, $mA);
    }

    /**
     *
     * @return array [[testArray], ...]
     */
    public function completeArrays()
    {
        return array(
            array([]), //shorthand empty array
            array(array([])), //longhand empty array
            array(array(1)), //shorthand single vertice array
            array(array(array(1))), //longhand single vertice array
            array(array(array(1, 2), array(2, 1))), //even number array
            array(array(array(1.12, 2, 3), array(3, 2, 1), array(2, 1, 3))), //odd number array
        );
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     * @covers chippyash\Matrix\Matrix::enforceCompleteness()
     * @dataProvider nonCompleteArrays
     */
    public function testConstructEnforcingCompletenessWithNonCompleteArraysRaisesException($testArray, $invalidRow)
    {
        $this->setExpectedException(
                'chippyash\Matrix\Exceptions\NotCompleteMatrixException', sprintf('Matrix is not complete in row %d', $invalidRow));
        $this->object = new RationalMatrix($testArray, true);
    }

    /**
     *
     * @return array [[testArray], ...]
     */
    public function nonCompleteArrays()
    {
        return array(
            array(array(array(1), array(2, 1)), 1), //2nd row invalid
            array(array(array(1, 2), array(2)), 1), //2nd row invalid
            array(array(array(1, 2, 3), [], array(3, 2, 1)), 1), //2nd row invalid
            array(array(array(1, 2, 3), array(3, 2, 1), array(2, 1)), 2), //3rd row invalid
        );
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     * @dataProvider nonCompleteArraysForNormalization
     */
    public function testConstructForcingNormalizationNoCompletenessNoRationalisationGivesNormalizedMatrix($testArray, $expectedArray)
    {
        $this->object = new RationalMatrix($testArray, false, true, false);
        $this->assertEquals($expectedArray, $this->object->toArray());
    }

    /**
     * @dataProvider nonCompleteArraysForNormalization
     */
    public function testConstructForcingNormalizationNoCompletenessPassesCompleteTest($testArray, $expectedArray)
    {
        $this->object = new RationalMatrix($testArray, false, true);
        $this->assertTrue($this->object->is('complete'));
    }

    /**
     *
     * @return array [[$testArray, $expectedArray],...]
     */
    public function nonCompleteArraysForNormalization()
    {
        return array(
            array([], array([])), //empty array
            array(array(2), array(array(2))), //single vertice
            array(array(array(2, 1), array(2)), array(array(2, 1), array(2, null))), //missing X2.Y2
            array(array(array(2), array(2, 1)), array(array(2, null), array(2, 1))), //missing X1.Y2
            array(array([], array(2, 1)), array(array(null, null), array(2, 1))), //missing X1.Y1, X1.Y2
            array(array(array(2, 1), []), array(array(2, 1), array(null, null))), //missing X2.Y1, X2.Y2
        );
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     * @dataProvider incompleteArrays
     */
    public function testConstructNotForcingNormalizationNoCompletenessFailsCompleteTest($testArray)
    {
        $this->object = new RationalMatrix($testArray, false, false, null, false);
        $this->assertFalse($this->object->is('complete'));
    }

    /**
     *
     * @return array [[$testArray],...]
     */
    public function incompleteArrays()
    {
        return array(
            array(array(array(2, 1), array(2))), //missing X2.Y2
            array(array(array(2), array(2, 1))), //missing X1.Y2
            array(array([], array(2, 1))), //missing X1.Y1, X1.Y2
            array(array(array(2, 1), [])), //missing X2.Y1, X2.Y2
        );
    }

    /**
     * @covers chippyash\Matrix\Matrix::__construct()
     * @covers chippyash\Matrix\Matrix::toArray()
     * @dataProvider nonCompleteArraysForNormalizationWithUserDataNoRationalisation
     */
    public function testConstructForcingNormalizationWithUserDataNotCompleteGivesNormalizedMatrix($testArray, $expectedArray)
    {
        $this->object = new RationalMatrix($testArray, false, true, 'foo', false);
        $this->assertEquals($expectedArray, $this->object->toArray());
    }

    /**
     * @return array [[$testArray, $expectedArray],...]
     */
    public function nonCompleteArraysForNormalizationWithUserDataNoRationalisation()
    {
        return array(
            array([], array([])), //empty array
            array(array(2), array(array(2))), //single vertice
            array(array(array(2, 1), array(2)), array(array(2, 1), array(2, 'foo'))), //missing X2.Y2
            array(array(array(2), array(2, 1)), array(array(2, 'foo'), array(2, 1))), //missing X1.Y2
            array(array([], array(2, 1)), array(array('foo', 'foo'), array(2, 1))), //missing X1.Y1, X1.Y2
            array(array(array(2, 1), []), array(array(2, 1), array('foo', 'foo'))), //missing X2.Y1, X2.Y2
        );
    }

    /**
     * @covers chippyash\Matrix\Matrix::rows()
     * @covers chippyash\Matrix\Matrix::columns()
     * @covers chippyash\Matrix\Matrix::vertices()
     * @dataProvider matrixDimensions
     */
    public function testConstructNonCompleteMatrixWithVariousArraysGivesCorrectDimensions($array, $columns, $rows, $vertices)
    {
        $this->object = new RationalMatrix($array);
        $this->assertEquals($rows, $this->object->rows());
        $this->assertEquals($columns, $this->object->columns());
        $this->assertEquals($vertices, $this->object->vertices());
    }

    /**
     * Test Data
     * @return array [[testArray, numColumns, numRows, numVertices], ...]
     */
    public function matrixDimensions()
    {
        return array(
            array([], 0, 0, 0), //empty matrix has no rows or columns
            array(array(1), 1, 1, 1), //shorthand single vertice construction
            array(array(array(1)), 1, 1, 1), //longhand  single vertice construction
            array(array(array(1, 2)), 2, 1, 2), //2 col, 1 row
            array(array(array(1, 2), array(2, 1)), 2, 2, 4), //2 col, 2 row
            array(array(array(1, 2), []), 2, 2, 4), //2 col, 2 row - but missing second row data
            array(array([], array(2, 1)), 0, 2, 0), //2 col, 2 row - but missing first row data
        );
    }

    /**
     * @covers chippyash\Matrix\Matrix::get()
     */
    public function testMatrixGetVerifiesOneBasedMatrix()
    {
        $this->object = new RationalMatrix(array(array(1, 2, 3), array(3, 2, 1), array(2, 1, 3)));
        $this->setExpectedException(
                'chippyash\Matrix\Exceptions\VerticeOutOfBoundsException', "Vertice 'col' is out of bounds with value: 0");
        $this->object->get(1, 0);
        $this->setExpectedException(
                'chippyash\Matrix\Exceptions\VerticeOutOfBoundsException', "Vertice 'row' is out of bounds with value: 0");
        $this->object->get(0, 1);
    }

    /**
     * @covers chippyash\Matrix\Matrix::get()
     */
    public function testMatrixGetVerifiesUpperBoundaryOfMatrix()
    {
        $this->object = new RationalMatrix(array(array(1, 2, 3), array(3, 2, 1), array(2, 1, 3)));
        $this->setExpectedException(
                'chippyash\Matrix\Exceptions\VerticeOutOfBoundsException', "Vertice 'col' is out of bounds with value: 4");
        $this->object->get(1, 4);
        $this->setExpectedException(
                'chippyash\Matrix\Exceptions\VerticeOutOfBoundsException', "Vertice 'row' is out of bounds with value: 4");
        $this->object->get(4, 1);
    }

    /**
     * @covers chippyash\Matrix\Matrix::get()
     */
    public function testMatrixGetErrorsIfVerticeNotFound()
    {
        $this->object = new RationalMatrix(array(array(1, 2, 3), [], []));
        for ($c = 1; $c < 4; $c++) {
            for ($r = 2; $r < 4; $r++) {
                $this->setExpectedException(
                        'chippyash\Matrix\Exceptions\VerticeNotFoundException', "Vertice R({$r}),C({$c}) is not found in the matrix");
                $this->object->get($r, $c);
            }
        }
    }

    /**
     * @covers chippyash\Matrix\Matrix::get()
     */
    public function testMatrixGetReturnsCorrectValue()
    {
        $testArray = array(array(1, 2, 3), array(0, 2, 1), array(2.5, 1, 3));
        $this->object = new RationalMatrix($testArray);
        for ($r = 1; $r < 4; $r++) {
            for ($c = 1; $c < 4; $c++) {
                $this->assertEquals($testArray[$r - 1][$c - 1], $this->object->get($r, $c));
            }
        }
    }

    /**
     * @covers chippyash\Matrix\Matrix::get()
     */
    public function testMatrixGetAndNotDerationalisingReturnsRational()
    {
        $testArray = array([1,0,0.5]);
        $asRational = ['1/1', '0/1', '1/2'];
        $this->object = new RationalMatrix($testArray);
        for ($c = 1; $c < 4; $c++) {
                $item = $this->object->get(1, $c, false);
                $this->assertInstanceOf(
                        'FlorianWolters\Component\Number\Fraction',
                        $item);
                $this->assertEquals($asRational[$c-1], (string) $item);
        }
    }

    /**
     * @covers chippyash\Matrix\Matrix::compute()
     */
    public function testComputeReturnsCorrectResult()
    {
        $testArray = array(array(1, 2, 3), array(3, 2, 1), array(2, 1, 3));
        $expectedArray = array(array(3, 4, 5), array(5, 4, 3), array(4, 3, 5));
        $object = new RationalMatrix($testArray);
        $computation = new \chippyash\Matrix\Computation\Add\Scalar();
        $this->assertEquals($expectedArray, $object->compute($computation, 2)->toArray());
    }

    /**
     * @covers chippyash\Matrix\Matrix::__invoke()
     * @expectedException \InvalidArgumentException
     */
    public function testInvokeWithBadComputationNameThrowsException()
    {
        $mA = new RationalMatrix([]);
        $mA('foobar');
    }

    /**
     * @covers chippyash\Matrix\Matrix::__invoke()
     * @covers chippyash\Matrix\Matrix::compute()
     */
    public function testInvokeProxiesToComputation()
    {
        $testArray = array(array(1, 2, 3), array(3, 2, 1), array(2, 1, 3));
        $expectedArray = array(array(3, 4, 5), array(5, 4, 3), array(4, 3, 5));
        $object = new RationalMatrix($testArray);
        $this->assertEquals($expectedArray, $object("Add\\Scalar", 2)->toArray());
    }

    /**
     * @covers chippyash\Matrix\Matrix::display()
      ;     * @expectedException chippyash\Matrix\Exceptions\FormatterNotSetException
     * @expectedExceptionMessage Formatter not set
     */
    public function testDisplayThrowsExceptionIfNoFormatterSet()
    {
        $mA = new RationalMatrix([]);
        $mA->display();
    }

    /**
     * @covers chippyash\Matrix\Matrix::display()
     * @covers chippyash\Matrix\Matrix::setFormatter()
     */
    public function testDisplayReturnsOutputIfFormatterSet()
    {
        $mA = new RationalMatrix([]);
        $formatter = $this->getMock("\chippyash\Matrix\Interfaces\FormatterInterface");
        $formatter->expects($this->once())
                ->method('format')
                ->will($this->returnValue('foo'));
        $mA->setFormatter($formatter);
        $this->assertEquals('foo', $mA->display());
    }

    /**
     * @covers chippyash\Matrix\Matrix::display()
     * @covers chippyash\Matrix\Matrix::setFormatter()
     */
    public function testDisplayAcceptsOptionsArray()
    {
        $mA = new RationalMatrix([]);
        $formatter = $this->getMock("\chippyash\Matrix\Interfaces\FormatterInterface");
        $formatter->expects($this->once())
                ->method('format')
                ->will($this->returnValue('foo'));
        $mA->setFormatter($formatter);
        $this->assertEquals('foo', $mA->display([]));
    }

    /**
     * @covers chippyash\Matrix\Matrix::display()
     * @covers chippyash\Matrix\Matrix::setFormatter()
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testDisplayRequiresOptionsToBeArray()
    {
        $mA = new RationalMatrix([]);
        $formatter = $this->getMock("\chippyash\Matrix\Interfaces\FormatterInterface");
        $mA->setFormatter($formatter);
        $this->assertEquals('foo', $mA->display('foo'));
    }

    /**
     * @covers chippyash\Matrix\Matrix::is()
     * @covers chippyash\Matrix\Matrix::test()
     */
    public function testIsMethodAcceptsKnownAttributeName()
    {
        $mA = new RationalMatrix([]);
        $this->assertInternalType('boolean', $mA->is('empty'));
    }

    /**
     * @covers chippyash\Matrix\Matrix::is()
     */
    public function testIsMethodReturnsFalseForUnknownAttributeName()
    {
        $mA = new RationalMatrix([]);
        $this->assertFalse($mA->is('foobar'));
    }

    /**
     * @covers chippyash\Matrix\Matrix::is()
     * @covers chippyash\Matrix\Matrix::test()
     */
    public function testIsMethodAcceptsAttributeInterfaceAsParameter()
    {
        $mA = new RationalMatrix([]);
        $attr = $this->getMock('\chippyash\Matrix\Interfaces\AttributeInterface');
        $attr->expects($this->once())
                ->method('is')
                ->will($this->returnValue(true));
        $this->assertTrue($mA->is($attr));
    }

    /**
     * @covers chippyash\Matrix\Matrix::test()
     * @expectedException chippyash\Matrix\Exceptions\NotAnAttributeInterfaceException
     */
    public function testTestMethodThrowsExceptionIfAttributeIsNotInterface()
    {
        $mA = new RationalMatrix([]);
        $mA->test(new \stdClass());
    }

    /**
     * @covers chippyash\Matrix\Matrix::test()
     * @expectedException BadMethodCallException
     */
    public function testTestMethodThrowsExceptionIfParamAsClassCannotBeFound()
    {
        $mA = new RationalMatrix([]);
        $mA->test('foobar');
    }

    public function testConstructingAndNotRationalisingReturnsMatrix()
    {
        $mA = new RationalMatrix([[1, 2], [1, 2]], false, false, null, false);
        $this->assertInstanceOf('chippyash\Matrix\Matrix', $mA);
        $this->assertEquals([[1, 2], [1, 2]], $mA->toArray());
    }

    public function testToArrayAndNotDerationalisingReturnsArrayOfRationals()
    {
        $mA = new RationalMatrix([[1, 2], [1.5, 2.76]]);
        $arr = $mA->toArray(false);
        foreach ($arr as $row) {
            foreach ($row as $item) {
                $this->assertInstanceOf('FlorianWolters\Component\Number\Fraction', $item);
            }
        }
    }

    public function testIsRational()
    {
        $mA = new RationalMatrix([2]);
        $this->assertTrue($mA->isRational());

        $mB = new RationalMatrix([2], false, false, null, false);
        $this->assertFalse($mB->isRational());
    }
}
