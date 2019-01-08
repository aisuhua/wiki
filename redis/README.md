## 安装

安装

```sh
wget http://download.redis.io/releases/redis-5.0.3.tar.gz
tar -zxvf redis-5.0.3.tar.gz
cd redis-5.0.3
make
```

启动服务

```sh
src/redis-server redis.conf
```

与服务器交互

```sh
src/redis-cli
```

- [Installation](https://redis.io/download#installation)

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

## 安全

添加连接密码

```sh
shell> vim redis.conf
requirepass 123456
```

使用密码登录

```sh
auth 123456
```

配置复制时，若主库有密码则需要提供密码

```conf
masterauth <master-password>
```

## 监控

记录慢日志

```sh
shell> vim redis.conf
slowlog-log-slower-than 10000
```

查看慢日志

```sh
127.0.0.1:6379> slowlog get
```

监控并查看所有执行语句，此命令在生产环境下慎用

```sh
127.0.0.1:6379> monitor
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
shell> mkdir -p /www/redis/6380
shell> cp redis.conf /www/redis/6380/redis.conf
shell> vim 6380/redis.conf
port 6380
pidfile "/var/run/redis_6380.pid"
dir "/www/redis/6380"
shell> cd /www/redis/6380
shell> ../redis-server redis.conf
```

