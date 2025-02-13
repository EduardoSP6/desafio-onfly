# Desafio técnico - Onfly

## Instruções para instalação:

- Executar o comando do Docker para fazer o build da aplicação:

```
docker compose up -d --build
```

- Criar arquivo .env com base no .env.example:

```
cp .env.example .env
```

- Executar o comando para instalar dependências do composer:

```
composer install
```

- Executar comando para gerar application key (Laravel):

```
php artisan key:generate
```

- Gerar JWT Secret:

```
php artisan jwt:secret
```

- Criar as tabelas e seed de usuários:

```
php artisan migrate --seed
```

Nota: A senha dos usuários criados pela seed é: password.

- Comando para executar os testes do PHPUnit:

```
php artisan test
```

- Comando para gerar documentação da API:

```
php artisan l5-swagger:generate
```

Nota: Após executar o comando acima, a documentação estará disponível
na URL: http://localhost:8001/api/documentation


- Executar análise estática do código com PHP-Stan (Opcional):

```
composer analyse
```


- Notificações:

As notificações são enviadas por e-mail, neste caso o provedor de e-mail
deverá ser configurado no arquivo .env.

## Organização do código:

A estrutura foi baseada em conceitos de Arquitetura Limpa e SOLID, onde a parte
do código que detém as regras de negócios está no diretório src.
Este diretório está subdividido em:

- Application - Camada responsável pelo fluxo da aplicação.
  Nela estão os casos de uso, interfaces, Exceptions, etc.


- Domain - Camada responsável por executar as regras de negócio.
  Nela você encontrará as entidades, ValueObjects, Enums e Factories.


- Infrastructure - Camada dispõe de implementações concretas das interfaces, como:
  Repositories, DataMappers, Services, etc.


- Testes - Camada responsável pelos testes de unidade e integração da aplicação.
  Os testes foram realizados com o PHPUnit e estão no diretório ./tests/

## Isolamento dos dados:

Para garantir o isolamento dos dados entre os usuários, onde cada usuário só terá acesso
aos pedidos de viagem que criaram, foi criado um Global Scope que aplica um filtro na tabela
de pedidos. O arquivo é: Infrastructure/Persistence/Scopes/TenantScope.php.

Para facilitar em implementações futuras, extrai a aplicação do Global Scope para uma trait,
então se outro Model for criado e precisar isolar os dados por usuário logado, basta adicionar
a trait TenantModels.
