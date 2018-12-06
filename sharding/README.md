## 分布式 ID 的生成方式

- 使用数据库的 auto_increment 来生成全局唯一递增ID；
- 批量 ID 生成服务；
- UUID/GUID；
- 取当前毫秒数（微秒数）；
- Twitter Snowflake；
- [Flickr](http://code.flickr.net/2010/02/08/ticket-servers-distributed-unique-primary-keys-on-the-cheap/)；
- [Instagram](https://instagram-engineering.com/sharding-ids-at-instagram-1cf5a71e5a5c)。

以下文章对此做了总结：

- [分布式ID生成器](https://mp.weixin.qq.com/s?__biz=MjM5ODYxMDA5OQ==&mid=2651960245&idx=1&sn=5cef3d8ca6a3e6e94f61e0edaf985d11)
- [分布式系统中唯一 ID 的生成方法](http://einverne.github.io/post/2017/11/distributed-system-generate-unique-id.html)
