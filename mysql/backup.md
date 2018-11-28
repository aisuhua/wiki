## mysqldump

备份单个库的所有表

```sh
mysqldump tutorial > tutorial.sql
```

上面语句的完整版本

```sh
mysqldump -h localhost -u root -p tutorial > tutorial.sql
```

备份指定表

```sh
mysqldump tutorial > tutorial.sql
```

备份单个库

```sh
mysqldump --databases tutorial > tutorial.sql
```

备份多个库

```sh
mysqldump --databases tutorial --databases demo > backup.sql
```

若存在 utf8mb4 编码的数据，需要指定导出时的编码。

```sh
mysqldump --default-character-set=utf8mb4 --databases tutorial > backup.sql
```

只导出 DDL 语句，不含数据

```sh
mysqldump --no-data --databases tutorial > tutorial.sql
```
