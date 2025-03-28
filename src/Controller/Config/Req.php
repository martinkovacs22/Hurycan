<?php

namespace Controller\Config;

require_once __DIR__ . '\..\..\..\vendor\autoload.php';



class Req
{
	static public $body;
	static public $fun;
	static public $method;
	static public $funNum = 5;
	static public $token;

	static public function getReqBody(): array
	{
		try {
			return json_decode(file_get_contents('php://input'), true);
		} catch (\Throwable $th) {
			return array();
		}
	}

	static public function getIP(){
		return $_SERVER['REMOTE_ADDR'];
	}

	static public function getReqFun(): string
	{
		try {
			return isset(explode("/", $_SERVER['REQUEST_URI'])[Req::$funNum]) ? explode("/", $_SERVER['REQUEST_URI'])[Req::$funNum] : "";
		} catch (\Throwable $th) {
			return "";
		}
	}
	static public function getReqMethod(): string
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	static public function getReqToken(): string |null
	{
		return isset(getallheaders()["token"]) ? getallheaders()["token"] : null;
	}

}

