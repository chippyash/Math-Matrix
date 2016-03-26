<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2016
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Test\Math\Matrix\Formatter;


use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Formatter\DirectedGraph;
use Chippyash\Math\Matrix\Formatter\DirectedGraph\VertexDescription;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Type\Number\FloatType;
use Chippyash\Type\RequiredType;
use Chippyash\Type\String\StringType;
use Monad\Collection;

class DirectedGraphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var DirectedGraph
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new DirectedGraph();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSupplyingANonNumericMatrixWillThrowAnException()
    {
        $this->sut->format(new Matrix([[0,1],[1,0]]));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSupplyingANonContainerAttribsParameterWillThrowAnException()
    {
        $this->sut->format(new NumericMatrix([[0,1],[1,0]]), ['attribs' => []]);
    }

    public function testYouDoNotNeedToSupplyVertexDescriptions()
    {
        $mA = new NumericMatrix([[0,0,1],[0,1,0],[1,0,0]]);
        $test = <<<EOF
digraph G {
  0 -> 2 [label=1]
  1 -> 1 [label=1 dir="none"]
  2 -> 0 [label=1]
}

EOF;
        $this->assertEquals($test, $this->sut->format($mA));
    }

    public function testYouCanSupplyAnOptionalEdgeFormatter()
    {
        $mA = new NumericMatrix([[0,50,50],[33,33,33],[100,0,0]]);
        $test = <<<EOF
digraph G {
  0 -> 1 [label=0.5]
  0 -> 2 [label=0.5]
  1 -> 0 [label=0.33]
  1 -> 1 [label=0.33 dir="none"]
  1 -> 2 [label=0.33]
  2 -> 0 [label=1]
}

EOF;
        $func = function($origValue) {return \round($origValue / 100, 2);};
        $this->assertEquals($test, $this->sut->format($mA, ['edgeFunc' => $func]));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPassingInANonClosureAsEdgeFunctionWillThrowAnException()
    {
        $this->sut->format(new NumericMatrix([[1,2],[2,1]]), ['edgeFunc' => 'foo']);
    }

    public function testYouCanRenderAValidNumericMatrixThatDescribesAGraph()
    {
        $csv = explode(PHP_EOL,$this->getData());
        $m = [];
        $attribs = [];
        foreach ($csv as $line) {
            $values = str_getcsv($line);
            $attr = new VertexDescription(new StringType(array_shift($values)));
            $attr->set(new StringType('fillcolor'), new StringType('white'))
                ->set(new StringType('style'), new StringType('filled,solid'));
            if ($attr->getName() == 'I') {
                $attr->set(new StringType('fillcolor'), new StringType('green'));
            }
            if ($attr->getName() == 'H') {
                $attr->set(new StringType('fillcolor'), new StringType('red'));
            }
            if (in_array($attr->getName(), ['A','B','C','D','E','F'])) {
                $attr->set(new StringType('fillcolor'), new StringType('yellow:green'));
            }
            array_shift($values);//remove name
            $m[] = array_map(function($item) { //convert weights to integer
                return intval(floatval($item * 100));
            },
                $values
            );
            $attribs[] = $attr;
        }
        $attribCollection = new Collection($attribs);
        $markov = new NumericMatrix($m);
        $test = $this->sut->format($markov, ['attribs' => $attribCollection, 'edgeFunc' => function($w){return $w/100;}]);
        $this->assertEquals($this->getRenderResult(), $test);
    }

    protected function getData()
    {
        return <<<EOT
A,0,24.57,14.83,2.57,1.76,,1.7,54.55,,,,,,,,,,,,,,,,,,,,,,
B,0,,,57,,,,10.9,16,5.52,4.78,3.75,2,,,,,,,,,,,,,,,,,
C,0,47.8,2.4,6.52,2.94,,,14,23.3,,,,,3.05,,,,,,,,,,,,,,,,
D,0,,,,,,,26.2,7.27,,,,30.8,,16.2,8.82,5.02,5.67,,,,,,,,,,,,
E,0,14,6.53,7.23,5.8,8.22,,29.9,28.3,,,,,,,,,,,,,,,,,,,,,
F,0,,,14.8,17.9,13,,25.4,14.8,,,,5.25,,,,,,8.69,,,,,,,,,,,
G,0,,,,,,,,100,,,,,,,,,,,,,,,,,,,,,
H,0,,,,,,,,100,,,,,,,,,,,,,,,,,,,,,
I,0,,,,,,,,100,,,,,,,,,,,,,,,,,,,,,
J,0,6.2,,,,,,69.4,24.4,,,,,,,,,,,,,,,,,,,,,
K,0,,,,,,,,100,,,,,,,,,,,,,,,,,,,,,
L,0,,,11.9,,,,19.1,15.7,,,,6.65,,,,,,15.3,22.6,8.76,,,,,,,,,
M,0,15.3,,9.1,,,,24.4,27.3,,,,,,,5,,,,,,14.2,4.74,,,,,,,
N,0,10.3,11.2,,,,,15.4,28.7,,,,,,,,,,,,,,,19.6,9.86,4.93,,,,
O,0,15.9,,14.3,,,,18.1,24,,,,14.8,,,9.19,,3.7,,,,,,,,,,,,
P,0,5.22,,19.3,,,,18.4,10.1,,,,11.4,,6.06,,29.5,,,,,,,,,,,,,
Q,0,7.64,,18.7,,,,15.9,13.7,,,,9.72,,4.76,29.7,,,,,,,,,,,,,,
R,0,4.95,,29.7,,,,28.2,17,,,,,,5.51,5.63,,,,,,8.96,,,,,,,,
S,0,10.5,,14,,,,27.4,13.3,,,,16,,,6.63,,,,,,,12.2,,,,,,,
T,0,15.4,,14.6,,,,14.6,39.3,,,,4.24,,,,,,3.58,,,,,,,,8.26,,,
U,0,,,,,,,,100,,,,,,,,,,,,,,,,,,,,,
V,0,14.8,,6.88,,,,19.6,27.2,,,,16.4,,,,,,,,,,,,,,,9.07,6.09,
X,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
Y,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
Z,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
AA,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
AB,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
AC,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
AD,0,,,,,,,100,,,,,,,,,,,,,,,,,,,,,,
EOT;

    }

    protected function getRenderResult()
    {
        return <<<EOT
digraph G {
  "A" [fillcolor="yellow:green" style="filled,solid"]
  "B" [fillcolor="yellow:green" style="filled,solid"]
  "C" [fillcolor="yellow:green" style="filled,solid"]
  "D" [fillcolor="yellow:green" style="filled,solid"]
  "E" [fillcolor="yellow:green" style="filled,solid"]
  "F" [fillcolor="yellow:green" style="filled,solid"]
  "G" [fillcolor="white" style="filled,solid"]
  "H" [fillcolor="red" style="filled,solid"]
  "I" [fillcolor="green" style="filled,solid"]
  "J" [fillcolor="white" style="filled,solid"]
  "K" [fillcolor="white" style="filled,solid"]
  "L" [fillcolor="white" style="filled,solid"]
  "M" [fillcolor="white" style="filled,solid"]
  "N" [fillcolor="white" style="filled,solid"]
  "O" [fillcolor="white" style="filled,solid"]
  "P" [fillcolor="white" style="filled,solid"]
  "Q" [fillcolor="white" style="filled,solid"]
  "R" [fillcolor="white" style="filled,solid"]
  "S" [fillcolor="white" style="filled,solid"]
  "T" [fillcolor="white" style="filled,solid"]
  "U" [fillcolor="white" style="filled,solid"]
  "V" [fillcolor="white" style="filled,solid"]
  "X" [fillcolor="white" style="filled,solid"]
  "Y" [fillcolor="white" style="filled,solid"]
  "Z" [fillcolor="white" style="filled,solid"]
  "AA" [fillcolor="white" style="filled,solid"]
  "AB" [fillcolor="white" style="filled,solid"]
  "AC" [fillcolor="white" style="filled,solid"]
  "AD" [fillcolor="white" style="filled,solid"]
  "A" -> "A" [label=24.57 dir="none"]
  "A" -> "B" [label=14.83]
  "A" -> "C" [label=2.57]
  "A" -> "D" [label=1.76]
  "A" -> "F" [label=1.7]
  "A" -> "G" [label=54.55]
  "B" -> "C" [label=57]
  "B" -> "G" [label=10.9]
  "B" -> "H" [label=16]
  "B" -> "I" [label=5.52]
  "B" -> "J" [label=4.78]
  "B" -> "K" [label=3.75]
  "B" -> "L" [label=2]
  "C" -> "A" [label=47.8]
  "C" -> "B" [label=2.4]
  "C" -> "C" [label=6.52 dir="none"]
  "C" -> "D" [label=2.94]
  "C" -> "G" [label=14]
  "C" -> "H" [label=23.3]
  "C" -> "M" [label=3.05]
  "D" -> "G" [label=26.2]
  "D" -> "H" [label=7.27]
  "D" -> "L" [label=30.8]
  "D" -> "N" [label=16.2]
  "D" -> "O" [label=8.82]
  "D" -> "P" [label=5.01]
  "D" -> "Q" [label=5.67]
  "E" -> "A" [label=14]
  "E" -> "B" [label=6.53]
  "E" -> "C" [label=7.23]
  "E" -> "D" [label=5.8]
  "E" -> "E" [label=8.22 dir="none"]
  "E" -> "G" [label=29.9]
  "E" -> "H" [label=28.3]
  "F" -> "C" [label=14.8]
  "F" -> "D" [label=17.89]
  "F" -> "E" [label=13]
  "F" -> "G" [label=25.4]
  "F" -> "H" [label=14.8]
  "F" -> "L" [label=5.25]
  "F" -> "R" [label=8.69]
  "G" -> "H" [label=100]
  "H" -> "H" [label=100 dir="none"]
  "I" -> "H" [label=100]
  "J" -> "A" [label=6.2]
  "J" -> "G" [label=69.4]
  "J" -> "H" [label=24.4]
  "K" -> "H" [label=100]
  "L" -> "C" [label=11.9]
  "L" -> "G" [label=19.1]
  "L" -> "H" [label=15.7]
  "L" -> "L" [label=6.65 dir="none"]
  "L" -> "R" [label=15.3]
  "L" -> "S" [label=22.6]
  "L" -> "T" [label=8.76]
  "M" -> "A" [label=15.3]
  "M" -> "C" [label=9.1]
  "M" -> "G" [label=24.4]
  "M" -> "H" [label=27.3]
  "M" -> "O" [label=5]
  "M" -> "U" [label=14.2]
  "M" -> "V" [label=4.74]
  "N" -> "A" [label=10.3]
  "N" -> "B" [label=11.2]
  "N" -> "G" [label=15.4]
  "N" -> "H" [label=28.7]
  "N" -> "X" [label=19.6]
  "N" -> "Y" [label=9.86]
  "N" -> "Z" [label=4.93]
  "O" -> "A" [label=15.9]
  "O" -> "C" [label=14.3]
  "O" -> "G" [label=18.1]
  "O" -> "H" [label=24]
  "O" -> "L" [label=14.8]
  "O" -> "O" [label=9.19 dir="none"]
  "O" -> "Q" [label=3.7]
  "P" -> "A" [label=5.22]
  "P" -> "C" [label=19.3]
  "P" -> "G" [label=18.39]
  "P" -> "H" [label=10.1]
  "P" -> "L" [label=11.4]
  "P" -> "N" [label=6.06]
  "P" -> "P" [label=29.5 dir="none"]
  "Q" -> "A" [label=7.64]
  "Q" -> "C" [label=18.7]
  "Q" -> "G" [label=15.9]
  "Q" -> "H" [label=13.7]
  "Q" -> "L" [label=9.72]
  "Q" -> "N" [label=4.76]
  "Q" -> "O" [label=29.7]
  "R" -> "A" [label=4.95]
  "R" -> "C" [label=29.7]
  "R" -> "G" [label=28.2]
  "R" -> "H" [label=17]
  "R" -> "N" [label=5.51]
  "R" -> "O" [label=5.63]
  "R" -> "U" [label=8.96]
  "S" -> "A" [label=10.5]
  "S" -> "C" [label=14]
  "S" -> "G" [label=27.4]
  "S" -> "H" [label=13.3]
  "S" -> "L" [label=16]
  "S" -> "O" [label=6.63]
  "S" -> "V" [label=12.2]
  "T" -> "A" [label=15.4]
  "T" -> "C" [label=14.6]
  "T" -> "G" [label=14.6]
  "T" -> "H" [label=39.29]
  "T" -> "L" [label=4.24]
  "T" -> "R" [label=3.58]
  "T" -> "AA" [label=8.26]
  "U" -> "H" [label=100]
  "V" -> "A" [label=14.8]
  "V" -> "C" [label=6.88]
  "V" -> "G" [label=19.6]
  "V" -> "H" [label=27.2]
  "V" -> "L" [label=16.39]
  "V" -> "AB" [label=9.07]
  "V" -> "AC" [label=6.09]
  "X" -> "G" [label=100]
  "Y" -> "G" [label=100]
  "Z" -> "G" [label=100]
  "AA" -> "G" [label=100]
  "AB" -> "G" [label=100]
  "AC" -> "G" [label=100]
  "AD" -> "G" [label=100]
}

EOT;

    }
}
