<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Cls\Error;
use InvalidArgumentException;

class ErrorTest extends TestCase
{

    public function testError(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $obj = new Error(0);
        $this->assertEquals(2, $obj->getNum());

    }
}