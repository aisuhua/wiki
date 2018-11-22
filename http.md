## HTTP Basic authentication

URL

```sh
shell> curl https://username:password@www.example.com
```

Authorization

```sh
shell> curl -H 'Authorization: Basic ZWx2c33pYzoxMTUjM23Q29t' https://www.example.com
```

> ZWxhc3RpYzoxMTUjMTE1Q29t = base64(username:password)

- [[译]web权限验证方法说明](https://segmentfault.com/a/1190000004086946)

# Cross-Origin Resource Sharing (CORS)

HTML

```html
// demo.aisuhua.com
<html>
<body>
<script>
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
  
    xhr.open('post', 'http://foo.aisuhua.com/nginx_cors_server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/xml');
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.write(xhr.responseText);
        }
    };
</script>
</body>
</html>
```
