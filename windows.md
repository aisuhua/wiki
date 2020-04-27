
查看端口占用

```
netstat -ano | findstr :9002
```

查看进程信息

```
tasklist |findstr 17944
```

- [以一种访问权限不允许的方式做了一个访问套接字的尝试](https://www.cnblogs.com/zhengdongdong/p/12001152.html)

开机启动

```
%programdata%\Microsoft\Windows\Start Menu\Programs\Startup
```

- https://jingyan.baidu.com/article/eae07827ad2bba1fec5485ae.html

## 其他

无法将windows安装到磁盘0的分区1

```
SHIFT+F10

dos> diskpart
dos> list disk
dos> select disk 0
dos> clean
dos> convert mbr
dos> create partition primary

http://blog.sina.com.cn/s/blog_71c6e0ea0101nrpr.html
```

bootmgr is missing 

```
1、开机,一直狂按DEL键或F2键或者F12键,目的进入CMOS设置!
2、切换到Advanced BIOS,设置First Boot Device为 CDROM!不同的机子设置方法不一,当然有的可以通过DEL键直接选择。
3、插入系统盘恢复光盘!启动电脑。
4、显示光盘的安装界面后,点击修复计算机!
5、再出来的对话框中,点击自动修复!
6、修复完成后,取出光盘!
7、重启电脑,看问题有没有解决。
```

- [linux 下 格式化u盘 并分区 为fat32文件系统][1]

[1]: https://blog.csdn.net/linkedin_35878439/article/details/82020925
