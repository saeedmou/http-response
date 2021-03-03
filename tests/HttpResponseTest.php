<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Saeedmou\HttpResponse\HttpResponse;


final class HttpResponseTest extends TestCase
{

    public function testConstructTheNewClassInstanse(): void
    {
        $this->assertInstanceOf(
            HttpResponse::class,
            new HttpResponse()
        );
    }

}


