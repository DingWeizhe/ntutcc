< !doctype html >
  < html ng - app >
  < head >
  < script src = "https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js" > < /script>
  </head >
  < body >
  < div >
  < label > Name: < /label>
      <input type="text" ng-model="yourName" placeholder="Enter a name here">
      <hr>
      <h1>Hello {{yourName}}!</h1 >
  < /div>
  </body >
  < /html>
var list = ["比賽八大繩結， 贏的拿輸的0.2份肉",
  "原地轉20圈走直線， 最快到達終點免懲罰， 輸的用舌頭舔手軸拍照。",
  "獲得一根金針菇",
  "抽一個人來玩超級黑白配， 贏的拔輸的鼻毛乙根",
  "微積分出題最快解出來者 菜一份",
  "數字加減法大對決 最快解出來者 菜一份",
  "唱國歌～ 預備唱～ 不唱的罰肉三份 太小聲罰肉一份",
  "正轉 + 左 8 + 右 8 + 背旋 做不出來者罰飲料一份",
  "深深地輕吻自己的鞋子",
  "聞自己的襪子10秒， 大喊好香",
  "用憂鬱不失深情的眼神看著大家說『 對不起 我是人妖』",
  "孤單一人跳第一支舞～ 不跳的罰肉三份",
  "被所有玩家彈額頭",
  "朗讀口吻自我介紹",
  "比賽八大繩結， 贏的拿輸的0.2份肉",
  "原地轉20圈走直線， 最快到達終點免懲罰， 輸的用舌頭舔手軸拍照。",
  "獲得一根金針菇",
  "抽一個人來玩超級黑白配， 贏的拔輸的鼻毛乙根",
  "微積分出題最快解出來者 菜一份",
  "數字加減法大對決 最快解出來者 菜一份",
  "唱國歌～ 預備唱～ 不唱的罰肉三份 太小聲罰肉一份",
  "正轉 + 左 8 + 右 8 + 背旋 做不出來者罰飲料一份",
  "深深地輕吻自己的鞋子",
  "聞自己的襪子10秒， 大喊好香",
  "用憂鬱不失深情的眼神看著大家說『 對不起 我是人妖』",
  "孤單一人跳第一支舞～ 不跳的罰肉三份",
  "八點檔小劇場 - 『季Ａ",
  "case 南無夾丟溫公司丟A關門大吉』",
  "八點檔小劇場 - 『Jason 季A 灣ㄒㄧㄡˊ哇拿西無特等哎， 我丟恩戲XXX阿拉』",
  "八點檔小劇場 - 『哩Ａ心肝吶Ａ架尼熊！ 哇霸豆來吸你誒baby， 你竟連就挖鐵掉』",
  "接下來的遊戲穿白紗裙進行(抽到就換人)",
  "被所有玩家彈額頭",
  "被所有玩家打腳底板",
  "被所有玩家打手心",
  "被所有玩家彈耳朵",
  "朗讀口吻自我介紹",
  "飛到忠孝東路",
  "飛到紅樓",
  "飛到大度路",
  "飛到中正館",
  "搶奪卡",
  "搶奪卡",
  "搶奪卡",
  "搶奪卡",
  "搶奪卡",
  "乾坤大挪移",
  "乾坤大挪移"
]