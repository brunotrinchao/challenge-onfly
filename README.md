# Desafio Onfly

### Linda da apresentação:
https://www.loom.com/share/8b9c62d5df32450baeae83e75ce06e4b

##  Descrição do Projeto
Este projeto foi desenvolvido como parte de um desafio proposto pela Onfly. Utilidando para backend laravel com mysql (Docker) e para frontend vue.js com quasar.

## Tecnologias Utilizadas

### Backend
- Linguagem: PHP
- Framework: Laravel (v.10)
- Banco de Dados: Mysql
- Outras Tecnologias: Docker, Swagger

### Frontend
- Linguagem: HTML, CSS, JavaScript
- Framework: Vue.js (v.2)
- Biblioteca de UI: Quasar
- Outras Tecnologias: 

## Como Instalar e Usar o Projeto
1. Clone o repositório:
   ```
   git clone https://github.com/brunotrinchao/challenge-onfly.git
   ```

2. Inicie o container do banco
   ```
   # Com o docker ja iniciado
   cd db
   docker compose up -d
   ```

3. Instale as dependências do backend:
   ```
   # Com o docker ja iniciado
   cd api
   composer install
   ```

4. Execute as migrations
    ``` 
    php artisan migrate
    ```

5. Inicia a API
    ``` 
    php artisan serve
    ```

6. Instale as dependências do frontend
   ```
   cd app
   npm install
   ```

7. Iniciar o APP:
   ```
   quasar dev
   ```

8. Abra o navegador e acesse http://localhost:8080

## Testes

   ```
  cd api
  php artisan test
   ```


## Documentação API
Para acessar a documentação da api acesse http://127.0.0.1:8000/api/documentation

Este projeto é um desafio proposto pela Codeesh e está disponível no meu repositório pessoal do GitHub.