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
use Chippyash\Validation\Pattern\HasTypeMap;

/**
 * Identity Matrix
 * Create an Identity (a square matrix)
 *
 * Expects 1 parameter: int:size > 0
 */
class Identity extends AbstractSpecial
{
    const ERR1 = 'size parameter must be integer > 0';

    /**
     * Map of argument names
     * @var array
     */
    protected $map = ['size'];
    
    /**
     * @inheritDoc
     */
    protected function validateArguments(array $args)
    {
        $valA1 = new HasTypeMap([
                'size' => 'integer'
            ]
        );
        $valA2 = new Lambda(function($args) {
            return $args['size'] > 0;
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
        $func = function($row, $col) {return ($row == $col ? 1 : 0);};
        return (new Functional())->create([$args['size'], $args['size'], $func]);
    }
}