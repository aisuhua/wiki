# LOAD DATA

The LOAD DATA INFILE statement reads rows from a text file into a table at a very high speed. LOAD DATA INFILE is the complement of SELECT ... INTO OUTFILE. To write data from a table to a file, use SELECT ... INTO OUTFILE. To read the file back into a table, use LOAD DATA INFILE. 

## 基本使用

demo.txt

```txt
jpg 9980EB23FFFCE4F627071A963AA62FBA6350DE75 86901 c202 GCHHB_7LBuf3RnDA8alNL6of5IClAIyRyoYfDZUG
jpg 9AF105CEEAD7BA56A8B514AD1D9E8A799B538E75 2187526 c202 kBllKRDpCTx954BUtGswl0lhFGyut0tj9P8rJ1ID
mp4 D58CD748576BC1F7A2CF5622B6E0EB3C7DCC78F5 3301072 c202 1B00OAH4CC1s9pBbq53OpohZPHZfAK-yr93rA6FN
...
```

新增 demo 表

```
create table `demo` (
  `id` bigint(20) unsigned not null auto_increment comment '主键id',
  `file_type` varchar(128) not null default '' comment '文件类型',
  `sha1` char(40) not null default '' comment '文件sha1',
  `file_size` bigint(20) not null default '0' comment '文件大小',
  `cluster` varchar(20) not null default '' comment '集群',
  `object_id` varchar(255) not null default '' comment '文件object_id',
  primary key (`id`),
  unique key (`sha1`)
) engine=innodb default character set utf8mb4 collate utf8mb4_unicode_ci;
```

将 demo.txt 的数据导入到 demo 表

```sql
load data local infile '/root/demo.txt' ignore into table demo 
FIELDS TERMINATED BY ' ' 
(file_type, sha1, file_size, cluster, object_id);
```

## 常见问题

### 安全限制

```
The MySQL server is running with the --secure-file-priv option so it cannot execute this statement
```

将文件移动到 `secure_file_priv` 的默认目录。

```sh
shell> move /root/demo.txt /var/lib/mysql-files
```

- [How should I tackle --secure-file-priv in MySQL?](https://stackoverflow.com/questions/32737478/how-should-i-tackle-secure-file-priv-in-mysql)

然后执行 `load data` 时要写完整路径。

```sql
load data infile '/var/lib/mysql-files/demo.txt' into ...
```

或禁用 `secure_file_priv`。

```conf
[mysqld]
secure_file_priv = ''
```

或修改 `secure_file_priv` 的默认目录。

```conf
[mysqld]
secure_file_priv = '/root'
```

### 权限不足

```
ERROR 13 (HY000): Can't get stat of '/root/demo.txt' (Errcode: 13 - Permission denied)
```

使用 `local` 关键字。

```sql
load data local infile ...
```

## 参考文献

- [LOAD DATA INFILE Syntax](https://dev.mysql.com/doc/refman/5.7/en/load-data.html)
