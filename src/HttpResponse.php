<?php
namespace Saeedmou\HttpResponse;

class HttpResponse
{
    protected $responseModel=array(
        "status" => false,
        "message" => "Admin previleges needs!",
        "data" => "",
    );

    public function __construct()
    {
        //instantiate public child inside parent
    }

    public function CORSHeaders(): void
    {
        $this->setAccessControlAllowOrigin("*");
        header("Access-Control-Allow-Methods:  GET, PUT, DELETE, POST, OPTIONS, HEAD");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header("Content-Type: application/json; charset=UTF-8");
        header("Expires: on, 01 Jan 1970 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('HTTP/1.1 200 OK');
        http_response_code(200);
    }

    public function setAccessControlAllowOrigin(string $origin="*"){
        header("Access-Control-Allow-Origin: $origin");
    }

    public function needAdminPrevileges()
    {
        echo json_encode(
            array(
                "status" => false,
                "message" => "Admin previleges needs!",
                "data" => "",
            )
        );
        exit();
    }

    public function emptyToken()
    {
        echo json_encode(
            array(
                "status" => false,
                "message" => "Access denied, token is empty!",
                "data" => "",
            )
        );
        exit();
    }

    public function invalidToken()
    {
        echo json_encode(
            array(
                "status" => false,
                "message" => "Access denied, invalid tokken.",
                "data" => "",
            )
        );
        exit();
    }

    public function responseData(bool $status, string $message, $data, $exit = true)
    {
        // ignore_user_abort(true);
        ob_start("ob_gzhandler");
        // ob_start();
        echo json_encode(
            array(
                "status" => $status,
                "message" => $message,
                "data" => $data,
            )
        );

        if (!$exit) {
            header('Connection: close');
            header('Content-Length: ' . ob_get_length());
        }

        ob_end_flush();
        ob_flush();
        flush();

        if ($exit) {
            exit();
        }
    }

    public function responseDataCompressed(bool $status, string $message, $data, $exit = true)
    {
        // ignore_user_abort(true);
        header('Content-Encoding: gzip');
        ob_start("ob_gzhandler");
        // ob_start();
        echo gzencode(json_encode(
            array(
                "status" => $status,
                "message" => $message,
                "data" => $data,
            )
        ));

        if (!$exit) {
            header('Connection: close');
            header('Content-Length: ' . ob_get_length());
        }

        ob_end_flush();
        ob_flush();
        flush();

        if ($exit) {
            exit();
        }
    }

    public function responseJsonFile($content, $fileName)
    {
        ob_get_clean();
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $fileName . "\"");
        header('Content-Length: ' . (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content)));
        ob_clean();
        flush();
        echo $content;
        exit();
    }

    public function responseFile($content, $fileName)
    {
        ob_get_clean();
        header("Expires: on, 01 Jan 1970 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content)));
        header("Content-disposition: attachment; filename=\"" . $fileName . "\"");
        ob_clean();
        flush();
        echo $content;
        exit();
    }

}
