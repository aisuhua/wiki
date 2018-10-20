## 安装

使用 docker 镜像进行安装

```sh
shell> sudo docker run --detach \
    --hostname gitlab.example.com \
    --publish 443:443 --publish 80:80 --publish 22:22 \
    --name gitlab \
    --restart always \
    --volume /srv/gitlab/config:/etc/gitlab \
    --volume /srv/gitlab/logs:/var/log/gitlab \
    --volume /srv/gitlab/data:/var/opt/gitlab \
    gitlab/gitlab-ce:8.17.8-ce.0
```

为了避免冲突，物理机器 SSH 端口修改为 25680

```sh
shell> vim /etc/ssh/sshd_config
# What ports, IPs and protocols we listen for
Port 25680

shell> service ssh restart
```

## 备份

```sh
shell> docker stop gitlab
shell> docker commit gitlab local/gitlab-container-20181020
shell> docker save local/gitlab-container-20181020 > /root/gitlab-container-20181020.tar

shell> docker run --rm --volumes-from gitlab -v $(pwd):/backup ubuntu tar cvf /backup/gitlab-volume-etc-20181020.tar /etc/gitlab
shell> docker run --rm --volumes-from gitlab -v $(pwd):/backup ubuntu tar cvf /backup/gitlab-volume-log-20181020.tar /var/log/gitlab
shell> docker run --rm --volumes-from gitlab -v $(pwd):/backup ubuntu tar cvf /backup/gitlab-volume-opt-20181020.tar /var/opt/gitlab
```

## 恢复

加载镜像

```sh
shell> docker load -i gitlab-container-20181020.tar 
```

创建容器

```sh
shell> sudo docker create \
    --hostname gitlab.aisuhua.com \
    --publish 443:443 --publish 80:80 --publish 22:22 \
    --name gitlab \
    --restart always \
    --volume /srv/gitlab/config:/etc/gitlab \
    --volume /srv/gitlab/logs:/var/log/gitlab \
    --volume /srv/gitlab/data:/var/opt/gitlab \
    local/gitlab-container-20181020
```

