<?php

namespace Error;

use Exception;

//Para exceções personalizadas dessa API
//Estendem a classe Exception
class APIException extends Exception
{
    function __construct(string $message, int $code = 500)
    {
        //recebe a mensagem personalizada e o código que será o código de status HTTP
        //se o código não for informado, usa o 500 - Internal Server Error
        //chama o construtor da superclasse
        parent::__construct($message, $code);
    }
}

