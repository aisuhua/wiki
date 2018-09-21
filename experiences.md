```text
1. RPC 调用失败重试机制
若 RPC 接口调用失败，可将回调内容推送到 RabbitMQ 进行重试。

2. 防止 worker 并发或资源独占
将 worker 按用户ID进行分组，特别用户使用一个固定队列即可。 
```