<?php

namespace Controller\Config;

require_once __DIR__ . '\..\..\..\vendor\autoload.php';

class Res
{
    private $body;
    private $headers;
    private $cookies = [];
    private $status_code;

    public function saveDataAndSend(array $body,array $header,int $status){
        setBody($body);
        setHeaders($header);
        setStatus_code($status);
        send();
    }

    public function send()
    {
        foreach ($this->getCookies() as $key => $value) {
            setcookie($key, $value);
        }

        http_response_code($this->getStatus_code());
        echo json_encode($this->getBody());
    }

    /**
     * @return mixed
    */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body 
     * @return self
    */
    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
    */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers 
     * @return self
    */
    public function setHeaders($headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return mixed
    */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param mixed $cookies 
     * @return self
    */
    public function addCookie($key, $value)
    {
        $this->cookies[$key] = $value;
        return $this;
    }

    public function getStatus_code()
    {
        return $this->status_code;
    }

    public function setStatus_code($status_code)
    {
        $this->status_code = $status_code;
    }
}
