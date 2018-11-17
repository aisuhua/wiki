## 安装

安装

```sh
shell> apt-get install keepalived
```

添加配置

```sh
shell> vim /etc/keepalived/keepalived.conf
! Configuration File for keepalived
global_defs {
    notification_email {
        zhouxiao@example.com
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

重启服务

```sh
shell> service keepalived restart
```

## 操作

抓取 VRRP 包

```sh
shell> tcpdump vrrp
```

> 为减少网络带宽消耗，只有主控路由器才可以周期性地发送 VRRP 通告报文。
若备份路由器在连续三个通告间隔内收不到 VRRP 或收到优先级为 0 的通告则启动新一轮的 VRRP 选举
—— 摘抄自《Linux 集群和自动化运维》

查看系统日志

```sh
shell> tail -f /var/log/syslog 
```

## 参考文献

- [How can I (from CLI) assign multiple IP addresses to one interface?](https://askubuntu.com/questions/547289/how-can-i-from-cli-assign-multiple-ip-addresses-to-one-interface)
- [Nginx+Keepalived实现站点高可用](http://seanlook.com/2015/05/18/nginx-keepalived-ha/) ([转载1](https://linux.cn/article-5715-1.html))
- [keepalived + nginx 初步实现高可用](https://klionsec.github.io/2017/12/23/keepalived-nginx/)
- [Keepalived+Nginx实现双主高可用负载均衡](http://blog.51cto.com/zhangpenglinux/1782759)
- [告别LVS：使用keepalived+nginx实现负载均衡代理多个https](http://www.ha97.com/899.html)
