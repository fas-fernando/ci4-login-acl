# Instruções para Executar a Aplicação Localmente

Este arquivo README.md contém as etapas necessárias para configurar e executar sua aplicação no ambiente local.

## Requisitos

Certifique-se de que você tenha as seguintes ferramentas instaladas:

- **PHP 8.2**
- **Composer 2.6**
- **MySQL 8.2**

## Passos para Execução

1. **Configuração do Arquivo .env:**
   - Faça uma cópia do arquivo `.env.example` para um arquivo `.env`.
   - Descomente todo o código no arquivo `.env`.
   - Defina a `url_base`.
   - Escolha a opção `CI_ENVIRONMENT = development`.

2. **Instalação das Dependências:**
   - Na pasta raiz do projeto, execute o seguinte comando para instalar as dependências do Composer:
     ```
     composer install
     ```

3. **Criação do Banco de Dados:**
   - Na raiz do projeto, crie um banco de dados com o seguinte comando:
     ```
     php spark db:create [nome_do_banco]
     ```

4. **Execução do Servidor:**
   - Execute o servidor na raiz do projeto com o seguinte comando:
     ```
     php spark serve
     ```
   - Acesse a URL gerada pelo servidor.

5. **Criação das Tabelas:**
   - Execute a URL base definida no arquivo `.env` com a seguinte rota:
     ```
     http://localhost:8080/migrate
     ```
   - Isso criará as tabelas necessárias para o projeto.

6. **População das Tabelas:**
   - Execute a mesma URL base, mas agora com a rota:
     ```
     http://localhost:8080/seed
     ```
   - Isso irá popular as tabelas do banco de dados.

7. **Configuração do PHP:**
   - Acesse o arquivo `php.ini` do servidor PHP local.
   - Descomente as extensões `intl` e `mbstring` para garantir o funcionamento correto da aplicação.

## Observações

- Certifique-se de inserir as credenciais corretas do banco de dados nas opções do arquivo `.env`.

Agora você tem um guia completo para executar sua aplicação localmente! 🚀
