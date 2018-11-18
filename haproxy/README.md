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
    option forwardfor
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
     option forwardfor
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
- [《大规模Linux集群架构和最佳实践》](https://read.douban.com/ebook/39297083/)
- [LOAD BALANCER ADMINISTRATION](https://access.redhat.com/documentation/en-us/red_hat_enterprise_linux/7/html/load_balancer_administration/index)
- [Chapter 16 Load Balancing and High Availability Configuration](https://docs.oracle.com/cd/E37670_01/E41138/html/ol6-loadbal.html)
- [keepalived+haproxy双主高可用负载均衡](http://blog.51cto.com/nmshuishui/1405486)

