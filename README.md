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
docker exec -it desafio-onfly-php composer install
```

- Executar comando para gerar application key (Laravel):
```
docker exec -it desafio-onfly-php php artisan key:generate
```

- Gerar JWT Secret:
```
docker exec -it desafio-onfly-php php artisan jwt:secret
```

- Criar as tabelas e seed de usuários:
```
docker exec -it desafio-onfly-php php artisan migrate --seed
```

- Executar análise estática do código com PHP-Stan:
```
docker exec -it desafio-onfly-php composer analyse
```

- Comando para executar os testes do PHPUnit:
```
docker exec -it desafio-onfly-php php artisan test
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
