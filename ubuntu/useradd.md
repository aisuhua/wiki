## 添加用户

添加用户

```sh
shell> groupadd suhua
shell> useradd -g suhua -G sudo -s /bin/bash -d /home/suhua -m suhua
shell> passwd suhua
```

- [How to add new user in Linux](https://www.simplified.guide/linux/add-new-user)

删除用户

```sh
shell> userdel -r suhua
```

查看用户所属组

```sh
shell> groups suhua
```

## 添加 sudo 权限

修改配置文件

```sh
shell> vim /etc/sudoers
suhua ALL=(ALL) ALL
```

或将用户加入 sudo 分组

```sh
shell> usermod -a -G sudo suhua
```

- [How do I grant sudo privileges to an existing user? ](https://askubuntu.com/questions/168280/how-do-i-grant-sudo-privileges-to-an-existing-user)

免密执行 sudo 命令

```sh
shell> vim /etc/sudoers
suhua ALL=(ALL) NOPASSWD: ALL
```

- [Sudoers file, enable NOPASSWD for user, all commands](https://askubuntu.com/questions/334318/sudoers-file-enable-nopasswd-for-user-all-commands)
- [sudoers的深入剖析与用户权限控制](https://segmentfault.com/a/1190000007394449)

查看 sudo 权限

```sh
shell> sudo -l -U suhua
```
