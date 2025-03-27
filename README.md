

Cấu hình database:
Chạy SQL: CREATE DATABASE movie_db
hoặc tạo mới database movie_db


Để chạy dự án này, bạn cần cài đặt các phần mềm sau trên máy của mình:

* Composer ([https://getcomposer.org/](https://getcomposer.org/)) // Tải về và cài đặt
* Node.js & NPM (hoặc Yarn) ([https://nodejs.org/](https://nodejs.org/)) (node -v để kiểm tra có Node chưa)
* Cơ sở dữ liệu (Mysql) // dùng laragon hay xampp cũng đc 

## Hướng dẫn cài đặt

Thực hiện các bước sau để cài đặt và chạy dự án trên môi trường local của bạn:

1.  **Clone Repository:**
    ```bash
    git clone <URL-repository-cua-ban>
    cd <ten-thu-muc-du-an>
    ```

2.  **Cài đặt các gói PHP (Composer):** tải và cài composer trc 
    ```bash
    composer install
    ```

3.  **Cài đặt các gói Javascript (NPM (windown) hoặc Yarn):**
    * Nếu dùng NPM:
        ```bash
        npm install
        ```
    * Nếu dùng Yarn:
        ```bash
        yarn install
        ```

4.  **Tạo file cấu hình môi trường (`.env`):**
    Sao chép file `.env.example` thành `.env`. File này chứa các biến môi trường cần thiết.
    ```bash
    cp .env.example .env
    ```

cấu hình .evn:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=movie_db
DB_USERNAME=root

SESSION_DOMAIN=localhost

// đây là smtp cấu hình sẵn cho mail , có thể thay đổi nếu muốn ()
MAIL_MAILER=smtp
MAIL_ENCRYPTION=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=sunobaby86@gmail.com
MAIL_PASSWORD="emmg qalb hpyd kuie"
MAIL_FROM_ADDRESS="sunobaby86@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"


  **Tạo khóa ứng dụng (APP_KEY):**
    Lệnh này sẽ tạo một khóa mã hóa duy nhất và ghi vào biến `APP_KEY` trong file `.env`.
    ```bash
    php artisan key:generate
    ```

7.  **Tạo khóa JWT (JWT_SECRET):**
    *(Áp dụng nếu bạn dùng package `tymon/jwt-auth`)*
    Lệnh này tạo khóa bí mật cho JWT và ghi vào biến `JWT_SECRET` trong file `.env`.
    ```bash
    php artisan jwt:secret
    ```

8.  **Chạy Database Migrations:**
    Lệnh này sẽ tạo các bảng trong cơ sở dữ liệu dựa trên các file migration trong `database/migrations`.
    ```bash
    php artisan migrate
    ```
Để chạy dự án thì mở 2 terminal chạy 2 lệnh sau:
terminal 1: npm run dev
terminal 2: php artisan serve

