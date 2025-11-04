<?php

namespace Model;

use JsonSerializable;

class Course implements JsonSerializable
{
    //implementando essa interface, a função json_enconde() terá acesso
    //aos membros privados do objeto através do método jsonSerialize().

    //atributos do curso

    //o id é obrigatório e não pode ser nulo no banco de dados
    //contudo, durante o processo de criação, não temos um id, por é gerado no banco
    //assim, admite-se, nesse período, que o id seja nulo (nulo != opcional), indicando-se com ? 
    private ?int $id;
    private string $name;
    private int $semesters;

    //construtor
    public function __construct(string $name, int $semesters, ?int $id = null)
    {
        //o parâmetro id do construtor é opcional, mas se não informado
        //recebe o valor nulo (pois o atributo não é opcional)
        $this->id = $id;
        $this->name = trim($name);
        $this->semesters = $semesters;
    }

    //métodos GET
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSemesters(): int
    {
        return $this->semesters;
    }

    //métodos SET
    public function setId(int $id)
    { //repare que id só admite nulo no processo de criação, aqui não!
        $this->id = $id;
    }

    public function setName(string $name)
    {
        $this->name = trim($name);
    }

    public function setSemesters(int $semesters)
    {
        $this->semesters = $semesters;
    }

    //a interface JsonSerializable exige a implementação desse método
    //basicamene ele retorna todas (mas poderáimos customizar) os atributos do curso,
    //agora com acesso público, de forma que a função json_encode() possa acessá-los
    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}