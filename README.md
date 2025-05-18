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

## part 9-10

1. create `webroot/login.php`
2. create `templates/login.php`
3. validate and sanitize login data
4. alter user table, add password field
   ```sql
   ALTER TABLE users ADD COLUMN password CHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL;
   ```
5. add password to user by `password_hash`
   ```bash
   php -r "echo password_hash('test', PASSWORD_DEFAULT);";
   UPDATE users SET password = '$2y$10$000000000000000000000000000000000000000000000000' WHERE id = 1;
   ```
6. check password by `password_verify`

## part 11-12

1. add logout functionality
2. login session add `actions`
3. add `authorization.php` to check if the user has the permission to access the page
4. for each page, add authorization check

## part 13

> [Managing Hierarchical Data in MySQL](https://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/)

1. add `categories` table

   ```sql
   CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(64) NOT NULL UNIQUE,
    lft BIGINT UNSIGNED NOT NULL,
    rgt BIGINT UNSIGNED NOT NULL,
    UNIQUE KEY (lft, rgt)
   );

   INSERT INTO categories (name, lft, rgt) VALUES ('Computers', 1, 14), ('Computer parts', 2, 7), ('Peripherals', 8, 13), ('Processors', 3, 4), ('Memory', 5, 6), ('Keyboards', 9, 10), ('Mouse', 11, 12);
   ```

   ```
   |1|                              Computers                      |14|
   |2|        Computer parts      |7||8|     Peripherals         |13|
   |3|Processors|4||5|Memory|6|    |9|Keyboard|10|11|Mouse|12|
   ```

2. query categories: 核心思路： 一个节点的深度，等于它有多少个祖先节点。在嵌套集模型中，如果节点 P 是节点 N 的祖先，那么 P.lft < N.lft 并且 P.rgt > N.rgt。我们可以通过将表自身连接（self-join）来实现：对于每个节点（我们称之为 node），我们去计算有多少个其他节点（我们称之为 parent）符合祖先的条件。

   ```sql
   SELECT
    node.name AS category_name,
    node.lft, -- 可选，方便查看
    node.rgt, -- 可选，方便查看
    (COUNT(parent.id) - 1) AS depth -- 减1是因为每个节点自身也会满足 P.lft <= N.lft AND P.rgt >= N.rgt (如果用 <= >=)
                                    -- 或者更准确地，计算严格祖先的数量
   FROM
    categories AS node
   JOIN
    categories AS parent
   WHERE
    node.lft BETWEEN parent.lft AND parent.rgt -- 关键：node 在 parent 的区间内
   GROUP BY
    node.id, node.name, node.lft, node.rgt -- 确保每个 node 只有一行
   ORDER BY
    node.lft; -- 按 lft 排序，结果会按层级顺序显示
   ```

   ```sql
   SELECT
    node.name AS category_name,
    node.lft,
    node.rgt,
    COUNT(ancestor.id) AS depth -- 直接是0-indexed的深度
   FROM
    categories AS node
   LEFT JOIN -- 使用 LEFT JOIN 以确保根节点（没有祖先）也能被包含
    categories AS ancestor
    ON node.lft > ancestor.lft AND node.rgt < ancestor.rgt -- ancestor 严格包含 node
   GROUP BY
    node.id, node.name, node.lft, node.rgt
   ORDER BY
    node.lft;
   ```

## part 14

1. category need `$categories` to show select options
2. category need `$parent_id` to select parent category
3. category update name
