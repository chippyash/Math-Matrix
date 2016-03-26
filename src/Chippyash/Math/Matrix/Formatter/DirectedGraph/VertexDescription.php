<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2016
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Formatter\DirectedGraph;
use chippyash\Type\Interfaces\TypeInterface;
use chippyash\Type\String\StringType;

/**
 * Describes a vertex in the diGraph produced by the DirectedGraph formatter
 *
 * @see http://www.graphviz.org/content/attrs N class attributes
 */
class VertexDescription
{
    /**
     * Valid attributes as applicable to a Vertex (graphviz node)
     *
     * @var array
     */
    protected $validAttributes = [
        'URL','area','color','colorscheme','comment','distortion','fillcolor',
        'fixedsize','fontcolor','fontname','fontsize','gradientangle','group',
        'height','href','id','image','imagescale','label','labelloc','layer',
        'nojustify','ordering','orientation','penwidth','peripheries','pin',
        'pos','rects','regular','root','samplepoints','shape','shapefile','showboxes',
        'sides','skew','sortv','style','target','tooltip','vertices','width',
        'xlabel','xip','z'
    ];

    /**
     * Attributes for this Vertex
     *
     * @var array [name => value, ...]
     */
    protected $attrs = [];

    /**
     * Name of the vertex
     *
     * @var string
     */
    protected $name;

    public function __construct(StringType $name)
    {
       $this->name = $name();
    }

    /**
     * Set a graphviz attribute for this vertex
     *
     * @param StringType $attr
     * @param TypeInterface $value
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function set(StringType $attr, TypeInterface $value)
    {
        if (!in_array($attr(), $this->validAttributes)) {
           throw new \InvalidArgumentException($attr());
        }

        $this->attrs[$attr()] = $value();

        return $this;
    }

    /**
     * Does this description have an attribute?
     *
     * @param StringType $attr
     *
     * @return bool
     */
    public function has(StringType $attr)
    {
        return array_key_exists($attr(), $this->attrs);
    }

    /**
     * Get a description attribute
     *
     * @param StringType $attr
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function get(StringType $attr)
    {
        if ($this->has($attr)) {
            return $this->attrs[$attr()];
        }

        throw new \InvalidArgumentException($attr());
    }

    /**
     * Return attributes as an array
     * Key names will have 'graphviz.' prepended
     *
     * @return array [name=>value, ...]
     */
    public function getAttributes()
    {
        return array_combine(
            array_map(function($key) {
                return "graphviz.{$key}";
            },
                array_keys($this->attrs)
            ),
            $this->attrs
        );
    }

    /**
     * Return vertex name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}