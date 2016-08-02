<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */
namespace Chippyash\Math\Matrix\Special;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Validation\Exceptions\InvalidParameterException;

abstract class AbstractSpecial implements SpecialMatrixInterface
{
    /**
     * Map of argument names
     * Override in your child class
     * 
     * @var array
     */
    protected $map = [];
    
    /**
     * Create the special matrix
     *
     * @param array $args
     * @return NumericMatrix
     * @throws InvalidParameterException
     */
    public function create(array $args = [])
    {
        $mapped = $this->mapArguments($args);
        $this->validateArguments($mapped);
        return $this->createMatrix($mapped);
    }

    /**
     * Validate incoming matrix creation arguments
     * 
     * @param array $args
     * @return void
     * @throws InvalidParameterException
     */
    abstract protected function validateArguments(array $args);

    /**
     * Create the matrix
     * 
     * @param array $args
     * @return NumericMatrix
     */
    abstract protected function createMatrix(array $args);

    /**
     * Map arguments into an associative array e.g.
     * [1,2,4] => ['X' => 1, 'Y' => 2, 'j' => 3]
     *
     * @param array $args
     * @return array
     */
    private function mapArguments(array $args)
    {
        $ret = [];
        foreach ($args as $key => $val) {
            if (is_int($key)) {
                $ret[$this->map[$key]] = $val;
                continue;
            }

            $ret[$key] = $val;
        }

        return $ret;
    }
}