## 分布式 ID 的生成方式

- 使用数据库的 auto_increment 来生成全局唯一递增ID；
- 批量 ID 生成服务；
- UUID/GUID；
- 取当前毫秒数（微秒数）；
- Twitter Snowflake；
- [Flickr](http://code.flickr.net/2010/02/08/ticket-servers-distributed-unique-primary-keys-on-the-cheap/)；
- [Instagram](https://instagram-engineering.com/sharding-ids-at-instagram-1cf5a71e5a5c)。
- 还有其他一些方案，比如京东淘宝等电商的订单号生成。

因为订单号和用户id在业务上的区别，订单号尽可能要多些冗余的业务信息，比如：

- 滴滴：时间+起点编号+车牌号
- 淘宝订单：时间戳+用户ID
- 其他电商：时间戳+下单渠道+用户ID，有的会加上订单第一个商品的ID。

## 参考文献

- [分布式ID生成器](https://mp.weixin.qq.com/s?__biz=MjM5ODYxMDA5OQ==&mid=2651960245&idx=1&sn=5cef3d8ca6a3e6e94f61e0edaf985d11)
- [分布式系统中唯一 ID 的生成方法](http://einverne.github.io/post/2017/11/distributed-system-generate-unique-id.html)
- [分布式架构系统生成全局唯一序列号的一个思路](https://mp.weixin.qq.com/s/F7WTNeC3OUr76sZARtqRjw)
