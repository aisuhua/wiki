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
```

或在命令行中使用

```sh
git -c "http.proxy=socks5://127.0.0.1:1080" clone git@github.com:...
```
