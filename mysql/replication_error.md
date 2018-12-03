
##  复制出错

主库与从库数据不一致

```
Could not execute Delete_rows event on table tutorial.demo; 
Can't find record in 'demo', Error_code: 1032; handler error HA_ERR_END_OF_FILE; 
the event's master log mysql-bin.000007, end_log_pos 2782
```

查看主库 Binlog 日志

```sh
shell> mysqlbinlog mysql-bin.000007 --stop-position=2782 -v | tail -30
BEGIN
/*!*/;
# at 2683
#181130 16:51:45 server id 1  end_log_pos 2737 CRC32 0x1330c81b         Table_map: `tutorial`.`demo` mapped to number 108
# at 2737
#181130 16:51:45 server id 1  end_log_pos 2782 CRC32 0xad7c01cf         Delete_rows: table id 108 flags: STMT_END_F

BINLOG '
IfoAXBMBAAAANgAAALEKAAAAAGwAAAAAAAEACHR1dG9yaWFsAARkZW1vAAIDDwJQAAMbyDAT
IfoAXCABAAAALQAAAN4KAAAAAGwAAAAAAAEAAgAC//wGAAAABGxhbGHPAXyt
'/*!*/;
### DELETE FROM `tutorial`.`demo`
### WHERE
###   @1=6
###   @2='lala'
```

从库的记录与主库不一致。

```sql
mysql> select * from demo;
+------+-----------------+
| id   | name            |
+------+-----------------+
|    6 | lala2           |
+------+-----------------+
5 rows in set (0.00 sec)
```

将该记录修改成与主库一致。

```sql
update demo set name = lala where id = 6;
```

重启复制服务

```sql
STOP SLAVE;
START SLAVE;
```

