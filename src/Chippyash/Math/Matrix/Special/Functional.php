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
use Chippyash\Type\String\StringType;
use Chippyash\Validation\Common\Lambda;
use Chippyash\Validation\Exceptions\InvalidParameterException;
use Chippyash\Validation\Logical\LAnd;
use Chippyash\Validation\Logical\LOr;
use Chippyash\Validation\Pattern\HasTypeMap;

/**
 * Functional Matrix
 * Create a matrix given a function for each row, column index
 * Particularly useful when matrix content is a function of row,col index
 *
 * Expects 3 parameters: int:rows > 0, int:cols > 0, Closure:f(row, col)
 */
class Functional extends AbstractSpecial
{
    const ERR1 = 'rows and cols parameters must be integer > 0';
    const ERR2 = 'f parameter must be a Closure';

    /**
     * Map of argument names
     * @var array
     */
    protected $map = ['rows', 'cols', 'f'];
    
    /**
     * @inheritDoc
     */
    protected function validateArguments(array $args)
    {
        $valA1 = new HasTypeMap([
                'rows' => 'integer',
                'cols' => 'integer',
                'f' => function($val) { return ($val instanceof \Closure);}
            ]
        );
        $valA2 = new Lambda(function($args) {
            return ($args['rows'] > 0 && $args['cols'] > 0);
        }, new StringType(self::ERR1));
        $validator = new LAnd($valA1, $valA2);

        if (!$validator->isValid($args)) {
            throw new InvalidParameterException(implode(':', $validator->getMessages()));
        }
    }

    /**
     * @inheritDoc
     */
    protected function createMatrix(array $args)
    {
        $data = [];
        $func = $args['f'];
        for ($row = 0; $row < $args['rows']; $row ++) {
            for ($col = 0; $col < $args['cols']; $col ++) {
                $data[$row][$col] = $func($row, $col);
            }
        }

        return new NumericMatrix($data);
    }
}