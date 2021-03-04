# http-response

# Installation with Composer
## Add repository
Add Repository to "composer.json"
```shell
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/saeedmou/http-response"
        }
    ],
```

## Require
Require this package with the following composer cli command

```shell
composer require http-response
```

# How to use

Add composer autoload at the beginig of the script
```php
require_once("../vendor/autoload.php");
```

use the namespace
```php
use Saeedmou\HttpResponse\HttpResponse;
```

create a new Instance of the library
```php
$httpResponse=new HttpResponse();
```

now send headers
```php
$httpResponse->sendHeaders();
```

set the output data
```php
$httpResponse->setResponseParameters(true,"Test",["root",array("data1"=>null,"data2"=>"test","data3"=>55)],false);
```
or 
```php
$array=
    array(
        "status"=> true,
        "message"=> "Test",
        "data"=>
            ["root",
            array(
                "data1"=>null,
                "data2"=>"test",
                "data3"=>66
                )
            ]
    );
$httpResponse->setResponseArray($array);
```

now send the data as json
```php
$httpResponse->responseJson(true);
```

and output will be like this

```json
{
    "status": true,
    "message": "Test",
    "data": [
        "root",
        {
            "data1": null,
            "data2": "test",
            "data3": 66
        }
    ]
}
```

and the Http response header

```text
HTTP/1.1 200 OK
Host: localhost:3000
Date: Thu, 04 Mar 2021 14:30:06 GMT
Connection: close
X-Powered-By: PHP/7.3.26
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods:  GET, POST, OPTIONS, HEAD
Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept
Content-Type: application/json; charset=UTF-8
Expires: on, 01 Jan 1970 00:00:00 GMT
Last-Modified: Thu, 04 Mar 2021 14:30:06 GMT
Cache-Control: no-store, no-cache, must-revalidate, max-age=0
Cache-Control: post-check=0, pre-check=0
Pragma: no-cache
```