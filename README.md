## part 1

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

## part 2

1. change all `include` to `require`
2. error handling, create `includes/errors.php` file
   1. use `set_exception_handler` to handle exceptions
   2. use `set_error_handler` to handle errors
   3. use `ob_start` to start output buffering, and `ob_end_clean` to clean the output buffer
   4. use `print` to print the exception
3. create `webroot/user.php` to show user info
   1. validate the user id through `filter_input`
   2. extract validate logic in `ab_request_query_get_integer` method
4. pretty url
   1. in `webroot/users.php`, click the username link to the `user.php` page
   2. use `nginx rewrite` to rewrite the url
5. use `$statement->rowCount() === 0` to check if the user exists
   1. if the user does not exist, return a 404 error
   2. else return data
6. render `templates/user.php`, pretty the user form
