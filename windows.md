
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