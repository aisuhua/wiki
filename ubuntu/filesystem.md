# 文件系统

umount: /mnt/www1: target is busy 解决方法

```
# 查看有那些用户正在使用该分区
fuser -vm /mnt/www1 
````
