# devopsSci — Aplicação PHP + Nginx em Docker

Este repositório contém um exemplo simples de aplicação PHP servida por Nginx, empacotada com Docker Compose. O objetivo é demonstrar um fluxo local e um deploy em EC2 com conexão a um banco MySQL hospedado no Amazon RDS.

## Estrutura

- `php/` — Dockerfile da imagem PHP-FPM (extensões PDO/MySQL instaladas).
- `nginx/` — configuração do Nginx para rotear `.php` ao PHP-FPM.
- `src/` — código da aplicação (ex.: `index.php`).
- `docker-compose.yml` — orquestra `php` e `web` (nginx).
- `.github/workflows/deploy.yml` — workflow de CI/CD para copiar e subir a app na EC2.

## Executando localmente (desenvolvimento)

1. Copie o exemplo de variáveis de ambiente:

cp env.example .env
# Edite .env conforme necessário (DB_* se for usar um DB local)

2. Build e start:

docker compose up -d --build

3. Ver logs e debug:

docker compose logs --tail 200 php
docker compose logs --tail 200 web


4. Parar e remover containers (manual):

docker compose down

## Amazon RDS (MySQL)

Como usar o RDS com esta aplicação:

- Crie uma instância Amazon RDS (MySQL 8.0).
- Security Group: abra a porta `3306` apenas para o IP/SG da sua instância EC2 (não deixe pública).
- Habilite TLS e, para verificação de identidade, baixe o CA bundle:
  https://truststore.pki.rds.amazonaws.com/global/global-bundle.pem

Variáveis de ambiente necessárias (colocar em `.env` ou como secrets no deploy):

- `DB_HOST` (endpoint RDS)
- `DB_DATABASE` (nome do banco)
- `DB_USER` (usuário)
- `DB_PASSWORD` (senha)


Criar tabela e inserir registro exemplo:

```bash
MYSQL_PWD="$DB_PASSWORD" mysql --ssl-mode=VERIFY_IDENTITY --ssl-ca=/tmp/global-bundle.pem \
  -h "$DB_HOST" -P3306 -u "$DB_USER" "$DB_DATABASE" <<'SQL'
CREATE TABLE IF NOT EXISTS greetings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO greetings (message) VALUES ('Olá, mundo!');
SELECT id,message,created_at FROM greetings ORDER BY id DESC LIMIT 5;
SQL
```

Permissões: o usuário usado pelo app precisa ter pelo menos `SELECT`/`INSERT`/`UPDATE`/`DELETE` e privilégios para criar a tabela se você pretende criá-la via app ou deploy.

## Rodando Docker no EC2 (melhores práticas)

Passos rápidos para preparar uma EC2 Ubuntu e rodar a aplicação:

1. Instalar Docker e plugin Compose:

```bash
sudo apt update
sudo apt install -y docker.io docker-compose-plugin
sudo usermod -aG docker $USER
newgrp docker
```

2. Copiar o repositório para o EC2 e criar `.env` (local seguro):

```bash
git clone <repo-url>
cd <repo>
cp env.example .env
# edite .env com valores reais (DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD)
```

3. Subir containers:

docker compose up -d 


## CI/CD (GitHub Actions)

Secrets necessários para o workflow (configure em Settings → Secrets):

- `EC2_HOST`, `EC2_USER`, `EC2_SSH_KEY` (chave privada), `DB_HOST`, `DB_DATABASE`, `DB_USER`, `DB_PASSWORD`.


