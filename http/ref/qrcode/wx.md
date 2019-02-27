微信二维码扫码登录流程分析


用户打开登录页面，页面使用 ajax 请求接口返回一个 uuid
curl 'https://login.wx2.qq.com/jslogin?appid=wx782c26e4c19acffb&redirect_uri=https%3A%2F%2Fwx2.qq.com%2Fcgi-bin%2Fmmwebwx-bin%2Fwebwxnewloginpage&fun=new&lang=zh_CN&_=1551166567409' -H 'Pragma: no-cache' -H 'Accept-Encoding: gzip, deflate, sdch, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Accept: */*' -H 'Referer: https://wx2.qq.com/' -H 'Cookie: mm_lang=zh_CN' -H 'Connection: keep-alive' -H 'Cache-Control: no-cache' --compressed

window.QRLogin.code = 200; window.QRLogin.uuid = "Qe3PDEPf1Q==";


qrcode 根据 uuid 生成一张二维码图片（二维码的内容实际上就是下面的 url）
https://login.weixin.qq.com/qrcode/Qe3PDEPf1Q==


页面不停轮询请求登录状态接口，确认用户是否已经扫描二维码或者登录
curl 'https://login.wx.qq.com/cgi-bin/mmwebwx-bin/login?loginicon=true&uuid=QYRItUzjZQ==&tip=0&r=-687135983&_=1551170229227' -H 'Pragma: no-cache' -H 'Accept-Encoding: gzip, deflate, sdch, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Accept: */*' -H 'Referer: https://wx.qq.com/' -H 'Cookie: mm_lang=zh_CN' -H 'Connection: keep-alive' -H 'Cache-Control: no-cache' --compressed

window.code=408;


手机扫描后，接口返回扫描该二维码的用户头像给页面
curl 'https://login.wx2.qq.com/cgi-bin/mmwebwx-bin/login?loginicon=true&uuid=Qe3PDEPf1Q==&tip=1&r=-683373891&_=1551166567410' -H 'Pragma: no-cache' -H 'Accept-Encoding: gzip, deflate, sdch, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Accept: */*' -H 'Referer: https://wx2.qq.com/' -H 'Cookie: mm_lang=zh_CN' -H 'Connection: keep-alive' -H 'Cache-Control: no-cache' --compressed

window.code=201;window.userAvatar = 'data:img/jpg;base64,/9j/4A...';


手机点击确认登录，此时用户和uuid（二维码）已经完成绑定，接口返回一个 ticket 表示登录成功
curl 'https://login.wx2.qq.com/cgi-bin/mmwebwx-bin/login?loginicon=true&uuid=Qe3PDEPf1Q==&tip=0&r=-683386774&_=1551166567411' -H 'Pragma: no-cache' -H 'Accept-Encoding: gzip, deflate, sdch, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Accept: */*' -H 'Referer: https://wx2.qq.com/' -H 'Cookie: mm_lang=zh_CN' -H 'Connection: keep-alive' -H 'Cache-Control: no-cache' --compressed

window.code=200;
window.redirect_uri="https://wx2.qq.com/cgi-bin/mmwebwx-bin/webwxnewloginpage?ticket=AyGitdJAff6sUYys3kvhlm6-@qrticket_0&uuid=Qe3PDEPf1Q==&lang=zh_CN&scan=1551166580";


页面使用该 ticket 完成登录，生成保持登录状态所需的 Cookie 信息。
curl 'https://wx2.qq.com/cgi-bin/mmwebwx-bin/webwxnewloginpage?ticket=AyGitdJAff6sUYys3kvhlm6-@qrticket_0&uuid=Qe3PDEPf1Q==&lang=zh_CN&scan=1551166580&fun=new&version=v2' -H 'Pragma: no-cache' -H 'Accept-Encoding: gzip, deflate, sdch, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Accept: application/json, text/plain, */*' -H 'Referer: https://wx2.qq.com/' -H 'Cookie: mm_lang=zh_CN; MM_WX_NOTIFY_STATE=1; MM_WX_SOUND_STATE=1' -H 'Connection: keep-alive' -H 'Cache-Control: no-cache' --compressed

HTTP/1.1 200 OK
Connection: keep-alive
Content-Type: text/plain;charset=utf-8
Set-Cookie: wxuin=2663483803; Domain=wx2.qq.com; Path=/; Expires=Tue, 26-Feb-2019 19:36:33 GMT; Secure
Set-Cookie: wxsid=GLRECysse3NhRQe2; Domain=wx2.qq.com; Path=/; Expires=Tue, 26-Feb-2019 19:36:33 GMT; Secure
Set-Cookie: wxloadtime=1551166593; Domain=wx2.qq.com; Path=/; Expires=Tue, 26-Feb-2019 19:36:33 GMT; Secure
Set-Cookie: mm_lang=zh_CN; Domain=wx2.qq.com; Path=/; Expires=Tue, 26-Feb-2019 19:36:33 GMT; Secure
Set-Cookie: webwx_data_ticket=gSeQqWLSMdWtXWd5shc7+gG9; Domain=.qq.com; Path=/; Expires=Tue, 26-Feb-2019 19:36:33 GMT; Secure
Set-Cookie: webwxuvid=900475e82e60d3d7dd2f4ddfb827abf129ad28e69d0210609875d885ad79de1ec37d7e99282234116f472e138e3ad00f; Domain=wx2.qq.com; Path=/; Expires=Fri, 23-Feb-2029 07:36:33 GMT; Secure
Set-Cookie: webwx_auth_ticket=CIsBEPuS+64MGoABTUJaN1gybFXvZNhlc6st/1GLdDpemdDDtvPimK8raUtLk3jjq7Y8Yr3aAelBWI5zdveeuU0RioZw5FMxdn5sdH8swjGY3r1tkCRNtjWSgvj853BXXe41pKdQ9OSfVTc/WeT5yE/8EUBRmip4qxGaFy9ouX8OSzIzue96Xpy/BmY=; Domain=wx2.qq.com; Path=/; Expires=Fri, 23-Feb-2029 07:36:33 GMT; Secure
Strict-Transport-Security: max-age=31536000
Content-Encoding: gzip
Content-Length: 236


<error><ret>0</ret><message></message><skey>@crypt_ae446460_e9e8807e9992e98fb587194ac764792f</skey><wxsid>GLRECysse3NhRQe2</wxsid><wxuin>2663483803</wxuin><pass_ticket>NuMItNqHBs1eIid1K1YK5cJ7Q2K7VmQ0wuFkv%2FNgDfLKwEwdX4mETYoskIJutT5d</pass_ticket><isgrayscale>1</isgrayscale></error>


登录完成后，获取用户数据
curl 'https://wx2.qq.com/cgi-bin/mmwebwx-bin/webwxinit?r=-683373613&pass_ticket=NuMItNqHBs1eIid1K1YK5cJ7Q2K7VmQ0wuFkv%252FNgDfLKwEwdX4mETYoskIJutT5d' -H 'Pragma: no-cache' -H 'Origin: https://wx2.qq.com' -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Content-Type: application/json;charset=UTF-8' -H 'Accept: application/json, text/plain, */*' -H 'Cache-Control: no-cache' -H 'Referer: https://wx2.qq.com/' -H 'Cookie: MM_WX_NOTIFY_STATE=1; MM_WX_SOUND_STATE=1; wxuin=2663483803; wxsid=GLRECysse3NhRQe2; wxloadtime=1551166593; mm_lang=zh_CN; webwx_data_ticket=gSeQqWLSMdWtXWd5shc7+gG9; webwxuvid=900475e82e60d3d7dd2f4ddfb827abf129ad28e69d0210609875d885ad79de1ec37d7e99282234116f472e138e3ad00f; webwx_auth_ticket=CIsBEPuS+64MGoABTUJaN1gybFXvZNhlc6st/1GLdDpemdDDtvPimK8raUtLk3jjq7Y8Yr3aAelBWI5zdveeuU0RioZw5FMxdn5sdH8swjGY3r1tkCRNtjWSgvj853BXXe41pKdQ9OSfVTc/WeT5yE/8EUBRmip4qxGaFy9ouX8OSzIzue96Xpy/BmY=; login_frequency=1; last_wxuin=2663483803' -H 'Connection: keep-alive' --data-binary '{"BaseRequest":{"Uin":"2663483803","Sid":"GLRECysse3NhRQe2","Skey":"@crypt_ae446460_e9e8807e9992e98fb587194ac764792f","DeviceID":"e138413478611227"}}' --compressed


POST /cgi-bin/mmwebwx-bin/webwxinit?r=-683373613&pass_ticket=NuMItNqHBs1eIid1K1YK5cJ7Q2K7VmQ0wuFkv%252FNgDfLKwEwdX4mETYoskIJutT5d HTTP/1.1
Host: wx2.qq.com
Connection: keep-alive
Content-Length: 149
Pragma: no-cache
Cache-Control: no-cache
Accept: application/json, text/plain, */*
Origin: https://wx2.qq.com
User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36
Content-Type: application/json;charset=UTF-8
Referer: https://wx2.qq.com/
Accept-Encoding: gzip, deflate, br
Accept-Language: zh-CN,zh;q=0.8,en;q=0.6
Cookie: MM_WX_NOTIFY_STATE=1; MM_WX_SOUND_STATE=1; wxuin=2663483803; wxsid=GLRECysse3NhRQe2; wxloadtime=1551166593; mm_lang=zh_CN; webwx_data_ticket=gSeQqWLSMdWtXWd5shc7+gG9; webwxuvid=900475e82e60d3d7dd2f4ddfb827abf129ad28e69d0210609875d885ad79de1ec37d7e99282234116f472e138e3ad00f; webwx_auth_ticket=CIsBEPuS+64MGoABTUJaN1gybFXvZNhlc6st/1GLdDpemdDDtvPimK8raUtLk3jjq7Y8Yr3aAelBWI5zdveeuU0RioZw5FMxdn5sdH8swjGY3r1tkCRNtjWSgvj853BXXe41pKdQ9OSfVTc/WeT5yE/8EUBRmip4qxGaFy9ouX8OSzIzue96Xpy/BmY=; login_frequency=1; last_wxuin=2663483803


如何长时间没有扫该二维码，接口会提示该 uuid 已经失败，此时页面停止检测登录状态并提示：二维码失效，点击刷新
curl 'https://login.wx.qq.com/cgi-bin/mmwebwx-bin/login?loginicon=true&uuid=QYRItUzjZQ==&tip=0&r=-687339686&_=1551170229235' -H 'Pragma: no-cache' -H 'Accept-Encoding: gzip, deflate, sdch, br' -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36' -H 'Accept: */*' -H 'Referer: https://wx.qq.com/' -H 'Cookie: mm_lang=zh_CN' -H 'Connection: keep-alive' -H 'Cache-Control: no-cache' --compressed

window.code=400;
