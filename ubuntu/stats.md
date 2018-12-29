## netstat

查看端口的使用情况

```sh
shell> netstat -anp | grep :80
```

查看活动中的网络情况

```sh
shell> netstat -autlp
```

## ifstat

安装

```sh
shell> apt-get install ifstat 
shell> wget  http://gael.roualland.free.fr/ifstat/ifstat-1.1.tar.gz
shell> tar -zxvf ifstat-1.1.tar.gz
shell> cd ifstat-1.1/
shell> ./configure
shell> make && make install
```

查看实时网速

```sh
shell> ifstat
```

- [Ifstat](http://gael.roualland.free.fr/ifstat/)

## iostat

安装

```sh
apt-get install sysstat
```

查看磁盘写入速度

```sh
iostat -d -k 1 10 
```

- [Linux查看磁盘读写性能(iostat命令)的方法](https://blog.csdn.net/n8765/article/details/52044862)

## ps

查看进程状态

```sh
shell> ps -aux
```

查看进程状态，带有父子进程

```sh
shell> ps -ef
```
