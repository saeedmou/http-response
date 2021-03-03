<?php
declare (strict_types = 1);

use Saeedmou\HttpResponse\HttpResponse;
use PHPUnit\Framework\TestCase;

class PublicInterfaceTest extends TestCase
{

    public function testConstructTheNewClassInstanse(): void
    {
        $this->assertInstanceOf(
            HttpResponse::class,
            new HttpResponse()
        );
    }

    
}


