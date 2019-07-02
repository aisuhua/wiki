## 本地调试

### PhpStorm

#### step1

设置 PhpStorm 监听 9005 端口，这样就可以接收来自于 Xdebug 的调试信息。 

File > Settings > Languages & Frameworks > PHP > Debug > [Xdebug] Debug port: **9005**。

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
xdebug.remote_port=9005
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


