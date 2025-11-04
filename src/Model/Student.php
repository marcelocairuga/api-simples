<?php

namespace Model;

use JsonSerializable;

class Student implements JsonSerializable
{
    //implementando essa interface, a função json_enconde() terá acesso
    //aos membros privados do objeto através do método jsonSerialize().

    //atributos do estudante
    private string $id;
    private string $name;
    private string $email;
    private int $courseId;
    private int $semester;

    //construtor
    public function __construct(string $name, string $email, int $courseId, int $semester, ?string $id = null)
    {
        //o parâmetro id do construtor é opcional
        //se não informado, entra com valor nulo
        //nesse caso, gera-se um id no formato estabelecido
        $this->id = $id ?? $this->createId();
        $this->name = $name;
        $this->email = $email;
        $this->courseId = $courseId;
        $this->semester = $semester;
    }

    //métodos GET
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSemester(): int
    {
        return $this->semester;
    }

    public function getCourseId(): int
    {
        return $this->courseId;
    }

    //métodos SET
    public function setId(?string $id)
    {
        $this->id = $id ?? $this->createId();
        ;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setCourseId(int $courseId)
    {
        $this->courseId = $courseId;
    }

    public function setSemester(int $semester)
    {
        $this->semester = $semester;
    }

    //cria um id no padrão estabelecido
    private function createId(): string
    {
        return uniqid();
    }

    //a interface JsonSerializable exige a implementação desse método
    //basicamene ele retorna todas (mas poderáimos customizar) os atributos do estudante,
    //agora com acesso público, de forma que a função json_encode() possa acessá-los
    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}
