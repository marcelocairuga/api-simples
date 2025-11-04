<?php
namespace Controller;

use Error\APIException;
use Http\Request;
use Http\Response;
use Service\CourseService;

class CourseController
{
    private CourseService $service;

    public function __construct()
    {
        //cria o service de cursos
        $this->service = new CourseService();
    }

    public function processRequest(Request $request): void
    {
        //recupera alguns parâmetros da requisição
        $id = $request->getId();
        $method = $request->getMethod();
        $subCollection = $request->getSubCollection();  //para o caso de /courses/id/students

        //Para as rotas que possuem id, tipo /courses/id
        if ($id !== null) {
            //verifica se o id é numérico
            if (!is_numeric($id))
                throw new APIException("Course Id must be a number!", 400);

            switch ($method) { //conforme o método
                case "GET":
                    if ($subCollection === null) {
                        //para o caso GET /courses/id
                        //busca o curso pelo id
                        $response = $this->service->getCourseById($id);
                        //retorna o curso encontrado no formato JSON
                        Response::send($response);
                    } else if ($subCollection === "students") {
                        //para o caso GET /courses/id/students
                        //busca todos o estudante para o curso informado
                        $response = $this->service->getCourseStudents($id);
                        //retorna o conjunto de estudantes encontrados no formato JSON
                        Response::send($response);
                    } else {
                        //demais subcollections não são válidas
                        throw new APIException("Resource not found!", 404);
                    }
                    break;
                case "PUT":
                    //verifica se o corpo da requisição está correto
                    $course = $this->validateBody($request->getBody());
                    //atualiza os dados do curso
                    $course["id"] = $id;
                    $response = $this->service->updateCourse(...$course);
                    //retorna o curso atualizado no formato JSON
                    Response::send($response);
                    break;
                case "DELETE":
                    //exclui o curso especificado pelo id
                    $this->service->deleteCourse($id);
                    Response::send(null, 204);
                    break;
                default:
                    //para qualquer outro método, gera uma exceção
                    throw new APIException("Method not allowed!", 405);
            }
        } else {
            switch ($method) {
                case "GET":
                    //obtem o parâmetro de busca da querystring (se houver)
                    $name = $request->getQuery()["name"] ?? null;
                    //busca o conjunto de cursos
                    $response = $this->service->getCourses($name);
                    //retorna o conjunto de cursos no formato JSON
                    Response::send($response);
                    break;
                case "POST":
                    //verifica se o corpo da requisição está correto
                    $course = $this->validateBody($request->getBody());
                    //cria o novo curso
                    $response = $this->service->createNewCourse(...$course);
                    //retorna o curso criado no formato JSON
                    Response::send($response, 201);
                    break;
                default:
                    //para qualquer outro método, gera uma exceção
                    throw new APIException("Method not allowed!", 405);
            }
        }
    }

    private function validateBody(array $body): array
    {
        //verifica se o nome do curso foi informado
        if (!isset($body["name"]))
            throw new APIException("Property name is required!", 400);

        //verifica se o número de períodos do curso foi informado
        if (!isset($body["semesters"]))
            throw new APIException("Property semesters is required!", 400);

        //verifica se o número de períodos do curso é numérico
        if (!is_numeric($body["semesters"]))
            throw new APIException("Property semesters must be a number", 400);

        //cria um array com os dados do curso
        $course = [];
        $course["name"] = trim($body["name"]);
        $course["semesters"] = (int) $body["semesters"];

        //retorna o array criado
        return $course;
    }
}