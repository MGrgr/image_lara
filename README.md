## Installation
To install application, please run:
```
git clone https://gitlab.com/rleukhin/lara-test-images.git test-images

cd test-images/

bash install.sh
```
and configure your database and email in .env file

## Manual Installation
Run commands below:
```
git clone https://gitlab.com/rleukhin/lara-test-images.git

cd lara-test-images/

composer install

cp .env.example .env

php artisan key:generate
php artisan migrate
php artisan storage:link

npm install
npm run dev
```
and configure your database and email in .env file
