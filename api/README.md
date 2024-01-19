# Desafio Onfly

## Descrição do Projeto
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
2. Instale as dependências do backend:
   ```
   cd api
   composer install
   ```

3. Instale as dependências do frontend:
   ```
   cd app
   npm install
   ```

4. Execute o projeto:
   ```
   cd api
   sh startup.sh
   ```

   ```
   cd app
   vue serve
   ```

5. Execute as migrations
    ``` 
    php artisan migrate
    ```

10. Abra o navegador e acesse http://localhost:8080

## Documentação API
Para acessar a documentação da api acesse http://localhost:8000/api/documentation

Este projeto é um desafio proposto pela Codeesh e está disponível no meu repositório pessoal do GitHub.