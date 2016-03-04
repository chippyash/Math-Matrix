<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Interfaces;

use Chippyash\Type\String\StringType;

/**
 * Tuning interface - gives access to, usually static properties on a class
 * so that any objects created can be fine tuned.
 *
 * @codeCoverageIgnore
 */
interface TuningInterface
{

    /**
     * Tune an item on a class
     *
     * @param \Chippyash\Type\String\StringType $name Item to tune
     * @param mixed $value Value to set
     *
     * @return mixed - previous value of item
     *
     * @throws \InvalidArgumentException if name does not exist
     */
    public function tune(StringType $name, $value);

}
