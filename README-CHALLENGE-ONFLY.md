# Fullstack Challenge - Onfly 20231205

## Introdução

Este é o nosso case técnico! A ideia é que você possa mostrar toda sua expertise técnica através dele!
Estamos animados para te ver brilha!

[SPOILER] As instruções de entrega e apresentação do challenge estão no final deste Readme (=

### Antes de começar

- Prepare o projeto para ser disponibilizado no Github, copiando o conteúdo deste repositório para o seu (ou utilize o fork do projeto e aponte para o Github). Confirme que a visibilidade do projeto é pública (não esqueça de colocar no readme a referência a este challenge);
- O projeto deve utilizar a Linguagem específica na sua Vaga (caso esteja se candidatando). Por exempo: PHP, Node.js e entre outras;
- Considere como *deadline 4 dias a partir do início do desafio*. Caso tenha sido convidado a realizar o teste e não seja possível concluir dentro deste período, avise a pessoa que o convidou para receber instruções sobre o que fazer.
- Documentar todo o processo de investigação para o desenvolvimento da atividade (README.md no seu repositório); os resultados destas tarefas são tão importantes do que o seu processo de pensamento e decisões à medida que as completa, por isso tente documentar e apresentar os seus hipóteses e decisões na medida do possível.

### Instruções iniciais obrigatórias

- O projeto deverá ser desenvolvido com uma das tecnologias a seguir: **PHP Laravel | Vue.js**;
- Criar um banco de dados **MySQL**. (Recomendável usar Drivers oficiais para integração com o DB)

### Back-End:

Nessa etapa você deverá construir uma API Restful com Laravel, implementando:
- Autenticação de usuário.
- CRUD de despesas.
- Restrição de acesso.
- A api deverá conter uma forma de Autenticação. (o CRUD deve estar protegido pela
autenticação).

Na entidade despesas, deverá conter:
- Id
- Descrição (descrição da despesa)
- Data (data de quando ocorreu a despesa)
- Usuário (usuário dono da despesa, um relacionamento com a tabela de Usuários)
- Valor (valor em reais da despesa)

### Front-end:

Faça em Vue.js uma tela de gestão de usuários (CRUD) utilizando sua API. Para facilitar o desenvolvimento, deve-se usar UI framework baseado em Vue.js: Quasar.


### Observações para o teste:
- Colocar validação nos requests do CRUD (usuário existe, data não é futuro, valor não é negativo, descrição tem até 191 caracteres).
- Colocar restrição de acessos nos requests do CRUD (somente o usuário relacionado a despesa pode acessar e agir sobre ela).
- Ao cadastrar uma despesa, deverá ser enviado um e-mail para o usuário vinculado a despesa, com o título "despesa cadastrada".
- O teste pode ser realizado da forma que preferir, porém, a forma como for realizado o projeto será o ponto central da avaliação. Recomendamos implementar o projeto do jeito mais simples possível e seguir as boas práticas do Clean Code.
- Usar os recursos nativos do Laravel é o que entendemos como um bom teste simples e que usa bem o Framework.

### Recomendamos utilizar as boas práticas do Laravel para a criação desse projeto:
- Fazer a validação da API utilizando o Form Request.
- Documentação faz TODA a diferença!
- Fazer a camada de transformação da API utilizando o API Resources.
- Fazer a camada de roteamento utilizando API Resource Routes.
- Não esqueça dos testes unitários!
- Fazer a camada de restrição de acesso utilizando as Policies.
- Disparar o e-mail utilizando as Notifications, e colocar ele em uma fila, para que seja
disparado de forma assíncrona.
- Não se esqueça das FK nas Migrations e das Relations dos Models.



## Readme do Repositório

- Deve conter o título do projeto
- Uma descrição sobre o projeto em frase
- Deve conter uma lista com linguagem, framework e/ou tecnologias usadas
- Como instalar e usar o projeto (instruções)
- Não esqueça o [.gitignore](https://www.toptal.com/developers/gitignore)
- Se está usando github pessoal, referencie que é um challenge by coodesh:

>  This is a challenge by [Coodesh](https://coodesh.com/)

## Finalização e Instruções para a Apresentação

Avisar sobre a finalização e enviar para correção.

1. Confira se você respondeu o Scorecard da Vaga que chegou no seu email;
2. Confira se você respondeu o Mapeamento Comportamental que chegou no seu email;
3. Acesse: [https://coodesh.com/challenges/review](https://coodesh.com/challenges/review);
4. Adicione o repositório com a sua solução;
5. Grave um vídeo, utilizando o botão na tela de solicitar revisão da Coodesh, com no máximo 5 minutos, com a apresentação do seu projeto. Foque em pontos obrigatórios e diferenciais quando for apresentar.
6. Adicione o link da apresentação do seu projeto no README.md.
7. Verifique se o Readme está bom e faça o commit final em seu repositório;
8. Confira a vaga desejada;
9. Envie e aguarde as instruções para seguir no processo. Sucesso e boa sorte. =)

## Suporte

Use o nosso canal no slack: http://bit.ly/32CuOMy para tirar dúvidas sobre o processo ou envie um e-mail para contato@coodesh.com.



