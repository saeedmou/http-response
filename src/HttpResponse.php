<?php
namespace Saeedmou\HttpResponse;

class HttpResponse
{
    public $status = false;
    public $message = "";
    public $data = null;
    public $exit = true;
    public $apiVersion = "0";

    public function __construct(bool $status = false, string $message = "", $data = null, bool $exit = true)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->exit = $exit;
        return $this;
        //instantiate public child inside parent
    }

    public function setResponseParameters(bool $status = false, string $message = "", $data = null, bool $exit = true)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->exit = $exit;
        return $this;
    }

    public function setResponseArray($response, bool $exit = true)
    {
        $this->status = $response["status"];
        $this->message = $response["message"];
        $this->data = $response["data"];
        $this->exit = $exit;
        return $this;
    }

    public function sendHeaders($output = "json", $CORS = true): void
    {
        if ($CORS) {
            $this->setOrigin("*");
        }
        $this->setAllowMethods();
        $this->setAllowHeaders();
        $this->setContentType($output);
        $this->setNoCache();
        $this->sendOK();
    }

    public function setOrigin(string $origin = "*")
    {
        header("Access-Control-Allow-Origin: $origin");
    }

    public function setAllowMethods(bool $get = true, bool $post = true, bool $put = false, bool $delete = false, bool $options = true, bool $head = true)
    {
        $methods = [];
        ($get) ? $methods[] = "GET" : $methods = $methods;
        ($post) ? $methods[] = "POST" : $methods = $methods;
        ($options) ? $methods[] = "OPTIONS" : $methods = $methods;
        ($head) ? $methods[] = "HEAD" : $methods = $methods;
        ($put) ? $methods[] = "PUT" : $methods = $methods;
        ($delete) ? $methods[] = "DELETE" : $methods = $methods;
        $methodesStr = implode(", ", $methods);
        header("Access-Control-Allow-Methods:  $methodesStr");
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

    public function sendJson(bool $compressed = false)
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

        if ($this->exit) {
            exit();
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

    public function sendJsonAsFile($fileName)
    {
        $outputArray =
        array(
            "status" => $this->status,
            "message" => $this->message,
            "data" => $this->data,
        );
        $content = json_encode($outputArray);
        ob_get_clean();
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $fileName . "\"");
        header('Content-Length: ' . (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content)));
        echo $content;
        flush();
        ob_clean();
        exit();
    }

    public function sendContentAsFile($content, $fileName)
    {
        ob_get_clean();
        $this->setNoCache();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Content-Length: ' . (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content)));
        header("Content-disposition: attachment; filename=\"" . $fileName . "\"");
        echo $content;
        flush();
        ob_clean();
        exit();
    }

    public function HTTPToHTTPS()
    {
        if (
            $_SERVER['HTTP_HOST'] != 'localhost' &&
            (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))) {
            $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
            exit();
        }
    }

}
