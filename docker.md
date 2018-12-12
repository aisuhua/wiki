## 安装

安装

```sh
shell> apt-get -y install apt-transport-https ca-certificates curl software-properties-common
shell> curl -f sSL http://mirrors.aliyun.com/docker-ce/linux/ubuntu/gpg| sudo apt-key add -
shell> add-apt-repository "deb [arch=amd64] http://mirrors.aliyun.com/docker-ce/linux/ubuntu $(lsb_release -cs) stable"
shell> apt-get -y update
shell> apt-get -y install docker-ce
```

- [安装Docker](https://help.aliyun.com/document_detail/60742.html)

配置国内镜像

```sh
shell> mkdir -p /etc/docker
shell> sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://qby02i3s.mirror.aliyuncs.com"]
}
EOF
shell> systemctl daemon-reload
shell> systemctl restart docker
```

- [容器镜像服务](https://cr.console.aliyun.com/)



## 管理

### docker 

启动

```sh
shell> systemctl start docker
```

停止

```sh
shell> systemctl stop docker
```

查看容器状态

```sh
shell> docker ps
shell> docker ps -a
```

### container

创建并运行容器

```sh
shell> docker run -v /www/web:/www/web -p 80:80 --dns 223.5.5.5 --name demo ubuntu
```

查看容器信息

```sh
shell> docker inspect demo
```

进入容器

```sh
shell> docker exec -i -t demo /bin/bash
```

停止容器

```sh
shell> docker stop demo
```

删除容器

```sh
shell> docker rm demo
```

复制文件到未运行的容器

```sh
shell> docker cp demo:/etc/supervisor/conf.d/program.conf .
shell> docker cp my_file demo:/www/web
```

停止和删除所有容器

```sh
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
```


- [How to edit files in stopped/not starting docker container](https://stackoverflow.com/questions/32750748/how-to-edit-files-in-stopped-not-starting-docker-container)
