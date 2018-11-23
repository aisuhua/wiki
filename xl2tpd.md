安装

```sh
shell> apt-get install xl2tpd
```

/etc/xl2tpd/xl2tpd.conf

```sh
shell> vim /etc/xl2tpd/xl2tpd.conf
[global]
access control = no
auth file = /etc/ppp/chap-secrets
debug avp = no
debug network = no
debug packet = no
debug state = no
debug tunnel = no
[lac demo]
lns = 119.145.100.200
redial = yes
redial timeout = 5
require chap = yes
require authentication = no
ppp debug = no
pppoptfile = /etc/ppp/peers/options.l2tpd.client.demo
require pap = no
autodial = yes
```

/etc/ppp/peers/options.l2tpd.client.demo

```sh
shell> vim /etc/ppp/peers/options.l2tpd.client.demo
noipdefault
ipcp-accept-local
ipcp-accept-remote
refuse-eap
require-mschap-v2
noccp
noauth
idle 1800
mtu 1410
mru 1410
defaultroute
usepeerdns
debug
connect-delay 5000
name MYNAME
password MYPASSWORD
logfile /var/log/l2tpd.client.demo.log
ipparam route:10.10.0.0/16,10.11.0.0/16,10.21.0.0/16,10.22.0.0/16,10.220.0.0/16,192.168.1.0/24
```

/etc/ppp/ip-up.d/01-ipparam.sh

```sh
shell> vim /etc/ppp/ip-up.d/01-ipparam.sh
#!/bin/sh
  
if [ -n "${5}" -a -n "${6}" ]; then
   interface=${1}
   remoteip=${5}
   ipparams=(${6//;/ })
   for ipparam in ${ipparams[@]} ; do
       kv=(${ipparam//:/ })
       case ${kv[0]} in
           route)
               values=(${kv[1]//,/ })
               for value in ${values[@]} ; do
                   if [ `ip route | grep -c "${value}"` -eq 0 ]; then
                       ip route add ${value} via ${remoteip} dev ${interface} proto static
                   fi
               done
               ;;
           *)
               ;;
       esac
   done
fi
```

启动服务

```sh
shell> systemctl start xl2tpd
```
