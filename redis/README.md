## Lua scripting

每个脚本都是原子性的。

### 限制访问频率

添加限制访问频率的 lua 脚本

```lua
-- scripts/ratelimiting.lua
local times = redis.call('incr', KEYS[1])

if times == 1 then
    redis.call('expire', KEYS[1], ARGV[1])
end

if times > tonumber(ARGV[2]) then
    return 0
end

return 1
```

限制访问频率为 10 秒钟最多 3 次

```sh
src/redis-cli --eval scripts/ratelimiting.lua rate.limiting:127.0.0.1 , 10 3
```

### 获取多个散列键的值

添加获取多个散列键值的 lua 脚本

```lua
-- scripts/hmgetall.lua
local result = {}

for i, v in ipairs(KEYS) do
    result[i] = redis.call('hgetall', v)
end

return result
```

同时获取 `posts:1`、`posts:2`、`posts:3` 的散列值

```sh
src/redis-cli --eval scripts/hmgetall.lua posts:1 posts:2 posts:3
```

## Replication

只需在从库上配置好主库地址和端口即可

```sh
shell> vim redis.conf
# replicaof <masterip> <masterport>
replicaof 192.168.1.10 6379
````

运行时动态配置复制

```sh
REPLICAOF 192.168.1.10 6379
```

查看复制状态

```sh
info replication
```

取消复制恢复为主数据库

```sh
REPLICAOF NO ONE
```

复制原理展示

```sh
shell> telnet 192.168.1.10 6379
ping
+PONG
REPLCONF listening-port 6380
+OK
sync
$193
REDIS0009�	redis-ver5.0.3....
```

## Sentinel 

配置哨兵监控的主数据库信息

```sh
shell> vim sentinel.conf
# sentinel monitor <master-name> <ip> <redis-port> <quorum>
sentinel monitor mymaster 192.168.1.10 6379 2
```

提示

- 哨兵是通过主库找到从库，然后对所有从库也进行监控；
- 主库和从库都可配置哨兵，这种方式会更加稳妥；
- 主库若宕机，哨兵就会选出一个从库作为主库，并将其他从库的主库修改为该主库；
- quorum 表示执行故障恢复操作前至少需要几个哨兵节点同意。

## Cluster

开启集群功能

```sh
shell> vim /www/redis/7000/redis.conf 
port 7000
pidfile "/var/run/redis_7000.pid"
dir "/www/redis/7000"
cluster-enabled yes
cluster-config-file "nodes-7000.conf"
```

创建集群

```sh
shell> ./redis-cli --cluster create \
    127.0.0.1:7000 \
    127.0.0.1:7001 \
    127.0.0.1:7002 \
    127.0.0.1:7003 \
    127.0.0.1:7004 \
    127.0.0.1:7005 \
    --cluster-replicas 1
>>> Performing hash slots allocation on 6 nodes...
Master[0] -> Slots 0 - 5460
Master[1] -> Slots 5461 - 10922
Master[2] -> Slots 10923 - 16383
Adding replica 127.0.0.1:7003 to 127.0.0.1:7000
Adding replica 127.0.0.1:7004 to 127.0.0.1:7001
Adding replica 127.0.0.1:7005 to 127.0.0.1:7002
>>> Trying to optimize slaves allocation for anti-affinity
[WARNING] Some slaves are in the same host as their master
M: 72b1e33fdeed54698e5f68066a8239f9557f871b 127.0.0.1:7000
   slots:[0-5460] (5461 slots) master
M: 6a440cd87cb2403ed7d85e5c301deb1e6004030f 127.0.0.1:7001
   slots:[5461-10922] (5462 slots) master
M: 9dd01ffe2e5b01762bc54ffab637694ad288aa30 127.0.0.1:7002
   slots:[10923-16383] (5461 slots) master
S: 38d8d8bb3271db1e741714748bf85d49c2a944ca 127.0.0.1:7003
   replicates 72b1e33fdeed54698e5f68066a8239f9557f871b
S: 40a10edc25695477a89456bf4530192a2c71d00c 127.0.0.1:7004
   replicates 6a440cd87cb2403ed7d85e5c301deb1e6004030f
S: 138449d1ac998af1945e6e602b83bf3a43b9fb33 127.0.0.1:7005
   replicates 9dd01ffe2e5b01762bc54ffab637694ad288aa30
Can I set the above configuration? (type 'yes' to accept): yes
>>> Nodes configuration updated
>>> Assign a different config epoch to each node
>>> Sending CLUSTER MEET messages to join the cluster
Waiting for the cluster to join
.......
>>> Performing Cluster Check (using node 127.0.0.1:7000)
M: 72b1e33fdeed54698e5f68066a8239f9557f871b 127.0.0.1:7000
   slots:[0-5460] (5461 slots) master
   1 additional replica(s)
M: 6a440cd87cb2403ed7d85e5c301deb1e6004030f 127.0.0.1:7001
   slots:[5461-10922] (5462 slots) master
   1 additional replica(s)
S: 138449d1ac998af1945e6e602b83bf3a43b9fb33 127.0.0.1:7005
   slots: (0 slots) slave
   replicates 9dd01ffe2e5b01762bc54ffab637694ad288aa30
S: 40a10edc25695477a89456bf4530192a2c71d00c 127.0.0.1:7004
   slots: (0 slots) slave
   replicates 6a440cd87cb2403ed7d85e5c301deb1e6004030f
M: 9dd01ffe2e5b01762bc54ffab637694ad288aa30 127.0.0.1:7002
   slots:[10923-16383] (5461 slots) master
   1 additional replica(s)
S: 38d8d8bb3271db1e741714748bf85d49c2a944ca 127.0.0.1:7003
   slots: (0 slots) slave
   replicates 72b1e33fdeed54698e5f68066a8239f9557f871b
[OK] All nodes agree about slots configuration.
>>> Check for open slots...
>>> Check slots coverage...
[OK] All 16384 slots covered.
```

查看集群状态

```sh
127.0.0.1:7000> cluster nodes
6a440cd87cb2403ed7d85e5c301deb1e6004030f 127.0.0.1:7001@17001 master - 0 1546928620333 2 connected 5461-10922
138449d1ac998af1945e6e602b83bf3a43b9fb33 127.0.0.1:7005@17005 slave 9dd01ffe2e5b01762bc54ffab637694ad288aa30 0 1546928618327 6 connected
40a10edc25695477a89456bf4530192a2c71d00c 127.0.0.1:7004@17004 slave 6a440cd87cb2403ed7d85e5c301deb1e6004030f 0 1546928617324 5 connected
9dd01ffe2e5b01762bc54ffab637694ad288aa30 127.0.0.1:7002@17002 master - 0 1546928619331 3 connected 10923-16383
38d8d8bb3271db1e741714748bf85d49c2a944ca 127.0.0.1:7003@17003 slave 72b1e33fdeed54698e5f68066a8239f9557f871b 0 1546928618000 4 connected
72b1e33fdeed54698e5f68066a8239f9557f871b 127.0.0.1:7000@17000 myself,master - 0 1546928616000 1 connected 0-5460
```

查看插槽的分配情况

```sh
127.0.0.1:7000> cluster slots
1) 1) (integer) 5461
   2) (integer) 10922
   3) 1) "127.0.0.1"
      2) (integer) 7001
      3) "6a440cd87cb2403ed7d85e5c301deb1e6004030f"
   4) 1) "127.0.0.1"
      2) (integer) 7004
      3) "40a10edc25695477a89456bf4530192a2c71d00c"
2) 1) (integer) 10923
   2) (integer) 16383
   3) 1) "127.0.0.1"
      2) (integer) 7002
      3) "9dd01ffe2e5b01762bc54ffab637694ad288aa30"
   4) 1) "127.0.0.1"
      2) (integer) 7005
      3) "138449d1ac998af1945e6e602b83bf3a43b9fb33"
3) 1) (integer) 0
   2) (integer) 5460
   3) 1) "127.0.0.1"
      2) (integer) 7000
      3) "72b1e33fdeed54698e5f68066a8239f9557f871b"
   4) 1) "127.0.0.1"
      2) (integer) 7003
      3) "38d8d8bb3271db1e741714748bf85d49c2a944ca"
```

- [Redis cluster tutorial](https://redis.io/topics/cluster-tutorial)

启动集群模式下的自动重定向

```sh
shell> ./redis-cli -c -p 7000
127.0.0.1:7000> set name suhua
-> Redirected to slot [5798] located at 127.0.0.1:7001
OK
```

## 重新分配插槽

将 0 号插槽从 7000 迁移到 7001 节点，里面的数据也会转存过去。

```sh
./redis-cli --cluster reshard 127.0.0.1:7000
>>> Performing Cluster Check (using node 127.0.0.1:7000)
M: 72b1e33fdeed54698e5f68066a8239f9557f871b 127.0.0.1:7000
   slots:[0-5460] (5461 slots) master
   1 additional replica(s)
M: 6a440cd87cb2403ed7d85e5c301deb1e6004030f 127.0.0.1:7001
   slots:[5461-10922] (5462 slots) master
   1 additional replica(s)
S: 138449d1ac998af1945e6e602b83bf3a43b9fb33 127.0.0.1:7005
   slots: (0 slots) slave
   replicates 9dd01ffe2e5b01762bc54ffab637694ad288aa30
S: 40a10edc25695477a89456bf4530192a2c71d00c 127.0.0.1:7004
   slots: (0 slots) slave
   replicates 6a440cd87cb2403ed7d85e5c301deb1e6004030f
M: 9dd01ffe2e5b01762bc54ffab637694ad288aa30 127.0.0.1:7002
   slots:[10923-16383] (5461 slots) master
   1 additional replica(s)
S: 38d8d8bb3271db1e741714748bf85d49c2a944ca 127.0.0.1:7003
   slots: (0 slots) slave
   replicates 72b1e33fdeed54698e5f68066a8239f9557f871b
[OK] All nodes agree about slots configuration.
>>> Check for open slots...
>>> Check slots coverage...
[OK] All 16384 slots covered.
How many slots do you want to move (from 1 to 16384)? 1
What is the receiving node ID? 6a440cd87cb2403ed7d85e5c301deb1e6004030f
Please enter all the source node IDs.
  Type 'all' to use all the nodes as source nodes for the hash slots.
  Type 'done' once you entered all the source nodes IDs.
Source node #1: 72b1e33fdeed54698e5f68066a8239f9557f871b
Source node #2: done

Ready to move 1 slots.
  Source nodes:
    M: 72b1e33fdeed54698e5f68066a8239f9557f871b 127.0.0.1:7000
       slots:[0-5460] (5461 slots) master
       1 additional replica(s)
  Destination node:
    M: 6a440cd87cb2403ed7d85e5c301deb1e6004030f 127.0.0.1:7001
       slots:[5461-10922] (5462 slots) master
       1 additional replica(s)
  Resharding plan:
    Moving slot 0 from 72b1e33fdeed54698e5f68066a8239f9557f871b
Do you want to proceed with the proposed reshard plan (yes/no)? yes
Moving slot 0 from 127.0.0.1:7000 to 127.0.0.1:7001:
```

查看插槽迁移后的分配信息

```sh
127.0.0.1:7000> cluster slots
1) 1) (integer) 0
   2) (integer) 0
   3) 1) "127.0.0.1"
      2) (integer) 7001
      3) "6a440cd87cb2403ed7d85e5c301deb1e6004030f"
   4) 1) "127.0.0.1"
      2) (integer) 7004
      3) "40a10edc25695477a89456bf4530192a2c71d00c"
2) 1) (integer) 5461
   2) (integer) 10922
   3) 1) "127.0.0.1"
      2) (integer) 7001
      3) "6a440cd87cb2403ed7d85e5c301deb1e6004030f"
   4) 1) "127.0.0.1"
      2) (integer) 7004
      3) "40a10edc25695477a89456bf4530192a2c71d00c"
3) 1) (integer) 10923
   2) (integer) 16383
   3) 1) "127.0.0.1"
      2) (integer) 7002
      3) "9dd01ffe2e5b01762bc54ffab637694ad288aa30"
   4) 1) "127.0.0.1"
      2) (integer) 7005
      3) "138449d1ac998af1945e6e602b83bf3a43b9fb33"
4) 1) (integer) 1
   2) (integer) 5460
   3) 1) "127.0.0.1"
      2) (integer) 7000
      3) "72b1e33fdeed54698e5f68066a8239f9557f871b"
   4) 1) "127.0.0.1"
      2) (integer) 7003
      3) "38d8d8bb3271db1e741714748bf85d49c2a944ca"
```

## Troubleshoot

优化系统参数

```sh
shell> vim /etc/sysctl.conf
net.core.somaxconn=65535
vm.overcommit_memory=1
shell> vim /etc/rc.local
echo never > /sys/kernel/mm/transparent_hugepage/enabled
```

- [WARNING: /proc/sys/net/core/somaxconn is set to the lower value of 128.](https://github.com/docker-library/redis/issues/35)

一台机器启动多个 Redis 实例

```sh
shell> cp redis.conf redis_6380.conf
shell> cp redis.conf redis_6381.conf
shell> vim redis_6380.conf
port 6380
pidfile "/var/run/redis_6380.pid"
dir "/www/redis/6380"
shell> mkdir -p /www/redis/6380
shell> ./redis-server redis_6380.conf
```

