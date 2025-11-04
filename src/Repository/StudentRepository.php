<?php

namespace Repository;

use Database\Database;
use Model\Student;
use PDO;

class StudentRepository
{
    private $connection;

    public function __construct()
    {
        //estabelece uma conexão
        $this->connection = Database::getConnection();
    }

    public function findAll(): array
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM students");
        $stmt->execute();

        //para cada linha de retorno, cria um objeto Student
        //e aramazena em um array
        $students = [];
        while ($row = $stmt->fetch()) {
            $student = new Student(
                id: $row["id"],
                name: $row["name"],
                email: $row["email"],
                courseId: $row["course_id"],
                semester: $row["semester"]
            );
            $students[] = $student;
        }

        //retorna o conjunto de estudantes encontrados
        return $students;
    }

    public function findByName(string $name): array
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM students 
                                          WHERE name like :name");
        $stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
        $stmt->execute();

        //para cada linha de retorno, cria um objeto Student
        //e aramazena em um array
        $students = [];
        while ($row = $stmt->fetch()) {
            $student = new Student(
                id: $row["id"],
                name: $row["name"],
                email: $row["email"],
                courseId: $row["course_id"],
                semester: $row["semester"]
            );
            $students[] = $student;
        }

        //retorna o conjunto de estudantes encontrados
        return $students;
    }

    public function findById(string $id): ?Student
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM students 
                                          WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        //se não achou, retorna nulo
        $row = $stmt->fetch();
        if (!$row)
            return null;

        //se achou, cria um objeto Student
        $student = new Student(
            id: $row["id"],
            name: $row["name"],
            email: $row["email"],
            courseId: $row["course_id"],
            semester: $row["semester"]
        );

        //retorna o estudante encontrado
        return $student;
    }

    public function findByEmail(string $email): ?Student
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM students 
                                          WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        //como o e-mail é única, se achar é um só
        //se não achou, retorna nulo
        $row = $stmt->fetch();
        if (!$row)
            return null;

        //se achou, cria um objeto Student
        $student = new Student(
            id: $row["id"],
            name: $row["name"],
            email: $row["email"],
            courseId: $row["course_id"],
            semester: $row["semester"]
        );

        //retorna o estudante encontrado
        return $student;
    }

    public function findByCourseId(string $courseId): array
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("SELECT * FROM students 
                                          WHERE course_id = :course_id");
        $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        //para cada linha de retorno, cria um objeto Student
        //e aramazena em um array      
        $students = [];
        while ($row = $stmt->fetch()) {
            $student = new Student(
                id: $row["id"],
                name: $row["name"],
                email: $row["email"],
                courseId: $row["course_id"],
                semester: $row["semester"]
            );
            $students[] = $student;
        }

        //retorna o conjunto de estudantes encontrado
        return $students;
    }

    public function create(Student $student): Student
    {
        //executa a consulta no banco
        $stmt = $this->connection->prepare("INSERT INTO students (id, name, email, course_id, semester) 
                                          VALUES (:id, :name, :email, :course_id, :semester)");
        $stmt->bindValue(':id', $student->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':name', $student->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $student->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':course_id', $student->getCourseId(), PDO::PARAM_INT);
        $stmt->bindValue(':semester', $student->getSemester(), PDO::PARAM_INT);
        $stmt->execute();

        //retorna o estudante criado
        return $student;
    }

    public function update(Student $student): void
    {
        //executa a operação no banco
        $stmt = $this->connection->prepare("UPDATE students SET 
                                                name = :name, 
                                                email = :email,
                                                course_id = :course_id,
                                                semester = :semester
                                            WHERE id = :id;");
        $stmt->bindValue(':id', $student->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':name', $student->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $student->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':course_id', $student->getCourseId(), PDO::PARAM_INT);
        $stmt->bindValue(':semester', $student->getSemester(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(string $id): void
    {
        //executa a operação no banco
        $stmt = $this->connection->prepare("DELETE FROM students 
                                            WHERE id = :id;");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function setSemester(string $id, int $semester): void
    {
        //executa a operação no banco
        $stmt = $this->connection->prepare("UPDATE students SET
                                                semester = :semester 
                                            WHERE id = :id;");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':semester', $semester, PDO::PARAM_INT);
        $stmt->execute();
    }
}