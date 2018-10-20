生成密钥

```sh
shell> ssh-keygen
```

免密登录远程服务器

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
shell> ssh-keygen -t rsa -f ~/.ssh/id_rsa_github -C "youremail@example.com"
shell> vim ~/.ssh/config
# github
Host github.com
HostName github.com
IdentityFile ~/.ssh/id_rsa_github

shell> chmod 600 ~/.ssh/config
shell> ssh -T git@github.com
```

使用私钥重新生成公钥

```sh
shell> ssh-keygen -y -f ~/.ssh/id_rsa > ~/.ssh/id_rsa.pub
```
