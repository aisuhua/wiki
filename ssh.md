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

参考文献

- [ssh-keygen 中文手册](http://www.jinbuguo.com/openssh/ssh-keygen.html)
