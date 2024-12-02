### Local Install

#### Create `.env.local`
```bash
cat << EOF > .env.local
APP_ENV=dev
APP_SECRET=65976e6c0450aa5cf7ddba12c093a0c2
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=rest_crud_db
MYSQL_PORT=3315
NGINX_PORT=8015
LOCAL_USER=1000:1000
TIMEZONE=Europe/Kiev
DATABASE_URL=mysql://root:root@mysql_db/rest_crud_db
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=ddd64968f02152a0723018d241db1296d0537b8fa4867d680a873c5f9985b8f8
NBU_API_BASE_URL=https://bank.gov.ua/
EOF
```

#### Install the project
```bash
docker compose -f docker-compose-local.yaml --env-file ./.env.local build --no-cache              # Build containers from images
```
```bash
docker compose -f docker-compose-local.yaml --env-file ./.env.local up -d                         # Run containers       
```              
```bash
docker exec -it nelmiocrud-php-1 composer install --optimize-autoloader                             # Install dependencies
```
```bash
docker exec -it nelmiocrud-php-1 php bin/console doctrine:database:create --if-not-exists           # Create database
```
```bash
docker exec -it nelmiocrud-php-1 php bin/console doctrine:migrations:migrate -n                     # Run migrations
```
```bash
docker exec -it nelmiocrud-php-1 php bin/console lexik:jwt:generate-keypair                         # Generate JWT keys
```
```bash
docker exec -it -e MOUNT=3 -e USERS=4 nelmiocrud-php-1 php bin/console doctrine:fixtures:load -n    # Load fixtures
```

#### Open in browser
http://localhost:8015/api/doc                                                               


#### Run tests environment

```bash
docker exec -it restcrud-mysql_db-1 mysql -uroot -p -e "SELECT VERSION();"    # Check MySQL version
```
#### Create `.env.test.local`
```bash
cat << EOF > .env.test.local
DATABASE_URL="mysql://root:root@mysql_db/rest_crud_db?serverVersion=8.3.0" #check MySQL version
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=123456
EOF
```
```bash
docker exec -it nelmiocrud-php-1 php bin/console doctrine:database:create --env=test --if-not-exists           # Create database
```
```bash
docker exec -it nelmiocrud-php-1 php bin/console doctrine:migrations:migrate --env=test --no-interaction       # Run migrations
```
```bash
docker exec -it -e MOUNT=3 -e USERS=4 nelmiocrud-php-1 php bin/console doctrine:fixtures:load --env=test -n   # Load fixtures
```
```bash
docker exec -it nelmiocrud-php-1 php bin/phpunit                                                             # Run tests
```






