<?php

namespace Http;

use Error\APIException;

class Request
{
    private string $resource; // o recurso solicitado
    private ?string $id; // o identificador da instância do recurso (pode ser um string ou null)
    private ?string $subCollection; // o nome da subcoleção (pode ser um string ou null)
    private string $method; // o método (verbo) HTTP da requisição
    private array $body; // array com os parâmetros enviados via body (só aceita JSON)
    private array $query; // array com os parâmetros enviados via querystring

    //construtor
    function __construct(string $uri, string $method, ?string $rawBody = null)
    {
        //separa apenas o path da URL
        $path = parse_url($uri, PHP_URL_PATH);

        //cria uma expressão regular com um padrão que admite
        //qualquer texto (para o início da URL) até encontrar /api/
        //tudo o que vier depois será capturado pela subexpressão
        //especificada por (.*) e referenciada por $1. 
        $pattern = "/.*\/api\/(.*)$/";

        //por exemplo, para http://localhost/xyz/api/students/123 
        //$route recebe students/123
        $route = preg_replace($pattern, "$1", $path);

        //cria um array com os segmentos de $route separados por /
        $segments = explode('/', $route);

        $this->resource = $segments[0]; //o primeiro segmento é o recurso
        $this->id = isset($segments[1]) && $segments[1] !== '' ? $segments[1] : null; //o segundo segmento é id (se não houver, nulo)
        $this->subCollection = isset($segments[2]) && $segments[2] !== '' ? $segments[2] : null; //o terceito segmento é a subcoleção (se não houver, nulo)
        //obtém o método (verbo) HTTP da requisição
        $this->method = $method;

        //cria um array com os parâmetros da querystring
        $this->query = [];
        $queryString = parse_url($uri, PHP_URL_QUERY) ?? ""; //pega só a querystring
        parse_str($queryString, $this->query); //gera um array associativo

        //verifica o corpo da requisição
        if ($rawBody) {
            //decodifica o corpo que deve vir no formato JSON
            //gera um array associativo
            $this->body = json_decode($rawBody, true) ?? [];

            //caso não venha em JSON ou seja um JSON inválido, gera uma exceção
            if (json_last_error() !== JSON_ERROR_NONE)
                throw new APIException("Invalid request body!", 400);
        } else {
            //se não houver um corpo, devolve um array vazio
            $this->body = [];
        }
    }

    //métodos GET
    public function getResource(): string
    {
        return $this->resource;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSubCollection(): ?string
    {
        return $this->subCollection;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}