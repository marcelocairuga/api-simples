<?php

function autoload(string $className)
{
    //$classname possui tanto o namespace quanto o nome da classe
    //exemplo: Model\Student
    //assim precisa trocar \ por / para formar o caminho ..../Model/Student.php
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    //define o caminho para o arquivo
    $file = __DIR__ . '/' . $className . '.php';

    // Verifica se o arquivo existe
    if (!file_exists($file)) {
        throw new Exception("Class not found: {$className}");
    }

    // Inclui o arquivo
    require_once $file;
}

//registra a função autoload para ser responsável por carregar
//todos os arquivos das classes que forem sendo utilizadas 
spl_autoload_register('autoload');

use Error\APIException;
use Http\Response;

function exceptionHandler(\Throwable $exception)
{
    //$exception é objeto da classe Throwable
    //assim, adimite objetos da classe Error e Exception, que herdam de Throwable
    //bem como objetos da classe APIException, que herda de Exception

    //objetos da classe APIException chegarão aqui com mensagens de erro
    //e códigos personalizados, que nós programamos, pois eram previstos

    //demais objetos chegarão com mensagens e códigos do próprio PHP,
    //por isso devemos alterá-lo antes de encaminhar a resposta

    if ($exception instanceof APIException) {
        //Para as exceções previstas e geradas na própria API
        Response::send(['message' => $exception->getMessage()], $exception->getCode());
    } else {
        //Para as exceções não previstas, geradas pelo PHP
        // print_r($exception); //para testes e debug
        Response::send(['message' => 'Unable to process this request!'], 500);
    }
}

//registra a função exceptionHandler() para ser responsável por tratar
//todas as exceções e erros não capturados
set_exception_handler('exceptionHandler');

//registra a função handleError() para ser responsável por tratar
//erros do PHP, convertendo-os em exceções
function handleError($severity, $message, $file, $line)
{
    // Converte erros em exceções
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler('handleError');