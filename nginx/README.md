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

## 站点配置

静态站点

```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    
    server_name _;
    root /var/www/html;

    location / {
        try_files $uri $uri/ =404;
    }
}
```

PHP 站点

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name foo.aisuhua.com;
    root /www/web/foo;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```

错误页面处理

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name foo.aisuhua.com;
    root /www/web/foo;
    
    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }
    
    # 在 /www/web/foo 目录下放置 404.html 和 50x.html 页面
    location ~ /(404|50x)\.html {
        internal;
    }
}
```

## 负载均衡

服务器规划

| 服务器名称   | IP             | 用途          |
| ------------ | -------------- | ------------- |
| Nginx Master | 192.168.31.220 | 提供负载均衡  |
| Web1 服务器  | 192.168.31.201 | 提供 Web 服务 |
| Web2 服务器  | 192.168.31.202 | 提供 Web 服务 |

负载均衡基本配置

```nginx
http {
    upstream foo {
        ip_hash;
        server 192.168.31.201:80;
        server 192.168.31.202:80;
    }

    server {
        listen 80;
        server_name foo.aisuhua.com;

        location / {
            include /etc/nginx/proxy_params;
            proxy_pass http://foo;
        }
    }
}
```

负载均衡策略

- [Using nginx as HTTP load balancer](http://nginx.org/en/docs/http/load_balancing.html)

## 负载均衡高可用 Nginx + Keepalived

服务器规划

| 服务器名称   | IP             | 用途            |
| ------------ | -------------- | --------------- |
| Nginx Master | 192.168.31.220 | 提供负载均衡    |
| Nginx Backup | 192.168.31.221 | 提供负载均衡    |
| LVS-DR-VIP   | 192.168.31.20  | 网站的 VIP 地址 |
| Web1 服务器  | 192.168.31.201 | 提供 Web 服务   |
| Web2 服务器  | 192.168.31.202 | 提供 Web 服务   |

前提条件：两台 Nginx 负载均衡服务器能正常提供服务和安装了 Keepalived。

Nginx Master 配置

```sh
shell> vim /etc/keepalived/keepalived.conf 
! Configuration File for keepalived
global_defs {
    notification_email {
        aisuhua@example.com
        itsection@example.com
    }
    notification_email_from itsection@example.com
    smtp_server mail.example.com
    smtp_connect_timeout 30
    router_id LVS_lb1
}

vrrp_script chk_nginx {
    script "killall -0 nginx"
    interval 2
    weight -5
    fall 3
    rise 2
}

vrrp_instance VI_1 {
    state MASTER
    interface enp0s3
    mcast_src_ip 192.168.31.220
    virtual_router_id 51
    priority 101
    advert_int 2
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
       192.168.31.20
    }
    track_script {
       chk_nginx
    }
}
```

Nginx Backup 配置

```sh
shell> vim /etc/keepalived/keepalived.conf 
! Configuration File for keepalived
global_defs {
    notification_email {
        aisuhua@example.com
        itsection@example.com
    }
    notification_email_from itsection@example.com
    smtp_server mail.example.com
    smtp_connect_timeout 30
    router_id LVS_lb2
}

vrrp_script chk_nginx {
    script "killall -0 nginx"
    interval 2
    weight -5
    fall 3
    rise 2
}

vrrp_instance VI_1 {
    state BACKUP
    interface enp0s3
    mcast_src_ip 192.168.31.221
    virtual_router_id 51
    priority 100
    advert_int 2
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
       192.168.31.20
    }
    track_script {
       chk_nginx
    }
}
```

## 主主负载均衡高可用 Nginx + Keepalived

服务器规划

| 服务器名称   | IP             | 用途            |
| ------------ | -------------- | --------------- |
| Nginx-Master-1 | 192.168.31.220 | 提供负载均衡    |
| Nginx Master 2 | 192.168.31.221 | 提供负载均衡    |
| VIP-1   | 192.168.31.20  | 集群 VIP 地址一 |
| VIP-2   | 192.168.31.30  | 集群 VIP 地址二 |
| Web1 服务器  | 192.168.31.201 | 提供 Web 服务   |
| Web2 服务器  | 192.168.31.202 | 提供 Web 服务   |

原理说明

> 其实就是通过 Keepalived 生成两个实例，两台 Nginx 互为备份，即第一台是第二台机器的备机，
> 第二台机器也是第一台的备机，生成的两个 VIP 地址分别对应我们的站点 http://foo.aisuhua.com，
> 这样大家在公网上都可以通过 DNS 轮询来访问得到该网站。
> 任何一台 Nginx 机器如果发生硬件损坏，Keepalived 会自动将它的 VIP 地址切换到另一台，而不影响客户端访问。
> 摘抄自[《Linux集群和自动化运维》](https://www.amazon.cn/dp/B01KGTDEW0)

Nginx Master1 配置

```sh
shell> vim /etc/keepalived/keepalived.conf 
! Configuration File for keepalived
global_defs {
    notification_email {
        aisuhua@example.com
        itsection@example.com
    }
    notification_email_from itsection@example.com
    smtp_server mail.example.com
    smtp_connect_timeout 30
    router_id LVS_lb1
}

vrrp_script chk_nginx {
    script "killall -0 nginx"
    interval 2
    weight -5
    fall 3
    rise 2
}

vrrp_instance VI_1 {
    state MASTER
    interface enp0s3
    mcast_src_ip 192.168.31.220
    virtual_router_id 51
    priority 101
    advert_int 2
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
       192.168.31.20
    }
    track_script {
       chk_nginx
    }
}

vrrp_instance VI_2 {
    state BACKUP
    interface enp0s3
    mcast_src_ip 192.168.31.220
    virtual_router_id 151
    priority 100
    advert_int 2
    authentication {
        auth_type PASS
        auth_pass 2222
    }
    virtual_ipaddress {
       192.168.31.30
    }
    track_script {
       chk_nginx
    }
}
```

Nginx Master2 配置

```sh
shell> vim /etc/keepalived/keepalived.conf 
! Configuration File for keepalived
global_defs {
    notification_email {
        aisuhua@example.com
        itsection@example.com
    }
    notification_email_from itsection@example.com
    smtp_server mail.example.com
    smtp_connect_timeout 30
    router_id LVS_lb2
}

vrrp_script chk_nginx {
    script "killall -0 nginx"
    interval 2
    weight -5
    fall 3
    rise 2
}

vrrp_instance VI_1 {
    state BACKUP
    interface enp0s3
    mcast_src_ip 192.168.31.221
    virtual_router_id 51
    priority 100
    advert_int 2
    authentication {
        auth_type PASS
        auth_pass 1111
    }
    virtual_ipaddress {
       192.168.31.20
    }
    track_script {
       chk_nginx
    }
}

vrrp_instance VI_2 {
    state MASTER
    interface enp0s3
    mcast_src_ip 192.168.31.221
    virtual_router_id 151
    priority 101
    advert_int 2
    authentication {
        auth_type PASS
        auth_pass 2222
    }
    virtual_ipaddress {
       192.168.31.30
    }
    track_script {
       chk_nginx
    }
}
```

## 操作

重新加载配置文件

```sh
shell> nginx -s reload
```

查看编辑参数

```sh
shell> nginx -V
```

## 参考文献

- [Install](https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)
- [Welcome to NGINX Wiki!](https://www.nginx.com/resources/wiki/)
- [如何正确配置 Nginx 和 PHP](http://blog.jobbole.com/50121/)
- [Using nginx as HTTP load balancer](http://nginx.org/en/docs/http/load_balancing.html)
- [Module ngx_http_upstream_module](http://nginx.org/en/docs/http/ngx_http_upstream_module.html)
- [TCP/UDP Load Balancing with NGINX: Overview, Tips, and Tricks](https://www.nginx.com/blog/tcp-load-balancing-udp-load-balancing-nginx-tips-tricks/#filter)
- [TCP and UDP Load Balancing](https://docs.nginx.com/nginx/admin-guide/load-balancer/tcp-udp-load-balancer/)
- [nginx负载均衡的5种策略](https://segmentfault.com/a/1190000014483200)
- [Nginx负载均衡配置](https://blog.csdn.net/xyang81/article/details/51702900)
- [nginx负载均衡](https://thief.one/2017/08/22/1/)
