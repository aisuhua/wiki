## mysqldump

备份单个库，不含库创建语句

```sh
mysqldump tutorial > tutorial.sql
```

上面语句的完整版本

```sh
mysqldump -h localhost -u root -p tutorial > tutorial.sql
```
