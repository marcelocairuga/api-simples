<?php

namespace Http;

class Response {

    public static function send(mixed $content, int $statusCode = 200): void {
        //define o cabeçalho Content-Type como application/json
        header('Content-Type: application/json');
        http_response_code($statusCode);

        if ($content === null) return;
        //converte o conteúdo para JSON e envia como resposta
        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to encode JSON']);
            exit;
        }
        echo $json;
    }
}