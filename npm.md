安装

```
apt-get install npm
```

设置使用国内源

```
npm config set registry https://registry.npm.taobao.org
npm config get registry
```

安装 cnpm 

```
npm install -g cnpm --registry=https://registry.npm.taobao.org
```

- [淘宝npm镜像使用方法](https://blog.csdn.net/quuqu/article/details/64121812)

升级 npm

```
npm install -g npm
```

- [淘宝 NPM 镜像](https://developer.aliyun.com/mirror/NPM)
- [中科大 NPM 镜像](https://lug.ustc.edu.cn/wiki/mirrors/help/npm)

安装第三方模块

```
cnpm install [name]
```

升级 nodejs

```
sudo npm install -g n
sudo n latest
sudo n 8.9.0
```

- [updating nodejs on ubuntu 16.04](https://stackoverflow.com/questions/41195952/updating-nodejs-on-ubuntu-16-04)

## 常见问题

Error: ENOSPC: System limit for number of file watchers reached

```
echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf && sudo sysctl -p
cat /proc/sys/fs/inotify/max_user_watches
```

 - [React Native Error: ENOSPC: System limit for number of file watchers reached](https://stackoverflow.com/questions/55763428/react-native-error-enospc-system-limit-for-number-of-file-watchers-reached)
