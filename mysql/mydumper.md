
## 特征

mydumper是一个高性能多线程备份和恢复工具，能够实现记录级的多线程一致性备份。

1. 轻量级C语言写的
2. 执行速度比mysqldump快10倍
3. 事务性和非事务性表一致的快照(适用于0.2.2以上版本)
4. 快速的文件压缩
5. 支持导出binlog
6. 多线程恢复(适用于0.2.1以上版本)
7. 以守护进程的工作方式，定时快照和连续二进制日志(适用于0.5.0以上版本)
8. 开源 (GNU GPLv3)

mydumper 对表的结构、数据、存储过程等分开进行备份，其备份效率高，而且可以方便地使用 myloader 进行恢复。使用该工具进行备份直接就可以保证备份数据的一致性，因为它内部实现了针对 MyISAM 和 InnoDB 表进行必要的锁表或创建事务一致性快照等步骤。另外，在导入文件 metadata 中还包含了备份时 binlog 日志的信息，这对后续进行数据恢复十分方便。

导入步骤说明：

1. 主线程 `FLUSH TABLES WITH READ LOCK`，施加全局只读锁，以阻止 DML语句写入，保证数据的一致性
2. 读取当前时间点的二进制日志文件名和日志写入的位置并记录在 metadata 文件中，以供即使点恢复使用
3. N个（线程数可以指定，默认是4）dump线程 `START TRANSACTION WITH CONSISTENT SNAPSHOT` 开启读一致的事务
4. dump non-InnoDB tables, 首先导出非事务引擎的表
5. 主线程 UNLOCK TABLES 非事务引擎备份完后，释放全局只读锁
6. dump InnoDB tables, 基于事务导出 InnoDB 表
7. 事务结束

metadata 的文件内容，这里是在从库进行的备份。

```sh
shell> cat metadata 
Started dump at: 2017-04-14 15:05:52
SHOW MASTER STATUS:
        Log: mysql-bin-83-3306.000021
        Pos: 12679211
        GTID:
SHOW SLAVE STATUS:
        Host: 192.168.20.100
        Log: mysql-bin-83-3306.000014
        Pos: 120
        GTID:
Finished dump at: 2017-04-14 15:05:57
```



## 参考文献

- [mydumper](https://github.com/maxbube/mydumper)
- [mydumper 0.9.1 documentation](http://lira.no-ip.org:8080/doc/mydumper-doc/html/index.html#)
- [Backing up binary log files with mysqlbinlog](https://www.percona.com/blog/2012/01/18/backing-up-binary-log-files-with-mysqlbinlog/)
- [mydumper备份原理和使用方法](https://www.cnblogs.com/linuxnote/p/3817698.html)
- [mydumper原理和使用](https://blog.csdn.net/mysqldba23/article/details/70171097)
