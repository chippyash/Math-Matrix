<?php
namespace chippyash\Test\Math\Matrix\Decomposition;
use chippyash\Math\Matrix\Decomposition\Lu;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Type\Number\Rational\RationalTypeFactory as RTF;

class LuTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $testArray = array(array(1,2,3),array(4,5,6),array(7,8,9));

    protected function setUp()
    {
        $this->object = new Lu();
    }

    /**
     * @dataProvider luData
     *
     */
    public function testDecomposeReturnsCorrectResult(
            $source, $LUD, $LD, $UD, $pivotVectorD, $permutationMatrixD, $det)
    {
        $mA = new RationalMatrix($source);
        $LU = new RationalMatrix($LUD);
        $L = new RationalMatrix($LD);
        $U = new RationalMatrix($UD);
        $pivotVector = new RationalMatrix($pivotVectorD);
        $permutationMatrix = new RationalMatrix($permutationMatrixD);

        $decomp = $this->object->decompose($mA);
//        var_dump($decomp->Det);return;
        $this->assertEquals($LU, $this->object->LU, 'LU matrix incorrect');
        $this->assertEquals($L, $this->object->L, 'L matrix incorrect');
        $this->assertEquals($U, $this->object->U, 'U matrix incorrect');
        $this->assertEquals($pivotVector, $this->object->PivotVector, 'PivotVector matrix incorrect');
        $this->assertEquals($permutationMatrix, $this->object->PermutationMatrix, 'Permutation matrix incorrect');
        $det = (is_null($det) ? null : RTF::create($det));
        $this->assertEquals($det, $this->object->Det, 'Determinant incorrect');
    }

    /**
     * return array [[source, LU, L, U, pivot, perm],...]
     */
    public function luData()
    {
        return [
            //square 3x3
            [
                //source
                [
                    [1,2,3],
                    [4,5,6],
                    [7,8,9]],
                //LU
                [
                    [7,8,9],
                    ['1/7','6/7','12/7'],
                    ['4/7','1/2',0]
                ],
                //L
                [
                    [1,0,0],
                    ['1/7', 1, 0],
                    ['4/7', '1/2', 1]
                ],
                //U
                [
                    [7, 8, 9],
                    [0, '6/7', '12/7'],
                    [0, 0, 0],
                ],
                //pivot
                [
                    [3,1,2]
                ],
                //perm
                [
                    [0,1,0],
                    [0,0,1],
                    [1,0,0],
                ],
                //det
                0
            ],
            //square 4x4
            [
                //source
                [
                    [2, 15, -5, 4],
                    [1, -3, 6, 16],
                    [21, -12, 45, 3],
                    [1, 6, 4, -6]
                    ],
                //LU
                [
                    [21, -12, 45, 3],
                    ['2/21', '113/7', '-65/7', '26/7'],
                    ['1/21', '46/113', '637/113', '-865/113'],
                    ['1/21', '-119/791', '278/637', '12585/637']
                ],
                //L
                [
                    [1, 0, 0, 0],
                    ['2/21', 1, 0, 0],
                    ['1/21', '46/113', 1, 0],
                    ['1/21', '-119/791', '278/637',  1]
                ],
                //U
                [
                    [21, -12, 45, 3],
                    [0, '113/7', '-65/7',  '26/7'],
                    [0, 0, '637/113', '-865/113'],
                    [0, 0, 0, '12585/637']
                ],
                //pivot
                [
                    [3,1,4,2]
                ],
                //perm
                [
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                    [1, 0, 0, 0,],
                    [0, 0, 1, 0]
                ],
                //det
                -37755
            ],
            //rectangle 3x4
            [
                //source
                [
                    [2, 15, -5, 4],
                    [1, -3, 6, 16],
                    [21, -12, 45, 3],
                ],
                //LU
                [
                    [21, -12, 45, 3],
                    ['2/21', '113/7', '-65/7', '26/7'],
                    ['1/21', '-119/791', '278/113', '1855/113'],
                ],
                //L
                [
                    [1, 0, 0],
                    ['2/21', 1, 0],
                    ['1/21', '-119/791', 1],
                ],
                //U
                [
                    [21, -12, 45, 3],
                    [0, '113/7', '-65/7', '26/7'],
                    [0, 0, '278/113', '1855/113'],
                ],
                //PivotVector
                [
                    [3, 1, 2]
                ],
                //Permutation
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
                //det
                null
            ],
            //rectangle 4x3
            [
                //source
                [
                    [2, 15, -5],
                    [1, -3, 6],
                    [21, -12, 45],
                    [1, 6, 4]
                    ],
                //LU
                [
                    [21, -12, 45],
                    ['2/21', '113/7', '-65/7'],
                    ['1/21', '46/113', '637/113'],
                    ['1/21', '-119/791', '278/637']
                ],
                //L
                [
                    [ 1, 0, 0],
                    ['2/21', 1, 0],
                    ['1/21', '46/113', 1],
                    ['1/21', '-119/791', '278/637'],
                ],
                //U
                [
                    [21, -12, 45],
                    [0, '113/7', '-65/7'],
                    [0, 0, '637/113'],
                ],
                //pivot
                [
                    [3,1,4,2]
                ],
                //perm
                [
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                    [1, 0, 0, 0,],
                    [0, 0, 1, 0]
                ],
                //det
                null
            ],
            //row matrix
            [
                //source
                [
                    [2,15,-5,14]
                ],
                //LU
                [
                    [2,15,-5,14]
                ],
                //L
                [
                    [1]
                ],
                //U
                [
                    [2,15,-5,14]
                ],
                //pivot
                [
                    [1]
                    ],
                //perm
                [
                    [1]
                    ],
                //det
                null
            ],
            //col matrix
            [
                //source
                [
                    [2],
                    [15],
                    [-5],
                    [14]
                ],
                //LU
                [
                    [15.0],
                    ['2/15'],
                    ['-5/15'],
                    ['14/15']
                    ],
                //L
                [
                    [1],
                    ['2/15'],
                    ['-5/15'],
                    ['14/15']
                ],
                //U
                [
                    [15]
                ],
                //pivot
                [
                    [2,1,3,4]
                    ],
                //perm
                [
                    [0, 1, 0, 0],
                    [1, 0, 0, 0],
                    [0, 0, 1, 0,],
                    [0, 0, 0, 1]
                    ],
                //det
                null
            ]

        ];
    }
}
