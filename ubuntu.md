查看网速

```
shell> apt-get install ifstat 
# or
shell> wget  http://gael.roualland.free.fr/ifstat/ifstat-1.1.tar.gz
shell> tar -zxvf ifstat-1.1.tar.gz
shell> cd ifstat-1.1/
shell> ./configure
shell> make && make install

shell> ifstat
       eth0       
 KB/s in  KB/s out
 1003.77     51.67
  999.17     45.02
  922.40     37.69
...

http://gael.roualland.free.fr/ifstat/
```