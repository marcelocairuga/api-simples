<?php

// executa as configurações iniciais (autoload, tratamento de erros etc)
require_once 'src/config.php';

use Controller\StudentController;
use Controller\CourseController;
use Http\Request;
use Http\Response;
use Error\APIException;


//cria um objeto para armazenar os principais dados da requisição
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER["REQUEST_METHOD"];
$body = file_get_contents("php://input");
$request = new Request($uri, $method, $body);

switch ($request->getResource()) { //conforme o recurso solicitado
    case 'students':
        //para todas as rotas iniciadas por /students
        $studentsController = new StudentController();
        $studentsController->processRequest($request);
        break;
    case 'courses':
        //para todas as rotas iniciadas por /courses
        $coursesController = new CourseController();
        $coursesController->processRequest($request);
        break;
    case null:
        //para a raiz (rota /)
        $endpoints = [
            "GET /api/students",
            "GET /api/students?name=name",
            "POST /api/students",
            "GET /api/students/:id",
            "PUT /api/students/:id",
            "PATCH /api/students/:id",
            "DELETE /api/students/:id",
            "GET /api/courses",
            "GET /api/courses?name=name",
            "POST /api/courses",
            "GET /api/courses/:id",
            "PUT /api/courses/:id",
            "DELETE /api/courses/:id",
            "GET /api/courses/:id/students",
        ];
        Response::send(["endpoints" => $endpoints]);
        break;
    default:
        //para todos os demais casos, recurso não encontrado
        throw new APIException("Resource not found!", 404);
}