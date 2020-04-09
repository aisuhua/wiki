## ssh

安装

```sh
shell> apt-get install openssh-server
```

允许 root 用户登陆

```sh
shell> vim /etc/ssh/sshd_config
PermitRootLogin yes
shell> service sshd restart
```

本机生成密钥

```sh
shell> ssh-keygen
```

本机免密登录远程服务器

```sh
a@A:~> cat .ssh/id_rsa.pub
b@B:~> mdir ~/.ssh
b@B:~> chmod 700 ~/.ssh
b@B:~> vim ~/.ssh/authorized_keys
b@B:~> chmod 600 ~/.ssh/authorized_keys 
```

- [SSH login without password](http://www.linuxproblem.org/art_9.html)

管理多个密钥

```sh
shell> ssh-keygen -t rsa -C "github.com" -f ~/.ssh/id_rsa_github
shell> ssh-keygen -t rsa -C "gitlab.com" -f ~/.ssh/id_rsa_gitlab
shell> vim ~/.ssh/config
# github
Host github.com
HostName github.com
PreferredAuthentications publickey
IdentityFile ~/.ssh/id_rsa_github

# gitlab
Host gitlab.com
HostName gitlab.com
PreferredAuthentications publickey
IdentityFile ~/.ssh/id_rsa_gitlab
shell> chmod 600 ~/.ssh/config
shell> ssh -T git@github.com
shell> ssh -T git@gitlab.com
```

- [git 配置多个SSH-Key](https://blog.csdn.net/dqchouyang/article/details/54898910)

使用私钥重新生成公钥

```sh
shell> ssh-keygen -y -f ~/.ssh/id_rsa > ~/.ssh/id_rsa.pub
```

避免 Are you sure you want to continue connecting (yes/no)

```sh
shell> vim /etc/ssh/ssh_config
StrictHostKeyChecking no
```

参考文献

- [ssh-keygen 中文手册](http://www.jinbuguo.com/openssh/ssh-keygen.html)

防止自动断开

```
# vim /etc/ssh/sshd_config
ClientAliveInterval 30
ClientAliveCountMax 86400

service sshd restart
```

- https://cloud.tencent.com/developer/article/1163845

## pssh

安装

```sh
shell> wget https://github.com/lilydjwg/pssh/archive/v2.3.1.tar.gz
shell> tar -zxvf v2.3.1.tar.gz
shell> cd pssh-2.3.1/
shell> python setup.py install
```

对远程服务器执行命令，前提是当前用户能免密登录远程服务器，而且 suhua 用户有权限执行该命令

```sh
shell> pssh -H suhua@192.168.1.100:22 -i 'sudo /etc/init.d/php7.2-fpm restart'
```

以上的简化版本

```sh
suhua@ubuntu~> pssh -H 192.168.1.100 -i 'sudo /etc/init.d/php7.2-fpm restart'
```

在多台服务器上执行命令

```sh
suhua@ubuntu~> pssh -H 192.168.1.100 -H 192.168.1.101:25680 -i 'sudo /etc/init.d/php7.2-fpm restart'
```

将主机写到文件中，语法为 `用户名@主机ip:端口`

```sh
shell> vim hosts
192.168.1.100:22
192.168.1.101:25680
suhua@ubuntu~> pssh -h hosts -i "sudo /etc/init.d/php7.2-fpm restart"
```

- [pssh](https://github.com/lilydjwg/pssh)
- [批量管理工具-pdsh|pssh](https://blog.opskumu.com/pdsh-pssh.html)
