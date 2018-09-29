查看网速

```
shell> apt-get install ifstat 

shell> wget  http://gael.roualland.free.fr/ifstat/ifstat-1.1.tar.gz
shell> tar -zxvf ifstat-1.1.tar.gz
shell> cd ifstat-1.1/
shell> ./configure
shell> make && make install

shell> ifstat

http://gael.roualland.free.fr/ifstat/
```

查看多核 CPU 的使用情况

```sh
shell> top
# 然后按数字 1 即可查看每个核心的状态
```
