## 启用

对 PHPSTORM 进行配置 

- Add CLI Interpreter. `File > Settings > Languages & Frameworks > PHP`
- Set Xdebug Port (eg. 9001). `... > PHP > Debug > Xdebug`
- Add a server for the project. `... > PHP > Debug > Servers`
- Apply and OK.
- Add PHP Web Application. `Run > Edit Configurations > + PHP Web Application`

修改 xdebug.ini 

```shell
shell> vim /etc/php/7.2/mods-available/xdebug.ini 
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_host=192.168.1.100
xdebug.remote_port=9001
```

开始 debug

```shell
shell> curl http://www.example.com?XDEBUG_SESSION_START=PHPSTORM
shell> curl -H 'Cookie: XDEBUG_SESSION=PHPSTORM' http://www.example.com
```

- [Xdebug & Zend Debugger bookmarklets generator for PhpStorm](https://www.jetbrains.com/phpstorm/marklets/)

## how it work?

![how it work?](https://xdebug.org/images/docs/dbgp-setup.gif)

- [Remote Debugging](https://xdebug.org/docs/remote)


