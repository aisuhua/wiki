## 安装

安装

```sh
shell> apt-get install haproxy
```

添加配置（格式1）

```sh
shell> vim /etc/haproxy/haproxy.cfg
frontend public
    bind *:80
    default_backend wp-web

backend wp-web
    balance roundrobin
    server wp-web1 192.168.31.201:80 check
    server wp-web2 192.168.31.202:80 check
```

添加配置（格式2）

```sh
shell> vim /etc/haproxy/haproxy.cfg
listen wp_web
     bind *:80
     mode http
     balance roundrobin
     server wp-web1 192.168.31.201:80 check
     server wp-web2 192.168.31.202:80 check
```

添加监控页面配置

```sh
shell> vim /etc/haproxy/haproxy.cfg
listen stats
    bind *:8100
    mode http
    stats uri /stats
    stats auth admin:admin
    stats refresh 5s
```

重启服务

```sh
shell> service haproxy restart
```

## 参考文献

- [HAProxy Documentation](https://cbonte.github.io/haproxy-dconv/)
- [大规模Linux集群架构和最佳实践](https://read.douban.com/ebook/39297083/)

