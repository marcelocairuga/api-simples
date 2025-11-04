<?php
namespace Controller;

use Error\APIException;
use Http\Request;
use Http\Response;
use Service\StudentService;

class StudentController
{
    private StudentService $service;

    public function __construct()
    {
        //cria o service de estudantes
        $this->service = new StudentService();
    }

    public function processRequest(Request $request)
    {
        //recupera o método e o id da requisição
        $id = $request->getId();
        $method = $request->getMethod();

        //para as rotas que possuem um id (/students/id):
        if ($id !== null) {
            switch ($method) { //conforme o método
                case "GET":
                    //busca o estudane pelo seu id 
                    $response = $this->service->getStudentById($id);
                    //retorna o estudante encontrado no formato JSON
                    Response::send($response);
                    break;
                case "PUT":
                    //verifica se o corpo da requisição está correto
                    $student = $this->validateBody($request->getBody(), $method);
                    //atualiza os dados do estudante
                    $student["id"] = $id;
                    $response = $this->service->updateStudent(...$student);
                    //retorna o estudante atualizado no formato JSON
                    Response::send($response);
                    break;
                case "PATCH":
                    //verifica se o corpo da requisição está correto
                    $student = $this->validateBody($request->getBody(), $method);
                    //atualiza os dados no banco
                    $student["id"] = $id;
                    $response = $this->service->setStudentSemester(...$student);
                    //retorna o estudante atualizado no formato JSON
                    Response::send($response);
                    break;
                case "DELETE":
                    //exclui o estudante especificado pelo id
                    $this->service->deleteStudent($id);
                    Response::send(null, 204);
                    break;
                default:
                    //para qualquer outro método, gera uma exceção
                    throw new APIException("Method not allowed!", 405);
            }
        } else { //para as rotas que não possuem um id (/students)
            switch ($method) {
                case "GET":
                    //obtem o parâmetro de busca da querystring (se houver)
                    $name = $request->getQuery()["name"] ?? null;
                    //busca o conjunto de estudantes
                    $response = $this->service->getStudents($name);
                    //retorna o conjunto de estudantes encontrado no formato JSON
                    Response::send($response);
                    break;
                case "POST":
                    //verifica se o corpo da requisição está correto
                    $student = $this->validateBody($request->getBody(), $method);
                    //cria um novo estudantes com os dados informados no corpo da requisição
                    $response = $this->service->createNewStudent(...$student);
                    //retorna o estudante criado no formato JSON
                    Response::send($response, 201);
                    break;
                default:
                    //para qualquer outro método, gera uma exceção
                    throw new APIException("Method not allowed!", 405);
            }
        }
    }

    private function validateBody(array $body, string $method): array
    {
        //cria um array para os dados do estudantes que vierem no body
        $student = [];

        //Se o método for PATCH, precisa verificar apenas o semester
        if ($method !== "PATCH") {
            //verifica se o nome do estudante foi informado
            if (!isset($body["name"]))
                throw new APIException("Name is required!", 400);

            //verifica se o email do estudante foi informado
            if (!isset($body["email"]))
                throw new APIException("Email is required!", 400);

            //verifica se o id do curso do estudante foi informado
            if (!isset($body["courseId"]))
                throw new APIException("CourseId is required!", 400);

            //verifica se o id do curso do estudante é numérico
            if (!is_numeric($body["courseId"]))
                throw new APIException("CourseId must be a number!", 400);

            //adiciona os dados já validados
            $student["name"] = $body["name"];
            $student["email"] = $body["email"];
            $student["courseId"] = (int) $body["courseId"];
        }

        //verifica se o período do estudante foi informado
        if (!isset($body["semester"]))
            throw new APIException("Semester is required!", 400);

        //verifica se o período do estudante é numérico
        if (!is_numeric($body["semester"]))
            throw new APIException("Semester must be a number", 400);

        //adiciona o período
        $student["semester"] = (int) $body["semester"];

        //retorno o array criado
        return $student;
    }
}