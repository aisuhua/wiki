## Kill Queries

找出所有需要 kill 掉的进程

```sql
SELECT GROUP_CONCAT(CONCAT('KILL ',id,';') SEPARATOR ' ') 'Paste the following query to kill all processes'
FROM information_schema.processlist
WHERE user<>'system user'\G
```

将进程 kill 掉

```sql
KILL 30; KILL 29
```

找出运行时间超过 20 分钟的进程

```sql
SELECT GROUP_CONCAT(CONCAT('KILL ',id,';') SEPARATOR ' ') 'Paste the following query to kill all processes'
FROM information_schema.processlist
WHERE user<>'system user' and time >= 1200\G
```

找出连接特定 DB 的所有进程

```sql
SELECT GROUP_CONCAT(CONCAT('KILL ',id,';') SEPARATOR ' ') 'Paste the following query to kill all processes'
FROM information_schema.processlist
WHERE user<>'system user' and db = ''\G
```
