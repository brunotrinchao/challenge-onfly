# Etapas de desenvolvimento

**[X] Itens marcados significam que a tarefa em questão foi concluida.**

## Backend

### Linguagem exigidas
    [X] PHP usando Laravel
    [X] Banco Mysql

### Entidades (Models)
    [X] Expense (Despesa)
        - id bigint(20) PK
        - user_id bigint(20) FK
        - description varchar(255)
        - date date
        - amount decimento(10,2)
        - created_at timestamp
        - updated_at timestamp
  
    [X] User (Usuário)
        - id bigint(20) PK
        - name varchar(255)
        - email varchar(255)
        - password varchar(255)
        - created_at timestamp
        - updated_at timestamp

### Relacionamentos
- Um User pode ter vários Expenses associados (1->N).
- Um Expense pertence a um único User (N->1).

### Resources
    [X] Expense
        [X] ExpenseResource
    [X] User
        [X] UserResource

### Request
    [X] Expense
        [X] ExpenseStoreRequest
            [X] Usuário existe
            [X] Data futuro não é permitido
            [X] Valor negativo não é permitido
            [X] Limite de 191 caracteres para descrição
        [X] ExpenseUpdateRequest
            [X] Usuário existe
            [X] Data futuro não é permitido
            [X] Valor negativo não é permitido
            [X] Limite de 191 caracteres para descrição
    [X] User
        [X] UserStoreRequest
        [X] UserUpdateRequest
    [X] LoginP
        [X] LoginRequest
    [X] Register
        [X] RegisterRequest

### Policeis
    [X] ExpensePolicy (Permite acesso a despesas apenas se for do usuário)
        [X] viewAny
        [X] view
        [X] update
        [X] delete
    [X] ExpensePolicy (Permite acesso ao usuário apenas se diferente ao logado)
        [X] delete

### Routes
    [X] /expense (apiResource)
        [X] Usuário autenticado (sanctum)
    [X] euser (apiResource)
        [X] Usuário autenticado (sanctum)
    [X] auth/login

### Validações
    [X] Usar Form Request
    [X] Enviar e-mail com Notifications
        [X] Enviar e-mail
        [] Colocar em fila

### Documentação
    [X] Documentar API
        [X] Usar swagger

## Testes
    [X] Criar testes unitários



## Frontend

### Linguagem exigidas
    [X] Vue.js
    [X] Quasar

### Telas
    [X] Login
    [X] Signup
    [X] Despesas
    [X] Usuários

