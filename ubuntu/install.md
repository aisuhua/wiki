Setting sudo without password

```
vim /etc/sudoers
suhua ALL=(ALL) NOPASSWD: ALL
```

Install Shadowsocks

- https://github.com/shadowsocks/shadowsocks-qt5

Install Chrome

```
export http_proxy="socks5://127.0.0.1:1082"
export http_proxys="socks5://127.0.0.1:1082"

wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
dpkg -i google-chrome-stable_current_amd64.deb
apt-get install
```

Install SwitchyOmega

- https://chrome.google.com/webstore/detail/padekgcemlokbadohgkifijomclgjgif

Install Sogou Pinyin

```
dpkg -i sogoupinyin_2.3.1.0112_amd64.deb
apt install -f
```

- https://pinyin.sogou.com/linux/
- https://blog.csdn.net/lupengCSDN/article/details/80279177

Install baidu pan

- http://pan.baidu.com/download

Install git

```
git config --global user.name "aisuhua"
git config --global user.email 1079087531@qq.com
```

- https://github.com/aisuhua/wiki/blob/master/git.md

Install Docker

- https://github.com/aisuhua/wiki/blob/master/docker.md
- https://blog.csdn.net/ncdx111/article/details/79878098

Install Phpstorm

- https://gist.github.com/invinciblycool/ecc1c6e32b581b68932ac7452f4c911c

Install Postman

```
sudo snap install postman
```

- https://gist.github.com/invinciblycool/ecc1c6e32b581b68932ac7452f4c911c

Other

```
7z a gx.7z gx
```


