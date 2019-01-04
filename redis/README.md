## Script

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
SLAVEOF 192.168.1.10 6379
```

查看复制状态

```sh
info replication
```

取消复制恢复为主数据库

```sh
SLAVEOF NO ONE
```

## Troubleshoot

```sh
shell> vim /etc/sysctl.conf
net.core.somaxconn=65535
vm.overcommit_memory=1
shell> vim /etc/rc.local
echo never > /sys/kernel/mm/transparent_hugepage/enabled
```

- [WARNING: /proc/sys/net/core/somaxconn is set to the lower value of 128.](https://github.com/docker-library/redis/issues/35)
