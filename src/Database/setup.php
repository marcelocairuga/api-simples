<?php

// Este arquivo é responsável por configurar o banco de dados
// ele é executado por fora do fluxo normal da aplicação
// - a conexão com o banco de dados é feita diretamente aqui
// - não usa o tratamento de erros da API

// arquivo do banco de dados SQLite
$database = __DIR__ . '/database.sqlite';

try {
    // cria e configura a conexão com o banco de dados
    $conn = new PDO("sqlite:" . $database);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("PRAGMA foreign_keys = ON;");
    echo "Conexão com o banco de dados estabelecida com sucesso!\n";
} catch (Exception $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage() . PHP_EOL;
    exit;
}

try {
    // exclui as tabelas se já existirem
    $conn->exec("DROP TABLE IF EXISTS students;");
    $conn->exec("DROP TABLE IF EXISTS courses;");

    // cria a tabela courses
    $sql = "CREATE TABLE courses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                semesters INTEGER NOT NULL
            );";
    $conn->exec($sql);

    // cria a tabela students
    $sql = "CREATE TABLE students (
                id TEXT PRIMARY KEY,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                course_id INTEGER NOT NULL,
                semester INTEGER NOT NULL,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT
            );";
    $conn->exec($sql);
    
    echo "Tabelas criadas com sucesso!" . PHP_EOL;
} catch (PDOException $e) {
    echo "Erro ao criar as tabelas: " . $e->getMessage() . PHP_EOL;
    exit;
}

//cria um conjunto de cursos de exemplo
$courses = [
    ["name" => "Sistemas para Internet", "semesters" => 6],
    ["name" => "Ciência da Computação", "semesters" => 10],
    ["name" => "Análise de Sistemas", "semesters" => 8],
    ["name" => "Ciência de Dados e Inteligência Artificial", "semesters" => 9]
];

//cria um cojunto de estudantes de exemplo
$students = [
    ["id" => "690a4385a4a15", "name" => "Ana Silva", "email" => "ana.silva@email.com", "course_id" => 1, "semester" => 3],
    ["id" => "690a4385a4a16", "name" => "Bruno Costa", "email" => "bruno.costa@email.com", "course_id" => 1, "semester" => 4],
    ["id" => "690a4385a4a17", "name" => "Carlos Souza", "email" => "carlos.souza@email.com", "course_id" => 1, "semester" => 2],
    ["id" => "690a4385a4a18", "name" => "Daniela Lima", "email" => "daniela.lima@email.com", "course_id" => 1, "semester" => 4],
    ["id" => "690a4385a4a19", "name" => "Eduardo Pereira", "email" => "eduardo.pereira@email.com", "course_id" => 2, "semester" => 7],
    ["id" => "690a4385a4a20", "name" => "Fernanda Oliveira", "email" => "fernanda.oliveira@email.com", "course_id" => 3, "semester" => 5],
    ["id" => "690a4385a4a21", "name" => "Gabriel Martins", "email" => "gabriel.martins@email.com", "course_id" => 2, "semester" => 5],
    ["id" => "690a4385a4a22", "name" => "Helena Souza", "email" => "helena.souza@email.com", "course_id" => 2, "semester" => 6],
    ["id" => "690a4385a4a23", "name" => "Igor Rodrigues", "email" => "igor.rodrigues@email.com", "course_id" => 1, "semester" => 3],
    ["id" => "690a4385a4a24", "name" => "Joana Almeida", "email" => "joana.almeida@email.com", "course_id" => 3, "semester" => 2]
];


try {
    // inicia uma transação
    $conn->beginTransaction();

    //salva o conjunto no banco de dados
    $sql = "INSERT INTO courses (name, semesters) 
            VALUES (:name, :semesters);";
    $stmt = $conn->prepare($sql);
    foreach ($courses as $course) {
        $stmt->execute($course); //como as chaves do array possuem o mesmo nome dos parâmetros...
    }
    echo "Cursos inseridos com sucesso!" . PHP_EOL;

    //salva o conjunto no banco de dados
    $sql = "INSERT INTO students (id, name, email, course_id, semester) 
            VALUES (:id, :name, :email, :course_id, :semester);";
    $stmt = $conn->prepare($sql);
    foreach ($students as $student) {
        $stmt->execute($student); //como as chaves do array possuem o mesmo nome dos parâmetros..
    }
    echo "Estudantes inseridos com sucesso!" . PHP_EOL;

    // confirma a transação
    $conn->commit();
    echo "Banco de dados configurado com sucesso!" . PHP_EOL;
} catch (PDOException $e) {
    // reverte a transação em caso de erro
    if ($conn->inTransaction()) $conn->rollBack();
    echo "Erro ao inserir os dados: " . $e->getMessage() . PHP_EOL;
    exit;
}


