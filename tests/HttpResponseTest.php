<?php declare(strict_types=1);
error_reporting(E_ALL);
// ini_set("display_errors", 1);
use PHPUnit\Framework\TestCase;
use Saeedmou\HttpResponse\HttpResponse;


final class HttpResponseTest extends TestCase
{
    private $response;

    protected function setUp(): void
    {
        $this->response = new HttpResponse();
    }

    protected function tearDown(): void
    {
        unset($this->response);
    }
    
    public function testConstructTheNewClassInstanse(): void
    {
        $this->assertInstanceOf(
            HttpResponse::class,
            $this->response
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testConstructTheNewClassInstanseWithParameters(): void
    {
        $httpResponse=new HttpResponse(true,"Test",["root",array("data1"=>null,"data2"=>"test","data3"=>55)],false);
        $this->assertInstanceOf(HttpResponse::class,$httpResponse);
        $this->assertEquals("root", $httpResponse->data[0]);
        $this->assertEquals(null, $httpResponse->data[1]["data1"]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHeader():void{

    }

}


