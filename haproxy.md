## 安装

安装

```sh
shell> apt-get install haproxy
```

添加配置

```sh
shell> vim /etc/haproxy/haproxy.cfg
# Monitor
listen stats
    bind *:8100
    mode http
    stats uri /stats
    stats auth admin:admin
    stats refresh 5s

# HTTP
frontend http-in
    bind *:80
    mode http
    option forwardfor
    default_backend wp-web

backend wp-web
    balance roundrobin
    server wp-web1 192.168.1.100:80 check
    server wp-web2 192.168.1.101:80 check

# TCP
listen wp-db
    bind *:3306
    mode tcp
    option tcplog
    balance roundrobin
    server wp-db1 192.168.1.40:3306 check
    server wp-db2 192.168.1.41:3306 check
```

重启服务

```sh
shell> service haproxy restart
```

## 配置示例

虚拟主机、自定义 HTTP header

```sh
shell> vim /etc/haproxy/haproxy.cfg
frontend http-in
    bind *:80
    mode http

    # HTTP header
    option forwardfor
    http-request set-header X-Forwarded-Port %[dst_port]
    http-request set-header X-Forwarded-Proto https if { ssl_fc }
    http-request set-header X-Real-IP %[src]
    http-response set-header X-LB-Name lb1

    # foo.aisuhua.com
    acl is_foo_aisuhua_com hdr_end(host) -i foo.aisuhua.com
    use_backend foo-aisuhua-com if is_foo_aisuhua_com

    # demo_aisuhua_com
    acl is_demo_aisuhua_com hdr_end(host) -i demo.aisuhua.com
    use_backend demo-aisuhua-com if is_demo_aisuhua_com

    default_backend wp-web

backend foo-aisuhua-com
    balance roundrobin
    server server1 192.168.1.200:80 check
    server server2 192.168.1.201:80 check

backend demo-aisuhua-com
    balance source
    server server1 192.168.1.210:80 check
    server server1 192.168.1.211:80 check

backend wp-web
    balance roundrobin
    server wp-web1 192.168.1.100:80 check
    server wp-web2 192.168.1.101:80 check
```

- [HAproxy virtual site load balancing](https://gist.github.com/daemonza/1984806)

## 参考文献

- [HAProxy Documentation](https://cbonte.github.io/haproxy-dconv/)
- [haproxy配置详解](http://blog.51cto.com/leejia/1421882)
- [Using SSL Certificates with HAProxy](https://serversforhackers.com/c/using-ssl-certificates-with-haproxy)
