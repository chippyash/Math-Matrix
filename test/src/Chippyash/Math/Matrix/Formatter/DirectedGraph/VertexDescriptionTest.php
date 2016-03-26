<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2016
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Test\Math\Matrix\Formatter\DirectedGraph;

use Chippyash\Math\Matrix\Formatter\DirectedGraph\VertexDescription;
use Chippyash\Type\BoolType;
use Chippyash\Type\RequiredType;
use Chippyash\Type\String\StringType;

class VertexDescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var VertexDescription
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new VertexDescription(new StringType('foo'));
    }

    public function testAVertexDescriptionHasAName()
    {
        $this->assertEquals('foo', $this->sut->getName());
    }

    public function testSettingAValidAttributeWillReturnTheObject()
    {
        $this->assertInstanceOf(
            'Chippyash\Math\Matrix\Formatter\DirectedGraph\VertexDescription',
            $this->sut->set(new StringType('style'), new StringType('regular'))
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSettingAnInvalidAttributeWillThrowAnException()
    {
        $this->sut->set(new StringType('foo'), new BoolType(true));
    }

    public function testYouCanTestThatAnAttributeIsPresent()
    {
        $this->sut->set(new StringType('style'), new StringType('regular'));
        $this->assertTrue($this->sut->has(new StringType('style')));
        $this->assertFalse($this->sut->has(new StringType('foo')));
    }

    public function testYouCanGetAnAttributeValueIfItExists()
    {
        $this->sut->set(new StringType('style'), new StringType('regular'));
        $this->assertEquals('regular', $this->sut->get(new StringType('style')));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGettingAnUnknownAttributeValueWillThrowAnException()
    {
        $this->sut->get(new StringType('foo'));
    }

    public function testYouCanGetAllAttributesAsAnArray()
    {
        $this->sut->set(new StringType('style'), new StringType('regular'))
            ->set(new StringType('color'), new StringType('red'));
        $this->assertInternalType('array',$this->sut->getAttributes());
    }

    public function testGettingAllAttributesReturnsKeyedArrayWithKeysPrependedWithGraphviz()
    {
        $this->sut->set(new StringType('style'), new StringType('regular'))
            ->set(new StringType('color'), new StringType('red'));
        $test = $this->sut->getAttributes();
        $this->assertArrayHasKey('graphviz.style', $test);
        $this->assertArrayHasKey('graphviz.color', $test);
    }
}
