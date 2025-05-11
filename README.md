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
     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
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

## part 3

1. do not expose $statement to the template scope, use `$statement -> fetchAll` to get the data
2. use `extract` to transform the array to variables in the template
3. add `ab_request_methods_assert` to allow multiple methods in user.php
4. add `ab_request_is_method` to check the request method, distinguish between GET and POST
5. handling post data
   1. use `filter_input_array` to filter the post data, create a method `ab_request_get_post_parameters`
   2. set `id` in the hidden input field
   3. create `validate.php` to validate the post data
      - `mb_strlen` to get string length
      - `preg_match` to check the string format
      - `filter_var` to check the email format
   4. add `$errors` array to the template, use `isset` to check if the errors exist
   5. add `ab_validate_has_errors` to check if the errors exist
   6. update data to database

## part 4

1. php version must be 8.4 or later, then you can use `mb_trim` method
2. create `sanitize.php` to sanitize the user post data
3. config nginx rewrite to make user.php can as create user page
4. if user_id === 0, it's user creation
5. check username is unique
6. check first_name and last_name is unique
7. check email is unique
8. mysql query comparison is not sensitive

## part 5

1. split repeat code into header and footer
2. implement data pagination and pager template

## part 6

1. improve pager template, do not hard code the url
2. add `sidebar.php` template, use `str_starts_with` to check the active url
3. add role section

   1. create `roles` table

   ```sql
   CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(32) NOT NULL UNIQUE,
    description VARCHAR(1024) NOT NULL
   );

   INSERT INTO roles (name, description) VALUES
   ('Administrator', 'Administrator role'),
   ('User', 'User role'),
   ('Guest', 'Guest role');
   ```

   2. copy `users.php` to `roles.php`
   3. copy `user.php` to `role.php`

## part 6.5

1. add `actions` table

   ```sql
   CREATE TABLE actions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(32) NOT NULL UNIQUE,
    description VARCHAR(1024) NOT NULL
   );

   INSERT INTO actions (name, description) VALUES
   ('CreateUser', 'Create User'),
   ('ReadUser', 'Read User'),
   ('UpdateUser', 'Update User'),
   ('DeleteUser', 'Delete User'),
   ('CreateRole', 'Create Role'),
   ('ReadRole', 'Read Role'),
   ('UpdateRole', 'Update Role'),
   ('DeleteRole', 'Delete Role'),
   ('CreateAction', 'Create Action'),
   ('ReadAction', 'Read Action'),
   ('UpdateAction', 'Update Action'),
   ('DeleteAction', 'Delete Action');
   ```

## part 7

1. create `users_roles` table

   ```sql
   CREATE TABLE users_roles (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL
   );
   ALTER TABLE users_roles ADD UNIQUE KEY (user_id, role_id);
   ALTER TABLE users_roles ADD FOREIGN KEY (user_id) REFERENCES users(id);
   ALTER TABLE users_roles ADD FOREIGN KEY (role_id) REFERENCES roles(id);
   INSERT INTO users_roles VALUES (1, 1), (1, 2);
   ```

2. show user roles in `user.php`
3. add role to user
4. create `roles_actions` table

   ```sql
   CREATE TABLE roles_actions (
    role_id BIGINT UNSIGNED NOT NULL,
    action_id BIGINT UNSIGNED NOT NULL
   );
   ALTER TABLE roles_actions ADD UNIQUE KEY (role_id, action_id);
   ALTER TABLE roles_actions ADD FOREIGN KEY (role_id) REFERENCES roles(id);
   ALTER TABLE roles_actions ADD FOREIGN KEY (action_id) REFERENCES actions(id);
   INSERT INTO roles_actions VALUES (1, 1), (1, 2), (1, 3), (1, 4), (1, 5);
   ```

## part 8

1. add hidden action field to distinguish between add and delete
2. delete role for user
3. delete action for role
