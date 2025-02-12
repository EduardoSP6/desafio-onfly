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

- Executar comando para gerar JWT Secret:
```
docker exec -it desafio-onfly-php php artisan jwt:secret
```

- Executar comando para criar as tabelas e seed de usuários:
```
docker exec -it desafio-onfly-php php artisan migrate --seed
```
