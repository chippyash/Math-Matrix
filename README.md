# TBC - Library not yet ready for production use

Watch this project for readiness updates.  It is being actively developed but is
unstable at present.

# chippyash/Math-Matrix

Since the [JAMA library](http://www.phpmath.com/build02/JAMA/downloads/), there
has not been been a library to my knowledge that allows PHP devs to simply
incorporate arithmetic Matrix functionality within an application.

If you are using reasonably small matrices (i.e  up to a couple of thousand rather
than many of thousands of entries) then the complexity of having to compile in external
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

The current library covers basic matrix manipulation (inverses being worked on at
the moment which means matrix division is pending.) The library will cover most
well known matrix transformations and derivatives, enabling those with a modicum
of matrix maths to construct a good deal of common algabraic functionality .

If you want more, either suggest it, or better still, fork it and provide a pull request.

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

* NumericMatrix: containing int, float, IntType, WholeIntType, NaturalIntType, FloatType & RationalType data items
* RationalMatrix: containing only RationalType data items
* ComplexMatrix: containing only ComplexType data items

####  Numeric matrices have additional attributes

TBC

####  Matrices can be computed

*  Computations always return a matrix.
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Computations implement the chippyash\Math\Matrix\Interfaces\ComputationInterface
*  Computations work with scalar values.  Non scalar values will throw an exception

On the whole, computations, (transformations etc) will work with any scalar but:

**All matrix computations follow the natural laws**

*  you can't divide by zero
*  dividing a matrix by a non invertible matrix is like 1/0 - oops!
*  using float, int and rational data items will work together
*  using complex number data items: all items have to be complex

####  Matrices can be decomposed

*  Decomposition is an extended form of Transformation
*  Decomposition always returns the decomposition class
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Computations implement the chippyash\Math\Matrix\Interfaces\DecompositionInterface

<pre>
    $d = $mA("Decomposition\\Lu")
    //same as
    $decomp = new Math\Matrix\Transformation\Decomposition\Lu();
    $d = $decomp($mA);
</pre>

The thing about decompositions is that they have multiple products so that the
Lu decomposition for instance provides:

*  LU
*  L
*  U
*  Det
*  PivotVector
*  PermutationMatrix

So assuming you are only interested in the L product you can write

<pre>
    $L = $mA("Decomposition\\Lu")->L
</pre>

Decomposition transformations all support dereferencing of their
products.

####  You can derive other information from a matrix

*  Derivatives always return a numeric result
*  The original matrix is untouched
*  You can use the magic __invoke functionality
*  Derivatives implement the chippyash\Math\Matrix\Interfaces\DerivativeInterface

<pre>
    $det = $mA("Determinant");
    //same as
    $fDet = new Math\Matrix\Derivative\Determinant();
    $det = $fDet($mA);
</pre>

#### The magic invoke methods allow you to write in a functional way

For example (taken from Transformation\Cofactor):

<pre>
        $fC = new Colreduce();
        $fR = new Rowreduce();
        //R(C(mA))
        return $fR($fC($mA,[$col]),[$row]);
</pre>

or this (from Transformation\Colreduce):

<pre>

        $fT = new Transpose();
        $fR = new Rowreduce();

        return $fT($fR($fT($mA), [$col, $numCols]));
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

The library is hosted at [Github](https://github.com/chippyash/Math-Matrix). It will
appear at [Packagist.org](https://packagist.org/) in due course as a
[Composable](https://getcomposer.org/) module

### Installation

Install [Composer] (https://getcomposer.org/)

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Math-Matrix.git Matrix
    cd Matrix
    composer update
</pre>

To run the tests:

<pre>
    cd Matrix
    vendor/bin/phpunit -c Test/phpunit.xml Test/
</pre>

