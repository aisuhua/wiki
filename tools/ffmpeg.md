# ffmpeg

## 剪切视频

```
ffmpeg -i InputFile -vcodec copy -acodec copy -ss 00:00:00 -t 00:01:32 OutPutFile
```

## 转换格式

```sh
ffmpeg -i input.flv output.mp4
```
