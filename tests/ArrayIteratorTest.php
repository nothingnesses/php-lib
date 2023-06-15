<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

use Eris\Generator;
use PHPUnit\Framework\TestCase;
use Nothingnesses\Lib\Classes\ArrayIterator;

class ArrayIteratorTest extends TestCase {
	use Eris\TestTrait;

	public function test_to_array() {
		$this->forAll(Generator\seq(Generator\bool()))->then(function ($array) {
			$this->assertEquals($array, ArrayIterator::new($array)->to_array());
		});
	}

	public function test_reverse() {
		$this->forAll(Generator\seq(Generator\bool()))->then(function ($array) {
			$this->assertEquals(array_reverse($array), ArrayIterator::new($array)->reverse()->to_array());
		});
	}
}
