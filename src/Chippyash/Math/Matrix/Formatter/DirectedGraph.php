<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2016
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Formatter;

use Assembler\FFor;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Type\Comparator;
use Chippyash\Matrix\Interfaces\FormatterInterface;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\TypeFactory;
use Graphp\GraphViz\GraphViz;
use Fhaculty\Graph\Graph;
use Monad\Collection;

/**
 * Create a Graphviz directed graph definition
 */
class DirectedGraph implements FormatterInterface
{
    /**
     * Format the matrix contents for outputting
     *
     * @param Matrix $mA Matrix to format
     * @param array $options Options for formatter
     *  - attribs => Collection of VertexDescription
     *  - optional: edgeFunc => function($weight){return $newWeight;}
     *  - optional: output:string script|object default = script
     *
     * If output is script, return the graphviz dot file contents
     * If output is object then return Fhaculty\Graph\Graph for your own
     * processing via Graphp\GraphViz\GraphViz
     *
     * @return Graph|string
     */
    public function format(Matrix $mA, array $options = array())
    {
        if (!$mA instanceof NumericMatrix) {
            throw new \InvalidArgumentException('Matrix is not NumericMatrix');
        }
        return FFor::create(['mA' => $mA, 'graph' => new Graph(), 'options' => $options])
            ->attribs(function($options) {
                $attribs = array_key_exists('attribs', $options) ? $options['attribs'] : new Collection([],'string');
                if ($attribs instanceof Collection) {
                    return $attribs;
                }
                throw new \InvalidArgumentException('options[attribs]');
            })
            ->edgeFunc(function($options) {
                $edgeFunc = array_key_exists('edgeFunc', $options) ? $options['edgeFunc'] : function($w){return $w;};
                if ($edgeFunc instanceof \Closure) {
                    return $edgeFunc;
                }
                throw new \InvalidArgumentException('pptions[edgeFunc]');
            })
            ->output(function($options) {
                return array_key_exists('output', $options) ? $options['output'] : 'script';
            })
            ->vertices(function(Collection $attribs, Matrix $mA, Graph $graph) {
                $vertices = [];
                foreach(range(0, $mA->rows()-1) as $idx) {
                    if (array_key_exists($idx, $attribs)) {
                        $attribute = $attribs[$idx];
                        $vertices[$idx+1] = $graph->createVertex($attribute->getName());
                        foreach($attribute->getAttributes() as $key => $val) {
                            $vertices[$idx+1]->setAttribute($key, $val);
                        }
                    } else {
                        $vertices[$idx+1] = $graph->createVertex();
                    }
                }
                return $vertices;
            })
            ->graphViz(function(Graph $graph, Matrix $mA, array $vertices, \Closure $edgeFunc, $output) {
                $comp = new Comparator();
                $zero = TypeFactory::createInt(0);
                $rows = $mA->rows();
                for ($row = 1; $row <= $rows; $row++) {
                    for ($col = 1; $col <= $rows; $col++) {
                        if ($comp->compare($zero, $mA->get($row, $col)) != 0) {
                            $vertices[$row]->createEdgeTo($vertices[$col])
                                ->setWeight($edgeFunc($mA->get($row, $col)->get()));
                        }
                    }
                }

                return $output == 'script' ? (new GraphViz())->createScript($graph) : $graph;
            })
            ->fyield('graphViz');
    }
}