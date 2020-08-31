## 常见问题

### 如何允许非 root 进程绑定低位端口

允许特定程序绑定低位端口

```
setcap CAP_NET_BIND_SERVICE=+eip /path/to/binary
```

取消权限

```
setcap -r /path/to/binary
```

- [你有普通用户使用特权端口 (1024 以下) 的需求吗](https://cloud.tencent.com/developer/article/1526429)
- [如何允许非 root 进程绑定低位端口](https://www.boris1993.com/linux/allow-non-root-process-to-bind-low-numbered-ports.html)

