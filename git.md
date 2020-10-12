## 安装

安装

```sh
apt-get install git
```

初始化

```sh
git config --global user.name "aisuhua"
git config --global user.email 1079087531@qq.com
```

## 其他

清理未加入版本的文件

```sh
git clean -d -f ""
```

使用代理

```sh
git config --global http.proxy 'socks5://127.0.0.1:1080'
git config --global https.proxy 'socks5://127.0.0.1:1080'

git config --global --unset http.proxy
git config --global --unset https.proxy
```

或在命令行中使用

```sh
git -c "http.proxy=socks5://127.0.0.1:1080" clone git@github.com:...
```

保存账号密码

```
git config --global credential.helper store
```

- [How to save username and password in GIT](https://stackoverflow.com/questions/35942754/how-to-save-username-and-password-in-git-gitextension)

提示 warning: LF will be replaced by CRLF

```
git config --global core.autocrlf false
```

- [git如何避免”warning: LF will be replaced by CRLF“提示？](https://www.zhihu.com/question/50862500)
