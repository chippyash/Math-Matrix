# chippyash/Math-Matrix

## Quality

![PHP 5.4](https://img.shields.io/badge/PHP-5.4-blue.svg)
![PHP 5.5](https://img.shields.io/badge/PHP-5.5-blue.svg)
![PHP 5.6](https://img.shields.io/badge/PHP-5.6-blue.svg)
![PHP 7](https://img.shields.io/badge/PHP-7-blue.svg)
[![Build Status](https://travis-ci.org/chippyash/Math-Matrix.svg?branch=master)](https://travis-ci.org/chippyash/Math-Matrix)
[![Test Coverage](https://codeclimate.com/github/chippyash/Math-Matrix/badges/coverage.svg)](https://codeclimate.com/github/chippyash/Math-Matrix/coverage)
[![Code Climate](https://codeclimate.com/github/chippyash/Math-Matrix/badges/gpa.svg)](https://codeclimate.com/github/chippyash/Math-Matrix)

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
See the [Test Contract](https://github.com/chippyash/Math-Matrix/blob/master/docs/Test-Contract.md) (526 tests, 820 assertions)

See the [API Documentation](http://chippyash.github.io/Math-Matrix/) for further information

## What?

Since the [JAMA library](http://www.phpmath.com/build02/JAMA/downloads/), there
has not been been a library to my knowledge that allows PHP devs to simply
incorporate arithmetic Matrix functionality within an application.

If you are using reasonably small matrices then the complexity of having to compile in external
[Fortran or C based](http://en.wikipedia.org/wiki/LAPACK) libraries
is something you can do without. And even when you do, it transpires the PHP
bindings are limited.

You need speed - PHP is never going to do it for you on big Matrices, start compiling.
For everything else, give this a go.

This library aims to provide arithmetic matrix functionality in the most efficient way
possible using PHP given that:

*  Everything has a test case
*  It's PHP 5.5+

The library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

## Why?

This adds maths to the Chippyash/Matrix library giving you the ability to create
and manipulate matrices containing numeric (float, int, rational and complex) values.

## When

The current library covers basic matrix maths.  It is a work in progress and has some
limitations.  Addition, subtraction and multiplication  are straight forward and
should work for any size matrix.  Division relies on inversion which currently relies on
the ability to determine the determinant of a matrix.  The library supports two strategies
for finding a determinant - Laplace expansion and LU.  This places a realistic limit on the
size of matrices that can be operated on.  See the examples/example-laplace-bounds.php
and examples/example-lu-determinant-bounds.php scripts to understand why.
The limit is a 20x20 matrix when using the Determinant derivative
in auto mode.  The limit is arbitrary and based on what can computed on my
laptop in about a second.  Other brands of machinery may vary.

This may change in the future as I refactor for performance or incorporate more
performant strategies for computing inverses and determinants.  If you are good
at maths computation, this is an area where you can really help out

If you want more, either suggest it, or better still, fork it and provide a pull request.

Check out [chippyash/Matrix](https://github.com/chippyash/Matrix) for underlying matrix operations

Check out [chippyash/Logical-Matrix](https://github.com/chippyash/Logical-matrix) for logical matrix operations

Check out [ZF4 Packages](http://zf4.biz/packages?utm_source=github&utm_medium=web&utm_campaign=blinks&utm_content=mathmatrix) for more packages

## How

The current version of this library utilises the PHP Native numeric Strong Types as the
calculator that it uses can only deal with them at present.  GMP support is on the
roadmap once the calculator provides it.  You can ensure that this is enforced by
forcing its setting with a call to 

<pre>
use Chippyash\Type\RequiredType;
RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
</pre>

before any operation with the matrices.

### Coding Basics

In PHP terms a matrix is an array of arrays, 2 dimensional i.e

-  [[]]

As with any TDD application, the tests tell you everything you need to know about
the SUT.  Read them!  However for the short of tempered amongst us the salient
points are:

The library extends the Chippyash/Matrix library, so anything you can do on a basic
matrix, you can do with a numeric matrix.  The library utilises the Chippyash/Strong-Type
strong types.

Three basic Matrix types are supplied

* NumericMatrix: containing int, float, IntType, WholeIntType, NaturalIntType,
FloatType, RationalType and ComplexType data items
* RationalMatrix: containing only RationalType data items
* ComplexMatrix: containing only ComplexType data items

The NumericMatrix is fine for general purpose work, but not if you want prove that
the inverse(M) * M = Identity.  For that you'll need the RationalMatrix which is
far more arithmetically stable.  This is also the matrix that will benefit from
forthcoming support of PHP's various maths extension libraries in the Chippyash/Strong-Type
library.

The ComplexMatrix fully supports the Chippyash\Type\Numeric\Complex\ComplexType.

Both the RationalMatrix and ComplexMatrix extend the NumericMatrix.

Creating a numeric type matrix is straightforward:

<pre>
    use Chippyash\Math\Matrix\NumericMatrix;
    use Chippyash\Math\Matrix\RationalMatrix;
    use Chippyash\Math\Matrix\ComplexMatrix;
    //create empty matrices
    $mA = new NumericMatrix([]);
    $mB = new RationalMatrix([]);
    $mC = new ComplexMatrix([]);
</pre>
 Supplying data to matrix construction will create data items inside the matrix
according to the following rules:

*  NumericMatrix: items are created in the lowest NumericTypeInterface possible.  The
low to high order is IntType, FloatType, RationalType, ComplexType
*  RationalMatrix: items are created as RationalType
*  ComplexMatrix items are created as ComplexType.  If you supply non-complex data
items, then 'real' complex items are created, i.e. the imaginary part == zero

Be aware that operations on a NumericMatrix will probably produce the result
having different data type entries.  This is inevitable given the constraints
placed on computer based arithmetic. If any operation requires the item to be
cast upwards then it is unlikely to be cast downwards again.

Some helpers are provided:

#### MatrixFactory

provides static methods:

*  MatrixFactory::create(string $type, array $data).  $type is one of numeric, rational or complex
*  MatrixFactory::createComplex(array $data)
*  MatrixFactory::createRational(array $data)
*  MatrixFactory::createNumeric(array $data)
*  MatrixFactory::createFromFunction(callable $fn, IntType $rows, IntType $cols, $type = 'numeric')
*  MatrixFactory::createFromComplex(ComplexType $c)

#### FunctionMatrix

\[DEPRECATED - Use SpecialMatrix::create('functional', int:rows, int:cols, \Closure:f(int:rows, int:cols))\] instead

Create a numeric matrix as a result of applying a function.

<pre>
    $f = function($row, $col) {return TypeFactory::createInt($row * $col);};
    $mA = new FunctionMatrix($f, TypeFactory::createInt(3), TypeFactory::createInt(4));
</pre>

#### IdentityMatrix

\[DEPRECATED - Use SpecialMatrix::create('identity',int:size) instead\]

Create a NumericMatrix Identity matrix

<pre>
    //create 4x4 identity matrix with integer entries
    $mA = new IdentityMatrix(TypeFactory::createInt(4));
    //or more usually, use the factory method
    $mA - IdentityMatrix::numericIdentity(TypeFactory::createInt(4));
</pre>

Create rational and complex identity matrices using factory methods:

<pre>
    $mR = IdentityMatrix::rationalIdentity(TypeFactory::createInt(4));
    $mC = IdentityMatrix::complexIdentity(TypeFactory::createInt(4));
</pre>

#### ZeroMatrix

\[DEPRECATED - Use SpecialMatrix::create('zeros', int:rows\[, int:cols\])\]

Create a NumericMatrix with all entries set to zero

<pre>
    //create 2 x 4 zero matrix
    $mZ = new ZeroMatrix(TypeFactory::createInt(2), TypeFactory::createInt(4));
</pre>

#### ShiftMatrix

Create a [Shift Matrix](https://en.wikipedia.org/wiki/Shift_matrix)

<pre>
	$mA - IdentityMatrix::numericIdentity(TypeFactory::createInt(5));
	
	//create 5 x 5 shift matrices
	$mSupper = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_UPPER);
	$mSlower = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_LOWER);
	//optionally specify the matrix content type
	$mSupper = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_UPPER, new IntType(IdentityMatrix::IDM_TYPE_COMPLEX));
	
	$mC = $mA('Mul\Matrix', $mSupper);
	$mD = $mLower('Mul\Matrix', $mA);
</pre>

#### SpecialMatrix

Provides numerous special matrices:

Adopting an idea from [Octave Gallery Matrices](https://www.gnu.org/software/octave/doc/v4.0.1/Famous-Matrices.html)

<pre>
//inline creation if your version of PHP allows it
use Chippyash\Math\Matrix\SpecialMatrix;
$mS = (new SpecialMatrix())->create(new StringType('NameOfMatrix')[, $arg1, $arg2]);

//or as an invokable class
$factory = new SpecialMatrix();
$mS = $factory(new StringType('nameOfMatrix')[, $arg1, $arg2]);
//or
$mS = $factory('NameOfMatrix'[, $arg1, $arg2]);
</pre>

Matrices provided:

*  Ones Matrix/Vector: create('ones', int:rows) or create ('ones', int:rows, int:cols)
*  Cauchy Matrix: create('cauchy', int:x) or create('cauchy', vector:x, vector:y)
*  Identity Matrix: create('identity', int:size)
*  Functional Matrix: create('functional', int:rows, int:cols, \\Closure: f(int:rows, int:cols))
*  Zeros Matrix/Vector: create('zeros', int:rows) or create ('zeros', int:rows, int:cols)

All returned matrices are NumericMatrices.

Really important: We don't do this shit by ourselves. So read the tests and read the source files
where you'll find attribution to some far cleverer people than me. I just try
to translate into the PHP world.
 
####  Numeric matrices have additional attributes

*  IsComplex: boolean - Is the matrix instanceof ComplexMatrix?
*  IsIdentity: boolean - Is the matrix an identity matrix?
*  IsNonsingular: boolean - Is the matrix non singular
*  IsNumeric: boolean - Is the matrix instanceof NumericMatrix?
*  IsRational: boolean -  Is the matrix instanceof RationalMatrix?
*  IsMarkov: boolean - Does matrix conform to requirements for a Markov Chain Matrix

Remember, you can use the is() method to test for an attribute on a matrix.

####  Matrices can be computed

*  Computations always return a matrix.
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Computations implement the Chippyash\Math\Matrix\Interfaces\ComputationInterface
*  Computations work with scalar values or other matrices.  Non scalar values will throw an exception

On the whole, computations, will work with any scalar but:

**All matrix computations follow the natural laws**

*  you can't divide by zero
*  dividing a matrix by a non invertible matrix is like 1/0 - oops!
*  using float, int and rational data items will work together
*  using complex number data items: all items have to be complex

The following computations are provided (using the magic invoke interface method):

*  'Add\Scalar'    : add scalar value to the matrix
*  'Add\Matrix'    : add a matrix to the matrix
*  'Sub\Scalar'    : subtract scalar value from the matrix
*  'Sub\Matrix'    : subtract a matrix from the matrix
*  'Mul\Scalar'    : multiply matrix by scalar value
*  'Mul\Matrix'    : multiply matrix by another matrix using common [Matrix Product Method](https://en.wikipedia.org/wiki/Matrix_multiplication#General_definition_of_the_matrix_product)
*  'Mul\Entrywise' : multiply matrix by another matrix using [Hadamard or Schur Product Method](https://en.wikipedia.org/wiki/Matrix_multiplication#Hadamard_product)
*  'Div\Scalar'    : divide matrix by scalar value
*  'Div\Matrix'    : divide matrix by another matrix - see notes at head of this readme
*  'Div\Entrywise' : divide matrix by another matrix using an Entrywise method. Where a particular vertex is division
 by zero, a zero will be resulted.  This is a defensive strategy and there is no right answer.
 Best defence is to ensure that the matrix you are dividing by does not contain zeros 

<pre>
    $mC = $mA('Mul\Matrix', $mB);
    //same as
    $fMul = new Chippyash\Math\Matrix\Computation\Mul\Matrix();
    $mC = $mA->compute($fMul, $mB)
    //same as
    $mC = $fMul->compute($mA, $mB);
</pre>

#### You can derive other information from a matrix

*  Derivatives always return a numeric result
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Derivatives implement the Chippyash\Math\Matrix\Interfaces\DerivativeInterface

Four derivatives are currently supplied.

*  Determinant

<pre>
    $det = $mA("Determinant");
    //same as
    $fDet = new Chippyash\Math\Matrix\Derivative\Determinant();
    $det = $mA->derive($fDet);
    //same as
    $det = $fDet($mA);
</pre>

As noted above, the Determinant derivative currently only supports up to a 20x20 matrix
in auto mode. It will bork if you supply a matrix bigger than that. This will
be ok for most purposes.  If you are happy to wait a while to compute determinants
for bigger matrices, create the determinant by construction (second way above)
and specify Determinant::METHOD_LU as a construction parameter.
Determinant::METHOD_LAPLACE is left for historical reasons, as there is very
little likelihood you will want to use it!

*  Trace. Returns the trace of a square matrix

<pre>
    $tr = $mA('Trace');
    //or other variations as with Determinant
</pre>

*  Sum. Simply sums all the vertices in the matrix and returns the result

<pre>
    $sum = $mA('Sum');
    //or other variations as with Determinant
</pre>

* MarkovWeightedRandom. Return the next step using Random Weighted method on a 
Markov Chain Matrix

<pre>
    $mA = new NumericMatrix(
        [
            [0,2,0,8]  //row 1
            [1,0,0,0]  //row 2
            [0,0,5,5]  //row 3
            [0,1,6,3]  //row 4
        ]
    )
    
    $next = $mA('MarkovWeightedRandom', TypeFactory::createInt(3));
    //will return IntType(3) or IntType(4) with 50% chance of either being returned
    
</pre>

A NotMarkovException will be thrown if the supplied Matrix does not conform to the
IsMarkov Attribute.  Please note, that whilst you can supply a matrix with floats, the
nature of mt_rand() function used to generate the next number means that, for the time
being, you should provide integer values.  As long as you supply a square matrix where
each row row sums to the same number, you have a Markov Chain expressed as a Matrix.

#### Additional transformations are supported by numeric matrices

*  Invert - Returns the inverted matrix or throws an exception if not possible.
The current inversion method relies on determinants and as noted, this is only
currently feasible for matrices up to 20x20

<pre>
    try {
        $mI = $mA('Invert');
        //same as
        $fInvert = new Chippyash\Math\Matrix\Transformation\Invert();
        $mI = $mA->transform($fInvert);
        //same as
        $mI = $fInvert($mA);
    } catch (Chippyash\Math\Matrix\Exceptions\ComputationException $e) {
        //cannot invert
    }
</pre>

If you want to break the 20x20 limit, you can do the following:

<pre>
    $det = new Chippyash\Math\Matrix\Derivative\Determinant();
    $det->tune('luLimit', 40); //or whatever you are prepared to put up with
    $mI = $mA('Invert');
</pre>

*  MarkovRandomWalk - Given a Markov Chain represented as a Numeric Matrix, randomly 
walk from a start row to a target row, returning a Row Vector Numeric Matrix of IntTypes:

<pre>
    $det = new Chippyash\Math\Matrix\Derivative\MarkovRandomWalk();
    $res = $det->transform(
        $mA, 
        array(
            'start'=>TypeFactory::createInt(2), 
            'target'=>TypeFactory::createInt(4)
        )
    );
</pre>

You can supply an optional third parameter, `limit` to limit the steps that can be taken.
This is set to 100 by default.

<pre>
    $res = $mA(
        'MarkovRandomWalk', 
        array(
            'start'=>TypeFactory::createInt(2), 
            'target'=>TypeFactory::createInt(4),
            'limit'=>TypeFactory::createInt(10)
        )
    );
</pre>


#### Matrices can be decomposed

*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Decompositions return a decomposition object, from which you can access the various parts of the decomposition.
*  Decompositions implement the Chippyash\Math\Matrix\Interfaces\DecompositionInterface

The library currently supports:

*  LU Decomposition
*  Gauss Jordan Elimination

**LU**
<pre>
    $lu = $mA('Lu');
    //same as
    $fLu = new Chippyash\Math\Matrix\Decomposition\Lu()
    $lu = $mA->decompose($fLu);
    //same as
    $lu = $fLu($mA);
</pre>

The LU products are:

*  LU : NumericMatrix - The LU complete decomposition matrix
*  L : NumericMatrix - The lower triangle
*  U : NumericMatrix - The upper triangle
*  PivotVector : NumericMatrix - Pivot vector of the decomposition
*  PermutationMatrix : NumericMatrix - Permutation matrix
*  Det : NumericTypeInterface|Null - Determinant or null if matrix is not square

Accessing the products is either via the product() method or more simply as an
attribute of the decomposition:

<pre>
    $pv = $lu->product('PivotVector');
    //same as
    $pv = $lu->PivotVector;
</pre>

N.B.  Product names for any decomposition are case sensitive

**Gauss Jordan Elimination**

<pre>
    $mA = new NumericMatrix(
                [[1, 1, 1],
                 [2, 3, 5],
                 [4, 0, 5]]
                );
    $mB = new NumericMatrix(
            [[5],
             [8],
             [2]]
            );
    $elim = $mA('GaussJordonElimination', $mB);
    //same as
    $fElim = new Chippyash\Math\Matrix\Decomposition\GaussJordonElimination()
    $elim = $mA->decompose($fElim, $mB);
    //same as
    $elim = $fElim($mA, $mB);
</pre>

The products are:

*  left  The left matrix after elimination
*  right The right matrix after elimination

Using the above example then $elim->left should == an identity matrix and
$elim->right == [[3],[4],[-2]] as we've just solved

<pre>
    x + y + z = 5
    2x + 3y + 5z = 8
    4x + 5z = 2
where
    x = 3, y = 4, z = -2
</pre>

#### Formatting for numeric display

An additional display formatter is supported by the library:

\Chippyash\Math\Matrix\Formatter\AsciiNumeric

It extends the \Chippyash\Matrix\Formatter\Ascii.  An additional option 'outputType'
is provided that should take one of the following values:

<pre>
    AsciiNumeric::TP_NONE      //behave as base formatter - default behaviour
    AsciiNumeric::TP_INT       //convert all entries to int.  This will floor() if possible
    AsciiNumeric::TP_FLOAT     //convert all entries to float if possible
    AsciiNumeric::TP_RATIONAL  //convert all entries to rational if possible
    AsciiNumeric::TP_COMPLEX   //convert all entries to complex (always possible)
</pre>

Please note that although you instruct to convert to a particular numeric type the
actual display may result in a simpler form if possible.

Example:

<pre>
    echo $mA->setFormatter(new AsciiNumeric())
        ->display(['outputType' => AsciiNumeric::TP_FLOAT]);
</pre>

#### Formatting for a directed graph

To use this functionality you need to include

<pre>
    "clue/graph": "^0.9",
    "graphp/graphviz": "0.2.*"
</pre>

to your composer requires section and run a composer update.

This adds the functionality to use the GraphViz suite of programs to create image files
from a NumericMatrix that describes a graph.
  
The simplest use of the renderer is to simply supply it a NumericMatrix:

<pre>
$mA = new NumericMatrix([[0,0,1],[0,1,0],[1,0,0]]);
$dot = $mA->setFormatter(new DirectedGraph())->display();
</pre>

will produce a GraphViz .dot file content string such as:
 
<pre>
digraph G {
  0 -> 2 [label=1]
  1 -> 1 [label=1 dir="none"]
  2 -> 0 [label=1]
}
</pre>

Mot often however, you'll want to give some meaning to your vertices.  To do this, you 
can pass in a Monad\Collection of VertexDescription objects.  For example

<pre>
use Monad\Collection;
use Chippyash\Math\Matrix\Formatter\DirectedGraph\VertexDescription;

$attribs = new Collection(
    [
        new VertexDescription(new StringType('A')),
        new VertexDescription(new StringType('B')),
        new VertexDescription(new StringType('C')),
    ]
);
$dot = $mA->setFormatter(new DirectedGraph())->display(['attribs' => $attribs]);
</pre>

gives

<pre>
'digraph G {
  "A" -> "C" [label=1]
  "B" -> "B" [label=1 dir="none"]
  "C" -> "A" [label=1]
}
</pre>

You can also set things like colours and shapes via the VertexDescription.  Take a
look at the tests.

If you want to do some additional processing prior to generating something through GraphViz,
you can pass in an optional parameter:

<pre>
$graph = $mA->setFormatter(new DirectedGraph())->display(['output' => 'object']);
</pre>

will return a Fhaculty\Graph\Graph object that you can process further before sending
into Graphp\GraphViz\GraphViz.  You may also want to get a Graph so that you can do some
graph processing via the Graphp\Algorithms library.

Finally, with the DirectedGraph renderer, you can provide an optional callable function
that will be applied to the values of the edges.  This is often useful to reformat the value
in some way.  For instance:

<pre>
$mA = new NumericMatrix([[0,50,50],[33,33,33],[100,0,0]]);
$func = function($origValue) {return \round($origValue / 100, 2);};
$dot = $mA->setFormatter(new DirectedGraph())->display(['edgeFunc' => $func]);
</pre>

will produce

<pre>
digraph G {
  0 -> 1 [label=0.5]
  0 -> 2 [label=0.5]
  1 -> 0 [label=0.33]
  1 -> 1 [label=0.33 dir="none"]
  1 -> 2 [label=0.33]
  2 -> 0 [label=1]
}
</pre>

#### Exception handling

As matrix maths can throw up problems, particularly when inverting or decomposing,
it is always a good idea to wrap whatever you are doing in a try - catch block.
The following exceptions are supported by the library.  They all extend from the
Chippyash\Matrix\Exceptions\MatrixException.  The base namespace is Chippyash\Math\Matrix\Exceptions

<pre>
  MathMatrixException
  ComputationException
  NoInverseException
  SingularMatrixException
  UndefinedComputationException
  NotMarkovException
</pre>

### Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

## Where?

The library is hosted at [Github](https://github.com/chippyash/Math-Matrix).
It is available at [Packagist.org](https://packagist.org/packages/chippyash/math-matrix) as a
[Composable](https://getcomposer.org/) module

### Installation

Install [Composer] (https://getcomposer.org/)

#### For production

add

<pre>
    "chippyash/math-matrix": "~1.0"
</pre>

to your composer.json "requires" section.

#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Math-Matrix.git Math-Matrix
    cd Math-Matrix
    composer install
</pre>

To run the tests:

<pre>
    cd Math-Matrix
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>

## License

This software library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

This software library is Copyright (c) 2014-2016, Ashley Kitson, UK

A commercial license is available for this software library, please contact the author. 
It is normally free to deserving causes, but gets you around the limitation of the GPL
license, which does not allow unrestricted inclusion of this code in commercial works.

## History

V1.0.0 Initial Release - after 2 years of development - yippee!

V1.1.0 Update dependencies

V1.2.0 Add Entrywise calculations

V1.2.1 Eradicate calls to plain numeric strong types - use the factories

V1.2.2 Fix AsciiNumeric formatter - don't format strings

V1.2.3 Add link to packages

V1.3.0 Add Directed Graph from Matrix rendering

V1.4.0 Add ShiftMatrix

V1.5.0 Add Special Matrices
```
Deprecation notice: IdentityMatrix, ZeroMatrix, FunctionMatrix are
deprecated, Use the SpecialMatrix instead to create these.  I found a
problem in some of the auto conversion algorithms used in the Matrix calculator
that depended on class names.  The new convention ensures that these matrix types
are all returned as NumericMatrix objects which is more conformant with their
intended use.  I will shortly deprecate ShiftMatrix in favour of a SpecialMatrix
type instead, but as it's brand new, it probably won't effect too many.

SpecialMatrix allows for arbitrary inclusion of all sorts of matrices and the idea
comes from the Octave/Matlab world. I probably won't get round to including all those
that are provided by Octave/Matlab, but it is certainly an opportunity for others
to implement the other types provided by those libraries as required.

In due course, the version number for this library will be bumped to 2.0.0 at which
point the old classes will disappear.
```
V1.5.1 dependency update

V1.5.2 dependency update

