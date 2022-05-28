# yt-dlp

## 安装

```sh
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod a+rx /usr/local/bin/yt-dlp
```

## 基本使用

查看视频清晰度信息

```sh
xxx@master:~/Downloads$ yt-dlp -F --proxy socks5://127.0.0.1:1081  https://www.youtube.com/watch?v=9oF_COGxs6c
[youtube] 9oF_COGxs6c: Downloading webpage
[youtube] 9oF_COGxs6c: Downloading android player API JSON
[info] Available formats for 9oF_COGxs6c:
ID  EXT   RESOLUTION FPS │   FILESIZE   TBR PROTO │ VCODEC          VBR ACODEC      ABR     ASR MORE INFO
─────────────────────────────────────────────────────────────────────────────────────────────────────────────────
sb2 mhtml 48x27          │                  mhtml │ images                                      storyboard
sb1 mhtml 80x45          │                  mhtml │ images                                      storyboard
sb0 mhtml 160x90         │                  mhtml │ images                                      storyboard
139 m4a   audio only     │    5.53MiB   48k https │ audio only          mp4a.40.5   48k 22050Hz low, m4a_dash
249 webm  audio only     │    6.18MiB   54k https │ audio only          opus        54k 48000Hz low, webm_dash
250 webm  audio only     │    8.18MiB   72k https │ audio only          opus        72k 48000Hz low, webm_dash
140 m4a   audio only     │   14.66MiB  129k https │ audio only          mp4a.40.2  129k 44100Hz medium, m4a_dash
251 webm  audio only     │   15.92MiB  140k https │ audio only          opus       140k 48000Hz medium, webm_dash
17  3gp   176x144     12 │    8.84MiB   78k https │ mp4v.20.3       78k mp4a.40.2    0k 22050Hz 144p
394 mp4   256x144     24 │    7.32MiB   64k https │ av01.0.00M.08   64k video only              144p, mp4_dash
160 mp4   256x144     24 │   12.57MiB  111k https │ avc1.4d400c    111k video only              144p, mp4_dash
278 webm  256x144     24 │   10.18MiB   89k https │ vp9             89k video only              144p, webm_dash
395 mp4   426x240     24 │   12.62MiB  111k https │ av01.0.00M.08  111k video only              240p, mp4_dash
133 mp4   426x240     24 │   27.78MiB  245k https │ avc1.4d4015    245k video only              240p, mp4_dash
242 webm  426x240     24 │   16.77MiB  148k https │ vp9            148k video only              240p, webm_dash
396 mp4   640x360     24 │   23.24MiB  205k https │ av01.0.01M.08  205k video only              360p, mp4_dash
134 mp4   640x360     24 │   39.40MiB  347k https │ avc1.4d401e    347k video only              360p, mp4_dash
18  mp4   640x360     24 │   42.61MiB  376k https │ avc1.42001E    376k mp4a.40.2    0k 44100Hz 360p
243 webm  640x360     24 │   30.12MiB  265k https │ vp9            265k video only              360p, webm_dash
397 mp4   854x480     24 │   39.10MiB  345k https │ av01.0.04M.08  345k video only              480p, mp4_dash
135 mp4   854x480     24 │   80.41MiB  710k https │ avc1.4d401e    710k video only              480p, mp4_dash
244 webm  854x480     24 │   41.70MiB  368k https │ vp9            368k video only              480p, webm_dash
22  mp4   1280x720    24 │ ~152.38MiB 1313k https │ avc1.64001F   1313k mp4a.40.2    0k 44100Hz 720p
398 mp4   1280x720    24 │   71.69MiB  633k https │ av01.0.05M.08  633k video only              720p, mp4_dash
136 mp4   1280x720    24 │  157.77MiB 1393k https │ avc1.4d401f   1393k video only              720p, mp4_dash
247 webm  1280x720    24 │   99.27MiB  876k https │ vp9            876k video only              720p, webm_dash
399 mp4   1920x1080   24 │  124.69MiB 1101k https │ av01.0.08M.08 1101k video only              1080p, mp4_dash
137 mp4   1920x1080   24 │  342.60MiB 3025k https │ avc1.640028   3025k video only              1080p, mp4_dash
248 webm  1920x1080   24 │  191.37MiB 1690k https │ vp9           1690k video only              1080p, webm_dash
```

选择清晰度进行下载

```sh
yt-dlp -f 137+140 --merge-output-format mp4 --proxy socks5://127.0.0.1:1081  https://www.youtube.com/watch?v=9oF_COGxs6c
```

## 参考文献

1. [使用 yt-dlp 下载 youtube 视频的一点体会](https://zhuanlan.zhihu.com/p/431013905)
2. https://github.com/yt-dlp/yt-dlp

