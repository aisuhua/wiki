## 安装

安装

```sh
shell> sudo add-apt-repository ppa:nginx/stable
shell> apt-get update
shell> apt-get install nginx
```

- [NGINX PPA](https://launchpad.net/~nginx/+archive/ubuntu/stable)


启动

```sh
shell> service nginx start
```

重新加载配置文件

```sh
shell> nginx -s reload
```

## 参考文献

- [Install](https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)
- [如何正确配置 Nginx 和 PHP](http://blog.jobbole.com/50121/)
