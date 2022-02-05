# カレンダー
今時カレンダーなんて高機能なライブラリがたくさんあるので0から作るモチベーションは勉強か暇つぶしくらいのもんだと思っている。

```zsh
# 起動
$ docker build -t fuji-calender/php:7.4-cli .
$ docker run -it -d --rm --name fuji-calender -p 80:80 fuji-calender/php:7.4-cli

# 止める
$ docker stop fuji-calender
```
http://localhost/calender.php

<img width="508" alt="スクリーンショット 2022-02-06 1 37 22" src="https://user-images.githubusercontent.com/25114976/152650479-abee9dc8-1515-461f-80bb-59ea48e7ac5d.png">