
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