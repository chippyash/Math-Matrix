<?php
namespace chippyash\Test\Math\Matrix\Transformation\Decomposition;
use chippyash\Math\Matrix\Transformation\Decomposition\Lu;
use chippyash\Math\Matrix\Matrix;

/**
 * Description of LuTest
 *
 */
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
    public function testTransformReturnsCorrectResult($source, $LU, $L, $U, $pivotVector, $permutationMatrix)
    {
        $mA = new Matrix($source);
        $this->object->transform($mA);
//        echo $this->object->LU->setFormatter(new \chippyash\Matrix\Formatter\Ascii())->display();return;
        $this->assertEquals($LU, $this->object->LU->toArray(), 'LU matrix incorrect');
        $this->assertEquals($L, $this->object->L->toArray(), 'L matrix incorrect');
        $this->assertEquals($U, $this->object->U->toArray(), 'U matrix incorrect');
        $this->assertEquals($pivotVector, $this->object->PivotVector->toArray(), 'PivotVector matrix incorrect');
        $this->assertEquals($permutationMatrix, $this->object->PermutationMatrix->toArray(), 'Permutation matrix incorrect');
    }

    /**
     * return array [[source, LU, L, U, pivot, perm],...]
     */
    public function luData()
    {
        return array(
            //square 3x3
            array(
                //source
                array(
                    array(1,2,3),
                    array(4,5,6),
                    array(7,8,9)),
                //LU
                array(
                    array(7,8,9),
                    array(0.14285714285714, 0.85714285714286, 1.7142857142857),
                    array(0.57142857142857, 0.5, 0)
                ),
                //L
                array(
                    array(1,0,0),
                    array(0.14285714285714, 1, 0),
                    array(0.57142857142857, 0.5, 1)
                ),
                //U
                array(
                    array(7,8,9),
                    array(0, 0.85714285714286, 1.7142857142857),
                    array(0,0,0),
                ),
                //pivot
                array(
                    array(3,1,2)
                ),
                //perm
                array(
                    array(0,1,0),
                    array(0,0,1),
                    array(1,0,0),
                )
            ),
            //square 4x4
            array(
                //source
                array(
                    array(2, 15, -5, 4),
                    array(1, -3, 6, 16),
                    array(21, -12, 45, 3),
                    array(1, 6, 4, -6)
                    ),
                //LU
                array(
                    array(21, -12, 45, 3),
                    array(0.095238095238095, 16.142857142857, -9.2857142857143, 3.7142857142857),
                    array(0.047619047619048, 0.4070796460177, 5.6371681415929, -7.6548672566372),
                    array(0.047619047619048, -0.15044247787611, 0.43642072213501, 19.756671899529)
                ),
                //L
                array(
                    array(1, 0, 0, 0),
                    array(0.09523809523810, 1, 0, 0),
                    array(0.04761904761905, 0.40707964601770, 1.000000000000000, 0),
                    array(0.04761904761905, -0.15044247787611, 0.43642072213501,  1)
                ),
                //U
                array(
                    array(21, -12, 45, 3),
                    array(0, 16.14285714285710, -9.28571428571428,  3.71428571428571),
                    array(0, 0, 5.63716814159292, -7.65486725663717),
                    array(0, 0, 0, 19.756671899529000)
                ),
                //pivot
                array(
                    array(3,1,4,2)
                ),
                //perm
                array(
                    array(0, 1, 0, 0),
                    array(0, 0, 0, 1),
                    array(1, 0, 0, 0,),
                    array(0, 0, 1, 0)
                )
            ),
            //rectangle 3x4
            array(
                //source
                array(
                    array(2, 15, -5, 4),
                    array(1, -3, 6, 16),
                    array(21, -12, 45, 3),
                ),
                //LU
                array(
                    array(21, -12, 45, 3),
                    array(0.095238095238095, 16.142857142857, -9.2857142857143, 3.7142857142857),
                    array(0.047619047619048, -0.15044247787611, 2.4601769911504, 16.41592920354),
                ),
                //L
                array(
                    array(1.0, 0.0, 0.0),
                    array(0.095238095238095, 1.0, 0.0),
                    array(0.047619047619048, -0.15044247787611, 1.0),
                ),
                //U
                array(
                    array(21, -12, 45, 3),
                    array(0, 16.14285714285710, -9.28571428571428, 3.71428571428571),
                    array(0, 0, 2.46017699115044, 16.41592920353980),
                ),
                //PivotVector
                array(
                    array(3, 1, 2)
                ),
                //Permutation
                array(
                    array(0, 1, 0),
                    array(0, 0, 1),
                    array(1, 0, 0),
                ),
            ),
            //rectangle 4x3
            array(
                //source
                array(
                    array(2, 15, -5),
                    array(1, -3, 6),
                    array(21, -12, 45),
                    array(1, 6, 4)
                    ),
                //LU
                array(
                    array(21.0, -12.0, 45.0),
                    array(0.095238095238095, 16.142857142857, -9.2857142857143),
                    array(0.047619047619048, 0.4070796460177, 5.6371681415929),
                    array(0.047619047619048, -0.15044247787611, 0.43642072213501)
                ),
                //L
                array(
                    array( 1.0, 0.0, 0.0),
                    array(0.095238095238095, 1.0, 0.0),
                    array(0.047619047619048, 0.407079646017699, 1.0),
                    array(0.047619047619048, -0.150442477876106, 0.436420722135008),
                ),
                //U
                array(
                    array(21.0, -12.0, 45.0),
                    array(0.0, 16.142857142857100, -9.285714285714280),
                    array(0.0, 0.0, 5.637168141592920),
                ),
                //pivot
                array(
                    array(3,1,4,2)
                ),
                //perm
                array(
                    array(0, 1, 0, 0),
                    array(0, 0, 0, 1),
                    array(1, 0, 0, 0,),
                    array(0, 0, 1, 0)
                )
            ),
            //row matrix
            array(
                //source
                array(
                    array(2,15,-5,14)
                ),
                //LU
                array(
                    array(2,15,-5,14)
                ),
                //L
                array(
                    array(1)
                ),
                //U
                array(
                    array(2,15,-5,14)
                ),
                //pivot
                array(
                    array(1)
                    ),
                //perm
                array(
                    array(1)
                    ),
            ),
            //col matrix
            array(
                //source
                array(
                    array(2),
                    array(15),
                    array(-5),
                    array(14)
                ),
                //LU
                array(
                    array(15.0),
                    array(0.133333333333333),
                    array(-0.33333333333333),
                    array(0.933333333333333)               ),
                //L
                array(
                    array(1),
                    array(0.133333333333333),
                    array(-0.33333333333333),
                    array(0.933333333333333)
                ),
                //U
                array(
                    array(15)
                ),
                //pivot
                array(
                    array(2,1,3,4)
                    ),
                //perm
                array(
                    array(0, 1, 0, 0),
                    array(1, 0, 0, 0),
                    array(0, 0, 1, 0,),
                    array(0, 0, 0, 1)
                    ),
            )

        );
    }
}
