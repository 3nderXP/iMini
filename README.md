# iMini - Rede Social (Projeto de Estudos)

## Sobre o Projeto

iMini é uma rede social desenvolvida como projeto de estudos, focando em princípios SOLID, Design Patterns e arquitetura de software. Esta aplicação serve como um laboratório prático para implementar e compreender conceitos avançados de engenharia de software em PHP.

## Objetivos

- Implementar os princípios SOLID:
  - **S**ingle Responsibility Principle
  - **O**pen/Closed Principle
  - **L**iskov Substitution Principle
  - **I**nterface Segregation Principle
  - **D**ependency Inversion Principle
- Aplicar Design Patterns relevantes
- Desenvolver uma arquitetura limpa e escalável
- Praticar boas práticas de desenvolvimento

## Tecnologias Utilizadas

- PHP 8+
- Slim Framework (Microframework para rotas e middleware)
- PSR-7 (Para implementação HTTP)
- Dotenv (Gerenciamento de variáveis de ambiente)
- PHP-DI (Container de injeção de dependências)
- JWT (Firebase JWT para autenticação)
- UUID (Ramsey UUID para identificadores únicos)

## Estrutura do Projeto

```
core/
├── Console/               # Funcionalidades de linha de comando
├── Controllers/           # Controladores da aplicação
│   ├── Api/               # Endpoints da API
│   └── Middlewares/       # Middlewares
├── Helpers/               # Funções auxiliares
├── Infra/                 # Infraestrutura
│   ├── Database/          # Acesso a banco de dados
│   │   └── Migrations/    # Migrações de banco de dados
│   └── Repositories/      # Implementações de repositórios
├── Models/                # Modelos de domínio
│   ├── Entities/          # Entidades
│   ├── Enums/             # Enumerações
│   ├── Interfaces/        # Interfaces
│   │   ├── Repositories/  # Interfaces de repositórios
│   │   └── Services/      # Interfaces de serviços
│   └── ValueObjects/      # Objetos de valor
├── Routes/                # Definição de rotas
├── Services/              # Serviços de aplicação
├── Utils/                 # Utilitários gerais
└── Views/                 # Camada de visualização
    └── Components/        # Componentes reutilizáveis da UI
```

## Instalação

1. Clone este repositório:
```bash
git clone https://github.com/seu-usuario/iMini.git
cd iMini
```

2. Instale as dependências via Composer:
```bash
composer install
```

3. Configure as variáveis de ambiente:
```bash
cp .env.example .env
# Edite o arquivo .env com suas configurações
```

4. Execute o servidor de desenvolvimento:
```bash
php -S localhost:8000
```

## Princípios SOLID Aplicados

### Single Responsibility Principle
Cada classe tem uma única responsabilidade, evitando acoplamento e facilitando manutenção.

### Open/Closed Principle
Classes são abertas para extensão, mas fechadas para modificação, utilizando interfaces e abstrações.

### Liskov Substitution Principle
Subtipos podem ser substituídos por seus tipos base sem alterar o comportamento esperado.

### Interface Segregation Principle
Interfaces específicas são melhores que uma interface genérica, evitando implementações desnecessárias.

### Dependency Inversion Principle
Módulos de alto nível não dependem de módulos de baixo nível. Ambos dependem de abstrações.

## Design Patterns Implementados

- **Repository Pattern**: Abstrai o acesso a dados
- **Factory Method**: Para criação de objetos
- **Dependency Injection**: Através do container PHP-DI
- **Adapter**: Para integração com APIs externas
- **Strategy**: Para comportamentos intercambiáveis

## Contribuição

Este é um projeto de estudo pessoal, mas sugestões são bem-vindas através de issues ou pull requests.

## Licença

Este projeto é para fins educacionais e não possui uma licença formal.

## Documentação da API

### Endpoints

| Método | Endpoint          | Descrição                                      | Autenticação |
|--------|-------------------|-------------------------------------------------|--------------|
| POST   | `/api/auth/login` | Autenticar usuário e receber tokens de acesso  | Não          |
| POST   | `/api/auth/refresh` | Renovar tokens de acesso usando refresh token | Sim (refresh)|
| GET    | `/api/users`      | Listar todos os usuários                       | Sim          |
| POST   | `/api/users`      | Criar nova conta de usuário                    | Não          |

### Detalhes dos Endpoints

#### `POST /api/auth/login`

**Descrição:** Autenticar usuário e receber tokens de acesso

**Parâmetros:**
```json
{
  "email": "string", // Email do usuário
  "password": "string" // Senha do usuário
}
```

**Respostas:**
- `200 OK`: Autenticação bem-sucedida
  ```json
  {
    "output": {
      "accessToken": "string",
      "refreshToken": "string"
    },
    "message": "Authenticated successfully!"
  }
  ```
- `401 Unauthorized`: Credenciais inválidas
  ```json
  {
    "output": null,
    "message": "Unauthorized"
  }
  ```

#### `POST /api/auth/refresh`

**Descrição:** Renovar tokens de acesso usando refresh token

**Headers:**
- `Authorization: Bearer {refreshToken}` (Obrigatório)

**Respostas:**
- `200 OK`: Tokens renovados com sucesso
  ```json
  {
    "output": {
      "accessToken": "string",
      "refreshToken": "string"
    },
    "message": "Tokens refreshed successfully!"
  }
  ```
- `401 Unauthorized`: Token inválido ou expirado
  ```json
  {
    "output": null,
    "message": "Unauthorized"
  }
  ```

#### `GET /api/users`

**Descrição:** Listar todos os usuários (requer autenticação)

**Headers:**
- `Authorization: Bearer {accessToken}` (Obrigatório)

**Parâmetros de Query:**
- `page`: número da página (opcional)
- `limit`: quantidade de itens por página (opcional)

**Respostas:**
- `200 OK`: Lista de usuários
  ```json
  {
    "output": [
      {
        "id": "string",
        "name": "string",
        "email": "string"
      }
    ],
    "message": "Consulta realizada com sucesso!"
  }
  ```
- `401 Unauthorized`: Token inválido ou expirado
  ```json
  {
    "output": null,
    "message": "Unauthorized"
  }
  ```

#### `POST /api/users`

**Descrição:** Criar nova conta de usuário

**Parâmetros:**
```json
{
  "name": "string", // Nome do usuário
  "email": "string", // Email do usuário
  "password": "string" // Senha do usuário
}
```

**Respostas:**
- `200 OK`: Usuário criado com sucesso
  ```json
  {
    "output": {
      "id": "string",
      "name": "string",
      "email": "string"
    },
    "message": "Usuário criado com sucesso!"
  }  ```
- `409 Conflict`: Email já cadastrado

## Documentação da CLI

### Visão Geral

iMini inclui uma interface de linha de comando (CLI) para ajudar em tarefas comuns de desenvolvimento e gerenciamento do banco de dados.

### Uso Básico

```bash
php cli.php [comando] [argumentos]
```

### Comandos Disponíveis

| Comando               | Descrição                                            |
|-----------------------|------------------------------------------------------|
| `make:migration`      | Cria um novo arquivo de migração de banco de dados   |
| `run:migration`       | Executa migrações pendentes de banco de dados        |
| `rollback:migration`  | Reverte a última migração de banco de dados          |

### Exemplos de Uso

#### Exibir ajuda

```bash
php cli.php
```

#### Criar uma nova migração

```bash
php cli.php make:migration create_users_table
```

Este comando criará um novo arquivo de migração com timestamp no diretório `core/Infra/Database/Migrations/Sql`.

#### Executar migrações pendentes

```bash
php cli.php run:migration
```

Este comando identificará todas as migrações que ainda não foram aplicadas e as executará.

#### Reverter a última migração

```bash
php cli.php rollback:migration
```

Este comando reverte a migração mais recente que foi aplicada ao banco de dados.
