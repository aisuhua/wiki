## 负载均衡高可用

服务器规划

| 服务器名称   | IP             | 用途            |
| ------------ | -------------- | --------------- |
| Nginx Master | 192.168.31.220 | 提供负载均衡    |
| Nginx Backup | 192.168.31.221 | 提供负载均衡    |
| VIP   | 192.168.31.20  | 集群的虚拟IP地址 |
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

## 双主负载均衡高可用

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

- [Keepalived+Nginx实现双主高可用负载均衡](http://blog.51cto.com/zhangpenglinux/1782759)
