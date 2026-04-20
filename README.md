# Ambiente local com Nginx + PHP-FPM (Docker)

Passos rápidos:

- Instale o Docker e o Docker Compose seguindo a documentação oficial para sua distro Linux.
- No diretório do projeto, inicie os containers:

```bash
docker compose up -d
```

- Abrir no navegador: http://localhost — deve mostrar "Olá, mundo!".

Banco de dados (bônus):

- Este projeto agora inclui um serviço MySQL. O `docker-compose.yml` define as variáveis padrão:
	- `MYSQL_ROOT_PASSWORD=rootpass`
	- `MYSQL_DATABASE=testdb`
	- `MYSQL_USER=appuser`
	- `MYSQL_PASSWORD=apppass`

GitHub Actions (deploy automático):

- Há um workflow em `.github/workflows/deploy.yml` que faz SCP do repositório para a EC2 e executa `docker compose up -d --build`.
- Configure os segredos do repositório: `EC2_HOST`, `EC2_USER`, `EC2_SSH_KEY` (chave privada), e opcional `EC2_SSH_PORT`.

Deploy manual (alternativa):

```bash
chmod +x deploy-ec2.sh
./deploy-ec2.sh my-key.pem ubuntu EC2_PUBLIC_IP
```

Arquivos novos:

- [php/Dockerfile](php/Dockerfile#L1)
- [db/init.sql](db/init.sql#L1)
- [.github/workflows/deploy.yml](.github/workflows/deploy.yml#L1)
- [deploy-ec2.sh](deploy-ec2.sh#L1)


Arquivos principais criados:

- [docker-compose.yml](docker-compose.yml#L1)
- [nginx/default.conf](nginx/default.conf#L1)
- [src/index.php](src/index.php#L1)

Observações:

- O serviço `php` usa a imagem oficial `php:8.1-fpm` e escuta na porta interna 9000.
- O `nginx` está configurado para encaminhar requisições `.php` para `php:9000`.
- Se quiser testar sem instalar o Docker Engine, peça que eu gere instruções alternativas.
