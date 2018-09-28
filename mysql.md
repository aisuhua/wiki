避免插入主键或唯一健重复的记录（会提示警告而非致命错误）

```sql
mysql> INSERT IGNORE INTO `table_name` (`id`, `name`) VALUES ('1', 'suhua');
```