## 本地调试

### PhpStorm

#### step1

设置 PhpStorm 监听 9000 端口，这样就可以接收来自于 Xdebug 的调试信息。 

File > Settings > Languages & Frameworks > PHP > Debug > [Xdebug] Debug port: **9000**。

#### step2

配置项目的 mapping 信息

File > Settings > Languages & Frameworks > PHP > Debug > Servers

- Name: 随便，跟 Host 一致可便于区分。
- Host: 要填写你调试站点的域名。如果不一样，就需要修改。
- Port: 访问网站的端口，一般 80。
- Debugger: Xdebug，因为这里我们用的就是它。

Use path mappings 这个可选，一般只有在服务器目录跟本地目录路径不一致时才需要填写，而且一般填写只需要填写根目录即可。

#### step3

点击开始监听端口即可，此时 PhpStorm 就监听着 9005 端口，一旦收到数据就会进入 debug 模式。

### Xdebug

#### step1

填写你本机的 IP 地址即 PhpStorm 所在机器，以及监听端口等信息。

```
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_host=192.168.1.229
xdebug.remote_port=9001
```

IP 信息也可以让 Xdebug 自动判断，它也会正确地将数据发回来源主机。因为一般你会在本机通过 XDEBUG_SESSION 触发 Xdebug，那么它就知道你的 IP 了。

```
# xdebug.remote_host=192.168.1.229
xdebug.remote_connect_back=1
```

#### step2

service php7.2-fpm reload 

### 调试

通过在地址栏加上 XDEBUG_SESSION_START 或者在 Cookie 加入 XDEBUG_SESSION 都是可以的。session_name 可以随便填，在没有使用 DBGp proxy 的情况下，它没有其他作用。

```
?XDEBUG_SESSION_START=session_name
Cookie: XDEBUG_SESSION=session_name
```

## 远程主机调试

### 服务器

其实远程主机和本地调试一样配置都可以实现 debug 的，但是为了考虑安全性，我们使用 DBGp proxy 来做一层代理。

```
Xdebug -> proxy 127.0.0.1:9002
PhpStorm -> proxy 172.16.200.200:9003
```

服务器上安装 DBGp proxy

```
cd /usr/local
wget http://downloads.activestate.com/Komodo/releases/10.2.3/remotedebugging/Komodo-PythonRemoteDebugging-10.2.3-89902-linux-x86_64.tar.gz
tar -zxvf Komodo-PythonRemoteDebugging-10.2.3-89902-linux-x86_64.tar.gz
mv Komodo-PythonRemoteDebugging-10.2.3-89902-linux-x86_64 dbgpproxy
cd dbgpproxy
export PYTHONPATH=./pythonlib:./python3lib:$PYTHONPATH
```

启动 DBGp proxy 

```
./pydbgpproxy -d 127.0.0.1:9002 -i 172.16.200.200:9003
```

> The arguments define which IP address and port to listen for debugger connections from the web server and on which IP address and port to listen for developers.

### PhpStorm

向 DBGp proxy 注册本机信息，主要是告知它我本机的 IP、端口、idekey 是多少，注意这里的 idekey 是必须每个调试客户端都不一样。

File > Settings > Languages & Frameworks > PHP > Debug > DBGp proxy 

```
IED KEY: suhua 随便填，但是它是你的唯一标识，不要跟别人冲突就行
Host: 172.16.200.200 DBGp proxy 的 IP 地址
Port: 9003 DBGp proxy 的端口
```

填好之后，注册一下就行。

Tools > DBGp proxy > Register IDE 点击一下就能注册啦。此时你能从 DBGp proxy 的视窗中看到以下信息，当然 IDE 也会有注册成功的提示哦。

```
# 注册的过程，其实就是将 PhpStorm 所在的主机 IP/端口/idekey 告知 proxy
/usr/local/dbgpproxy# ./start-dbgp-proxy.sh 
INFO: dbgp.proxy: starting proxy listeners.  appid: 15799
INFO: dbgp.proxy:     dbgp listener on 127.0.0.1:9002
INFO: dbgp.proxy:     IDE listener on  172.16.200.200:9003
INFO: dbgp.proxy: Server:onConnect ('192.168.1.229', 53072) [proxyinit -p 9000 -k suhua -m 1]
```

start-dbgp-proxy.sh 这是为了我方便重启做的，它的内容如下：

```
export PYTHONPATH=/usr/local/dbgpproxy/pythonlib:/usr/local/dbgpproxy/python3lib:$PYTHONPATH
/usr/local/dbgpproxy/pydbgpproxy -d 127.0.0.1:9002 -i 172.16.200.200:9003
```

要注意的是，如果 DBGp proxy 重启过了，那么还是需要重新点击 Register IDE 进行注册才行的。

所有这些做好了以后，其实就可以调试啦，点击那个小电话就行。但是要注意的是，此时的 XDEBUG_SESSION 必须要是 suhua，表示我的 idekey 是 suhua 的意思。

## 其他

如果你想在浏览器中点一下按钮就可以开启调试，那么可以安装下面这些插件。

- [Xdebug Helper Chrome](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
- [Xdebug Helper Firefox](https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/)

这些插件实际上只是在你每次请求的时候，加上了 XDEBUG_SESSION=suhua 类似这样的 Cookie，实际上自己在使用 curl 的时候自行设置就可以了，插件只是便于操作而已。

## 参考

- []
