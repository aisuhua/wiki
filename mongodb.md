## 安装

```
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927

vim /etc/apt/sources.list.d/mongodb.list
deb https://mirrors.tuna.tsinghua.edu.cn/mongodb/apt/ubuntu bionic/mongodb-org/stable multiverse

sudo apt-get update
sudo apt-get install -y mongodb-org
```

- [MongoDB 镜像使用帮助](https://mirror.tuna.tsinghua.edu.cn/help/mongodb/)

## 常见问题

- [CentOS 7 - yum won't install mongodb](https://unix.stackexchange.com/questions/369620/centos-7-yum-wont-install-mongodb)
