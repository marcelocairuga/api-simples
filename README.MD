# PONTO DE PARTIDA PARA O PROJETO
Este é um exemplo de uso do padrão Contoller-Service-Repository para a construção de uma API Rest usando PHP puro. Para fins didáticos, alguns pontos foram simplificados ou abstraídos. Da mesma forma, algumas soluções foram adotadas a fim de permitir a problematização de aspectos importantes do projeto durante as aulas, além da discussão das mudanças necessárias para a sua evolução ou para a implementação de outras técnias/princípios.

### Entidades e Relações
- Curso (nome e número de semestres);
- Estudante (matrícula, nome, email, curso e semestre no curso);
- Um estudante deve estar matriculado em um único curso.

### Requisitos
- Deve ser possível criar, alterar e excluir um curso;
- Deve ser possível obter os dados de um curso pelo id;
- Deve ser possível obter uma listagem de cursos;
- Deve ser possível filtrar os cursos pelo nome;
- Deve ser possível criar, alterar e excluir um estudante;
- Deve ser possível obter os dados de um estudante pelo id;
- Deve ser possível obter uma listagem de estudantes;
- Deve ser possível filtrar os estudante pelo nome;
- Deve ser possível atualizar o semestre do estudante;
- O estudante é identificado por uma matrícula que deve ser gerada automaticamente pelo sistema;

### Regras de Negócio
- O nome de curso deve ter pelo menos 5 caracteres;
- O número de semestres do curso deve ser maior do que zero;
- Não deve ser possível excluir um curso que possua estudantes matriculados;
- A metrícula de um estudante não pode ser alterada;
- O nome de um estudante deve ter pelo menos 5 caracteres;
- O estudante deve ter um email válido;
- Dois estudantes não podem ter o mesmo email;
- O estudante deve estar matriculado em um curso existente;
- O semestre do estudante deve ser maior do que zero;
- O semestre do estudante deve ser menor ou igual ao número de semestre do curso em que está matriculado.

### Detalhes Importantes
- Deve ser usado o servidor APACHE com o módulo de reescrita de URLs ativado;
- No arquivo .htaccess há uma regra muito simples que redireciona todas as requisições para o index.php;
- No arquivo index.php há um roteador rudimentar que, baseado no recurso, chama o controller adequado.
- No index.php é carregado um arquivo de configurações que registra um autoload simples e funções para tratamento de erros e exceções;
- No index.php, antes de chamar o controller específico, é criado um objeto Request com todos os dados da requisição (veja src/Http/Request.php);
- A classe Response possui um método estático para padronizar o envio das respostas em JSON (veja em src/Http/Response.php);
- A classe APIException define uma exceção personalizada da API (veja em src/Error/APIException.php);
- A conexão com o banco de dados adota um Singleton (veja src/Database/Database.php);
- O script setup.php (em /src/Database) prepara o banco de dados e insere dados de exemplo;
- Nos arquivos test_courses.http e test_students.http há exemplos requisições para testar as rotas e regras de negócio.

### Rotas / Endpoint
- GET /api/students
- GET /api/students?name=name
- POST /api/students
- GET /api/students/:id
- PUT /api/students/:id
- PATCH /api/students/:id
- DELETE /api/students/:id
- GET /api/courses
- GET /api/courses?name=name
- POST /api/courses
- GET /api/courses/:id
- PUT /api/courses/:id
- DELETE /api/courses/:id
- GET /api/courses/:id/students

### TO-DO
- Ao dimunuir o número de semestre do curso, deve-se verificar o semestre dos estudantes matriculados.