1. use docker to setup php development environment
2. connect to mysql from outer terminal
   ```bash
   docker exec -it php-native-mysql-1 bash
   mysql -uuser -ppassword
   # or
   docker ps
   docker exec -it 920f4861a4d6|php-native-mysql-1 mysql -uuser -ppassword
   ```
3. create a user table in mysql
   ```sql
   CREATE TABLE users (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     first_name VARCHAR(32) NOT NULL,
     last_name VARCHAR(32) NOT NULL,
     email VARCHAR(255) NOT NULL UNIQUE,
     username VARCHAR(64) NOT NULL UNIQUE
   );
   alter table users add unique key (first_name, last_name);
   ```
4. insert some data into the user table, use [mockaroo](https://www.mockaroo.com/) to generate data
5. config directory structure to make sure the web server can only access the webroot directory
6. could use `curl -X POST http://localhost:8080/users.php -f` to test the different methods of accessing users.php file
