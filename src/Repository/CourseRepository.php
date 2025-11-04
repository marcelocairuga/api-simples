<?php

namespace Repository;

use Database\Database;
use Model\Course;
use PDO;

class CourseRepository
{
    private $connection;

    public function __construct()
    {
        //obtém a conexão
        $this->connection = Database::getConnection();
    }

    public function findAll(): array
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM courses");
        $stmt->execute();

        //para cada linha de retorno, cria um objeto Curso
        //e aramazena em um array
        $courses = [];
        while ($row = $stmt->fetch()) {
            $course = new Course(
                id: $row['id'],
                name: $row['name'],
                semesters: $row['semesters'],
            );
            $courses[] = $course;
        }

        //retorna o conjunto de cursos encontrado
        return $courses;
    }

    public function findByName(string $name): array
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM courses 
                                          WHERE name like :name");
        $stmt->bindValue(':name', '%' . $name . '%');
        $stmt->execute();

        //para cada linha de retorno, cria um objeto Student
        //e aramazena em um array
        $courses = [];
        while ($row = $stmt->fetch()) {
            $course = new Course(
                id: $row['id'],
                name: $row['name'],
                semesters: $row['semesters'],
            );
            $courses[] = $course;
        }

        //retorna o conjunto de cursos encontrado
        return $courses;
    }

    public function findById(int $id): ?Course
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM courses 
                                          WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        //se não achou, retorna nulo
        $row = $stmt->fetch();
        if (!$row)
            return null;

        //se achou, cria um objeto Course
        $course = new Course(
            id: $row['id'],
            name: $row['name'],
            semesters: $row['semesters'],
        );

        //retorna o curso encontrado
        return $course;
    }

    public function create(Course $course): Course
    {
        //executa a operação no banco    
        $stmt = $this->connection->prepare("INSERT INTO courses (name, semesters) 
                                          VALUES (:name, :semesters)");
        $stmt->bindValue(':name', $course->getName());
        $stmt->bindValue(':semesters', $course->getSemesters(), PDO::PARAM_INT);
        $stmt->execute();

        //recupera o id gerado pelo banco
        $course->setId($this->connection->lastInsertId());

        //retorna o curso criado
        return $course;
    }

    public function update(Course $course): void
    {
        //executa a operação no banco
        $stmt = $this->connection->prepare("UPDATE courses SET 
                                                name = :name, 
                                                semesters = :semesters
                                            WHERE id = :id;");
        $stmt->bindValue(':id', $course->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $course->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':semesters', $course->getSemesters(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        //executa a operação no banco
        $stmt = $this->connection->prepare("DELETE FROM courses 
                                          WHERE id = :id;");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}