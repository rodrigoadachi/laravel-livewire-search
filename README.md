# Laravel Livewire Search

Este projeto implementa uma aplicação de busca usando Laravel 11 com Livewire 3.

## Requisitos do Sistema

* **PHP 8.2 ou superior** (Laravel 11 não é compatível com PHP 8.1 ou anterior)
* Docker e Docker Compose (recomendado para desenvolvimento)
* Composer

## Problemas Comuns

**Erro: "Your php version (8.1.x) does not satisfy that requirement"**

Laravel 11 requer PHP 8.2 ou superior. Se você estiver vendo este erro, você tem duas opções:

1. **Atualizar o PHP do seu sistema** (instruções abaixo)
2. **Usar Laravel Sail com Docker** (recomendado, não requer PHP 8.2 instalado localmente)

## Instalação

### Opção 1: Usando Docker com Laravel Sail (Recomendado)

Esta opção permite contornar a necessidade de ter PHP 8.2 instalado localmente, já que o Docker usará a versão correta.

#### 1. Instalar Docker

```bash
# Atualizar pacotes
sudo apt update

# Instalar dependências
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common

# Adicionar chave GPG oficial do Docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

# Adicionar repositório do Docker
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"

# Atualizar lista de pacotes
sudo apt update

# Instalar Docker
sudo apt install -y docker-ce docker-ce-cli containerd.io

# Adicionar seu usuário ao grupo docker (para executar sem sudo)
sudo usermod -aG docker $USER

# Aplicar alterações de grupo (ou reinicie a sessão)
newgrp docker

# Verificar instalação
docker --version
```

#### 2. Instalar Docker Compose

```bash
# Baixar Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.23.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Dar permissão de execução
sudo chmod +x /usr/local/bin/docker-compose

# Verificar instalação
docker-compose --version
```

#### 3. Clone o repositório

```bash
git clone git@github.com:rodrigoadachi/laravel-livewire-search.git
cd laravel-livewire-search
```

#### 4. Configuração do ambiente

```bash
cp .env.example .env
```

#### 5. Iniciar Sail pela primeira vez

Como você não tem PHP 8.2 instalado localmente e não pode executar `composer install` ainda, use o Docker diretamente para criar o container Sail:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

#### 6. Iniciar o ambiente Sail

```bash
./vendor/bin/sail up -d
```

#### 7. Configurar a aplicação

```bash
# Gerar chave da aplicação
./vendor/bin/sail artisan key:generate

# Executar migrações
./vendor/bin/sail artisan migrate

# Executar seeders
./vendor/bin/sail artisan db:seed
```

### Opção 2: Atualizar o PHP localmente para 8.2+

Se preferir trabalhar com PHP local (sem Docker):

#### 1. Adicionar repositório PHP

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt update
```

#### 2. Instalar PHP 8.2

```bash
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring \
php8.2-xml php8.2-zip php8.2-mysql php8.2-gd php8.2-intl php8.2-bcmath \
php8.2-fpm php8.2-sqlite3
```

#### 3. Verificar versão do PHP

```bash
php -v
```

#### 4. Mudar versão padrão do PHP (se necessário)

```bash
sudo update-alternatives --set php /usr/bin/php8.2
```

#### 5. Continuar com a instalação normal

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Desenvolvimento com Laravel Sail

### Iniciar o ambiente de desenvolvimento

```bash
# Iniciar os containers
./vendor/bin/sail up -d

# Em outro terminal, compilar assets (Vite)
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### Criar e popular o banco de dados

```bash
# Executar migrações
./vendor/bin/sail artisan migrate

# Executar todos os seeders
./vendor/bin/sail artisan db:seed

# Ou executar um seeder específico
./vendor/bin/sail artisan db:seed --class=UserSeeder

# Para recriar o banco do zero e executar seeders
./vendor/bin/sail artisan migrate:fresh --seed
```

### Comandos úteis para desenvolvimento

**Limpar cache**
```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear
```

**Criar novos componentes**
```bash
# Criar controller
./vendor/bin/sail artisan make:controller NomeController

# Criar model com migration e factory
./vendor/bin/sail artisan make:model Nome -mf

# Criar componente Livewire
./vendor/bin/sail artisan make:livewire NomeComponente
```

**Executar testes**
```bash
./vendor/bin/sail test
```

**Executar filas e agendamentos**
```bash
# Processar filas
./vendor/bin/sail artisan queue:work

# Executar agendador
./vendor/bin/sail artisan schedule:work
```

**Modo de desenvolvimento completo**
```bash
# Com o comando definido no composer.json
./vendor/bin/sail composer dev
```

### Ambiente de desenvolvimento UI

O Laravel Mix/Vite precisa ser executado para compilar os assets:

```bash
# Instalar dependências Node
./vendor/bin/sail npm install

# Executar em modo de desenvolvimento (compila automaticamente ao salvar)
./vendor/bin/sail npm run dev

# Ou compilar para produção
./vendor/bin/sail npm run build
```

### Parar o ambiente

```bash
./vendor/bin/sail down
```

## Produção

### Preparação para produção

```bash
# Otimizar a aplicação
./vendor/bin/sail artisan optimize
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
./vendor/bin/sail artisan config:cache

# Compilar assets para produção
./vendor/bin/sail npm run build
```

### Deploy para produção

Existem várias maneiras de fazer deploy de um projeto Laravel. Uma abordagem simples:

1. Configure um servidor com PHP 8.2+, Nginx/Apache, MySQL e Composer
2. Clone o repositório em produção
3. Configure as variáveis de ambiente (.env)
4. Instale as dependências:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
5. Execute as migrações e seeders:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
6. Otimize a aplicação:
   ```bash
   php artisan optimize
   ```
7. Configure o servidor web para apontar para a pasta public

Alternativamente, você pode usar Docker/Sail ou Laravel Forge, Laravel Vapor ou outros serviços de hospedagem especializados.

## Visualização da aplicação

Com o ambiente em execução:
- Web: [http://localhost](http://localhost)
- MySQL: localhost:3306

## Estrutura do projeto

```
├── app/              # Código principal da aplicação
├── bootstrap/        # Arquivos de inicialização
├── config/           # Arquivos de configuração
├── database/         # Migrações e seeders
│   ├── factories/    # Factories para testes
│   ├── migrations/   # Migrações do banco de dados
│   └── seeders/      # Seeders para popular o banco
├── public/           # Arquivos públicos
├── resources/        # Views, assets e outros recursos
├── routes/           # Definições de rotas
├── storage/          # Arquivos gerados pela aplicação
└── tests/            # Testes automatizados
```

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo LICENSE para detalhes.
