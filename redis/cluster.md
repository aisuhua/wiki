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

### 重新分配插槽

将 0 号插槽从 7000 迁移到 7001 节点，里面的数据也会转存过去。

```sh
shell> ./redis-cli --cluster reshard 127.0.0.1:7000
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

### 加入新节点

将节点 7006 加入集群，新加入的节点默认为主节点，但是它没有分配插槽，所以它还不能存储数据。

```sh
redis-cli --cluster add-node 127.0.0.1:7006 127.0.0.1:7000
```

添加完成后，可以使用上一节的方法给该该节点分配插槽。

### 加入副本节点

若没有指定所属的主节点，默认会随机选择一个节点作为其主节点。

```sh
redis-cli --cluster add-node 127.0.0.1:7006 127.0.0.1:7000 --cluster-slave
```

指定主节点

```sh
redis-cli --cluster add-node 127.0.0.1:7006 127.0.0.1:7000 --cluster-slave --cluster-master-id 3c3a0c74aae0b56170ccb03a76b60cfe7dc1912e
```

### 从集群中剔除节点

```sh
redis-cli --cluster del-node 127.0.0.1:7000 `<node-id>`
```

### 副本节点切换

在集群运行期间，可以将副本节点的主节点切换成其他节点

```sh
CLUSTER REPLICATE <master-node-id>
```

## 故障恢复

在集群中，当一个主数据库下线时，就会出现一部分插槽无法写入的问题。
此时，如果该主数据库拥有至少一个从数据库，集群就会进行故障恢复操作来将其中一个从数据库转变成主数据库来保证集群的完整。
选择哪一个从数据库来作为主数据库的过程和在哨兵中选择领头哨兵一样，都是基于 Raft 算法。

- [Redis入门指南（第2版）](https://book.douban.com/subject/26419240/)
