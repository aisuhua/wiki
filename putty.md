## 常见问题

### 上传和下载文件

切换到文件所在目录

```
d:
cd path/to/
```

上传和下载

```
D:\>psftp root@192.168.1.2
Using username "root".
root@192.168.1.2's password:
Remote working directory is /root
psftp> pwd
Remote directory is /root
psftp> put nginx-patch.tar.gz # 上传文件
local:nginx-patch.tar.gz => remote:/root/nginx-patch.tar.gz
psftp> put -r folder1 # 上传文件夹
psftp> get nginx-patch.tar.gz # 下载文件
psftp> get -r folder1 # 下载文件夹
```

