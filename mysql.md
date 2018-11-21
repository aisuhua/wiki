## 安装

安装

```sh
shell> apt-get install mysql-server
```

查看版本号

```sh
shell> mysql --version
```

启动

```sh
shell> service mysql start
```

## 配置

允许所有来源的请求

```sh
shell> vim /etc/mysql/mysql.conf.d/mysqld.cnf
bind-address = 0.0.0.0
```

- [Bind address and MySQL server](https://stackoverflow.com/questions/3552680/bind-address-and-mysql-server)

修改 MySQL 字符集为 utf8mb4

```sh
shell> vim /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci
```

- [10.5 Configuring Application Character Set and Collation](https://dev.mysql.com/doc/refman/5.7/en/charset-applications.html)

修改客户端连接字符集为 utf8mb4

```sh
shell> vim /etc/mysql/conf.d/mysql.cnf
[mysql]
default-character-set = utf8mb4
```

- [10.4 Connection Character Sets and Collations](https://dev.mysql.com/doc/refman/5.7/en/charset-connection.html)

## 添加用户及授权

添加用户

```sql
mysql> CREATE USER 'username'@'172.16.%' IDENTIFIED BY 'password'
```

- [13.7.1.3 CREATE USER Syntax](https://dev.mysql.com/doc/refman/5.7/en/create-user.html)

对用户授权

```sql
mysql> GRANT SELECT, INSERT, UPDATE, DELETE ON `dbname`.* TO 'username'@'172.16.%'
```

- [13.7.1.4 GRANT Syntax](https://dev.mysql.com/doc/refman/5.7/en/grant.html)

授予更多权限

```sql
mysql> GRANT CREATE ON `dbname`.* TO 'username'@'172.16.%'
mysql> GRANT SELECT ON `dbname2`.* TO 'username'@'172.16.%'
```

查看用户权限

```sql
mysql> show grants for 'username'@'172.16.%';
+-------------------------------------------------------------------------------------+
| Grants for username@172.16.%                                                        |
+-------------------------------------------------------------------------------------+
| GRANT USAGE ON *.* TO 'username'@'172.16.%'                                         |
| GRANT SELECT, INSERT, UPDATE, DELETE, CREATE ON `dbname`.* TO 'username'@'172.16.%' |
| GRANT SELECT ON `dbname2`.* TO 'username'@'172.16.%'                                |
+-------------------------------------------------------------------------------------+
```

## 技巧

避免插入主键或唯一健重复的记录（会提示警告而非致命错误）

```sql
mysql> INSERT IGNORE INTO `table_name` (`id`, `name`) VALUES ('1', 'suhua');
```
