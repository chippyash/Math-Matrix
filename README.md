# chippyash/Math-Matrix

Since the [JAMA library](http://www.phpmath.com/build02/JAMA/downloads/), there
has not been been a library to my knowledge that allows PHP devs to simply
incorporate arithmetic Matrix functionality within an application.

If you are using reasonably small matrices then the complexity of having to compile in external
[Fortran or C based](http://en.wikipedia.org/wiki/LAPACK) libraries
is something you can do without. And even when you do, it transpires the PHP
bindings are limited.

You need speed - PHP is never going to do it for you on big Matrices, start compiling.
For everything else, give this a go.

## What?

This library aims to provide arithmetic matrix functionality in the most efficient way
possible using PHP given that:

*  Everything has a test case
*  It's PHP 5.4+

The library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

## Why?

This adds maths to the chippyash/Matrix library giving you the ability to create
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

Check out [chippyash/Strong-Type](https://github.com/chippyashl/Strong-Type) for strong type including numeric,
rational and complex type support

Check out [chippyash/Math-Type-Calculator](https://github.com/chippyash/Math-Type-Calculator) for arithmetic operations on aforementioned strong types

## How

### Coding Basics

In PHP terms a matrix is an array of arrays, 2 dimensional i.e

-  [[]]

As with any TDD application, the tests tell you everything you need to know about
the SUT.  Read them!  However for the short of tempered amongst us the salient
points are:

The library extends the chippyash/Matrix library, so anything you can do on a basic
matrix, you can do with a numeric matrix.  The library utilises the chippyash/Strong-Type
strong types.

Three basic Matrix types are supplied

* NumericMatrix: containing int, float, IntType, WholeIntType, NaturalIntType,
FloatType, RationalType and ComplexType data items
* RationalMatrix: containing only RationalType data items
* ComplexMatrix: containing only ComplexType data items

The NumericMatrix is fine for general purpose work, but not if you want prove that
the inverse(M) * M = Identity.  For that you'll need the RationalMatrix which is
far more arithmetically stable.  This is also the matrix that will benefit from
forthcoming support of PHP's various maths extension libraries in the chippyash/Strong-Type
library.

The ComplexMatrix fully supports the chippyash\Type\Numeric\Complex\ComplexType.

Both the RationalMatrix and ComplexMatrix extend the NumericMatrix.

Creating a numeric type matrix is straightforward:

<pre>
    use chippyash\Math\Matrix\NumericMatrix;
    use chippyash\Math\Matrix\RationalMatrix;
    use chippyash\Math\Matrix\ComplexMatrix;
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

Create a numeric matrix as a result of applying a function.

<pre>
    $f = function($row, $col) {return new IntType($row * $col);};
    $mA = new FunctionMatrix($f, new IntType(3), new IntType(4));
</pre>

#### IdentityMatrix

Create a NumericMatrix Identity matrix

<pre>
    //create 4x4 identity matrix with integer entries
    $mA = new IdentityMatrix(new IntType(4));
    //or more usually, use the factory method
    $mA - IdentityMatrix::numericIdentity(new IntType(4));
</pre>

Create rational and complex identity matrices using factory methods:

<pre>
    $mR = IdentityMatrix::rationalIdentity(new IntType(4));
    $mC = IdentityMatrix::complexIdentity(new IntType(4));
</pre>

#### ZeroMatrix

Create a NumericMatrix with all entries set to zero

<pre>
    //create 2 x 4 zero matrix
    $mZ = new ZeroMatrix(new IntType(2), new IntType(4));
</pre>

####  Numeric matrices have additional attributes

*  IsComplex: boolean - Is the matrix instanceof ComplexMatrix?
*  IsIdentity: boolean - Is the matrix and identity matrix?
*  IsNonsingular: boolean - Is the matrix non singular
*  IsNumeric: boolean - Is the matrix instanceof NumericMatrix?
*  IsRational: boolean -  Is the matrix instanceof RationalMatrix?

Remember, you can use the is() method to test for an attribute on a matrix.

####  Matrices can be computed

*  Computations always return a matrix.
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Computations implement the chippyash\Math\Matrix\Interfaces\ComputationInterface
*  Computations work with scalar values or other matrices.  Non scalar values will throw an exception

On the whole, computations, will work with any scalar but:

**All matrix computations follow the natural laws**

*  you can't divide by zero
*  dividing a matrix by a non invertible matrix is like 1/0 - oops!
*  using float, int and rational data items will work together
*  using complex number data items: all items have to be complex

The following computations are provided (using the magic invoke interface method):

*  'Add\Scalar' : add scalar value to the matrix
*  'Add\Matrix' : add a matrix to the matrix
*  'Sub\Scalar' : subtract scalar value from the matrix
*  'Sub\Matrix' : subtract a matrix from the matrix
*  'Mul\Scalar' : multiply matrix by scalar value
*  'Mul\Matrix' : multiply matrix by another matrix
*  'Div\Scalar' : divide matrix by scalar value
*  'Div\Matrix' : divide matrix by another matrix - see notes at head of this readme

<pre>
    $mC = $mA('Mul\Matrix', $mB);
    //same as
    $fMul = new chippyash\Math\Matrix\Computation\Mul\Matrix();
    $mC = $mA->compute($fMul, $mB)
    //same as
    $mC = $fMul->compute($mA, $mB);
</pre>

#### You can derive other information from a matrix

*  Derivatives always return a numeric result
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Derivatives implement the chippyash\Math\Matrix\Interfaces\DerivativeInterface

Two derivatives are currently supplied.

*  Determinant

<pre>
    $det = $mA("Determinant");
    //same as
    $fDet = new chippyash\Math\Matrix\Derivative\Determinant();
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

#### Additional transformations are supported by numeric matrices

*  Invert - Returns the inverted matrix or throws an exception if not possible.
The current inversion method relies on determinants and as noted, this is only
currently feasible for matrices up to 20x20

<pre>
    try {
        $mI = $mA('Invert');
        //same as
        $fInvert = new chippyash\Math\Matrix\Transformation\Invert();
        $mI = $mA->transform($fInvert);
        //same as
        $mI = $fInvert($mA);
    } catch (chippyash\Math\Matrix\Exceptions\ComputationException $e) {
        //cannot invert
    }
</pre>

If you want to break the 20x20 limit, you can do the following:

<pre>
    $det = new chippyash\Math\Matrix\Derivative\Determinant();
    $det->tune('luLimit', 40); //or whatever you are prepared to put up with
    $mI = $mA('Invert');
</pre>
#### Matrices can be decomposed

*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Decompositions return a decomposition object, from which you can access the various parts of the decomposition.
*  Decompositions implement the chippyash\Math\Matrix\Interfaces\DecompositionInterface

The library currently supports:

*  LU Decomposition
*  Gauss Jordan Elimination

**LU**
<pre>
    $lu = $mA('Lu');
    //same as
    $fLu = new chippyash\Math\Matrix\Decomposition\Lu()
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
    $fElim = new chippyash\Math\Matrix\Decomposition\GaussJordonElimination()
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

\chippyash\Math\Matrix\Formatter\AsciiNumeric

It extends the \chippyash\Matrix\Formatter\Ascii.  An additional option 'outputType'
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

#### Exception handling

As matrix maths can throw up problems, particularly when inverting or decomposing,
it is always a good idea to wrap whatever you are doing in a try - catch block.
The following exceptions are supported by the library.  They all extend from the
chippyash\Matrix\Exceptions\MatrixException.  The base namespace is chippyash\Math\Matrix\Exceptions


<pre>
MathMatrixException
  ComputationException
  NoInverseException
  SingularMatrixException
  UndefinedComputationException
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

The library is only available at present in dev-master

add

<pre>
    "chippyash/matrix": "dev-master"
</pre>

to your composer.json "requires" section.  You may need to add

<pre>
    "minimum-stability":"dev"
</pre>

to your composer.json file

#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Math-Matrix.git Matrix
    cd Matrix
    composer install
</pre>

To run the tests:

<pre>
    cd Matrix
    vendor/bin/phpunit -c Test/phpunit.xml Test/
</pre>

