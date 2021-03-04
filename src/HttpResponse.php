<?php
namespace Saeedmou\HttpResponse;

class HttpResponse
{
    public $status = false;
    public $message = "";
    public $data = null;
    public $exit = true;

    public function __construct(bool $status = false, string $message = "",  $data = null, bool $exit = true)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->exit = $exit;
        return $this;
        //instantiate public child inside parent
    }

    public function setResponse(bool $status = false, string $message = "",  $data = null, bool $exit = true)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->exit = $exit;
        return $this;
    }

    public function jsonCORSHeaders(): void
    {
        $this->setOrigin("*");
        $this->setAllowMethods();
        $this->setAllowHeaders();
        $this->setContentType();
        $this->setNoCache();
        $this->sendOK();
    }

    public function setOrigin(string $origin = "*")
    {
        header("Access-Control-Allow-Origin: $origin");
    }

    public function setAllowMethods()
    {
        header("Access-Control-Allow-Methods:  GET, PUT, DELETE, POST, OPTIONS, HEAD");
    }

    public function setAllowHeaders()
    {
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    }

    public function setNoCache()
    {
        header("Expires: on, 01 Jan 1970 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    public function sendOK()
    {
        header('HTTP/1.1 200 OK');
        http_response_code(200);
    }

    public function setContentType(string $type = "json")
    {
        switch ($type) {
            case 'value':
                # code...
                break;

            default:
                header("Content-Type: application/json; charset=UTF-8");
                break;
        }
    }

    public function responseJson(bool $compressed = false)
    {
        $outputArray =
        array(
            "status" => $this->status,
            "message" => $this->message,
            "data" => $this->data,
        );

        if ($compressed) {
            echo json_encode($outputArray);
        } else {
            header('Content-Encoding: gzip');
            ob_start("ob_gzhandler");
            // ob_start();
            echo gzencode(json_encode($outputArray));
            // if (!$exit) {
            header('Connection: close');
            header('Content-Length: ' . ob_get_length());
            // }
            ob_end_flush();
            ob_flush();
            flush();
        }

        if ($exit) {
            // exit();
        }
        return;
    }

    public function needAdminPrevileges()
    {
        $this->setResponse(false, "Admin previleges needs!", null, true);
        $this->responseJson();
        exit();
    }

    public function emptyToken()
    {
        $this->setResponse(false, "Access denied, token is empty!", null, true);
        $this->responseJson();
        exit();
    }

    public function invalidToken()
    {
        $this->setResponse(false, "Access denied, invalid tokken.", null, true);
        $this->responseJson();
        exit();
    }

    public function responseJsonAsFile($content, $fileName)
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
        // header("Expires: on, 01 Jan 1970 00:00:00 GMT");
        // header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        // header("Cache-Control: post-check=0, pre-check=0", false);
        // header("Pragma: no-cache");
        $this->setNoCache();
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
