angular.module('todoApp', ['ngSanitize'])
  .directive('sf', function() {
    return {
      restrict: 'E',
      transclude: false,
      template: function(element, scope) {
        var content = element[0].innerHTML;
        console.log('wtf');
        return ["<div class='sf'>",
          content,
          "</div>"
        ].join("\n");
      },
    };
  })
  .controller('TodoListController', function() {
    var vm = this;
    vm.list = [
      ["搶奪卡", "若踩到其他人土地時<br />付完罰鍰後<br />可以使用此卡將地契搶過來", "付完罰鍰後使用"],
      ["搶奪卡", "若踩到其他人土地時<br />付完罰鍰後<br />可以使用此卡將地契搶過來", "付完罰鍰後使用"],
      ["搶奪卡", "若踩到其他人土地時<br />付完罰鍰後<br />可以使用此卡將地契搶過來", "付完罰鍰後使用"],
      ["搶奪卡", "若踩到其他人土地時<br />付完罰鍰後<br />可以使用此卡將地契搶過來", "付完罰鍰後使用"],
      ["搶奪卡", "若踩到其他人土地時<br />付完罰鍰後<br />可以使用此卡將地契搶過來", "付完罰鍰後使用"],
      ["前進 1 格", "立即前進 1 格<br />使用後不得再投擲骰子", "回合開始時使用"],
      ["前進 2 格", "立即前進 2 格<br />使用後不得再投擲骰子", "回合開始時使用"],
      ["前進 3 格", "立即前進 3 格<br />使用後不得再投擲骰子", "回合開始時使用"],
      ["前進 4 格", "立即前進 4 格<br />使用後不得再投擲骰子", "回合開始時使用"],
      ["前進 5 格", "立即前進 5 格<br />使用後不得再投擲骰子", "回合開始時使用"],
      ["前進 6 格", "立即前進 6 格<br />使用後不得再投擲骰子", "回合開始時使用"],
      ["均富卡", "與相同決鬥場上的玩家進行資產均分", "立即使用"],
      ["乾坤大挪移", "與其他決鬥場上的玩家進行交換位置", "立即使用"],
      ["乾坤大挪移", "與其他決鬥場上的玩家進行交換位置", "立即使用"],
      ["飛到忠孝東路", "若途中經過起點<br />一樣可以獲得起點獎勵", "立即使用"],
      ["飛到紅樓", "若途中經過起點<br />一樣可以獲得起點獎勵", "立即使用"],
      ["飛到大度路", "若途中經過起點<br />一樣可以獲得起點獎勵", "立即使用"],
      ["飛到中正館", "若途中經過起點<br />一樣可以獲得起點獎勵", "立即使用"],
      ["升級卡", "升級房屋時可連續升級兩次", "升級房屋時使用"],
      ["升級卡", "升級房屋時可連續升級兩次", "升級房屋時使用"],
      ["升級卡", "升級房屋時可連續升級兩次", "升級房屋時使用"],
      ["破壞卡", "可以清除別人土地上的建築物", "付完罰鍰後使用"],
      ["破壞卡", "可以清除別人土地上的建築物", "付完罰鍰後使用"],
      ["強制拆除卡", "立即損失一塊土地", "立即使用"],
      ["強制拆除卡", "立即損失一塊土地", "立即使用"],
      ["魏應充卡", "可以立即出獄", "入獄時使用"],
      ["魏應充卡", "可以立即出獄", "入獄時使用"],
    ]

    vm.list2 = [
      "被所有玩家彈額頭乙下",
      "被所有玩家打腳底板乙下",
      "被所有玩家打手心乙下",
      "被所有玩家彈耳朵乙下",
      "唱國歌～ 預備唱～<br><br>不唱的罰肉三份<br>太小聲罰肉一份",
      "唱校歌～ 預備唱～<br><br>不唱的罰肉三份<br>太小聲罰肉一份",
      "正轉 + 左 8 + 右 8 + 背旋<br><br>做不出來者罰飲料一份",
      "深深地輕吻自己的鞋子<br><br>做不出來者罰肉一份",
      "聞自己的襪子10秒</br>並深情款款地說自己的香港腳好香<br><br>做不出來者罰肉一份",
      "用憂鬱不失深情的眼神看著大家說<br>『 對不起 我是人妖』<br><br>做不出來者罰肉一份",
      "孤單一人跳第一支舞～ <br><br>做不出來者罰肉一份",
      "朗讀口吻自我介紹 <br><br>做不出來者罰肉一份",
      "比賽八大繩結<br />贏的拿輸的0.2份肉",
      "比賽八大繩結<br />贏的拿輸的0.2份肉",
      "全體競賽<br /><br />原地轉20圈走直線<br />輸的用舌頭舔手軸拍照，贏的獲得飲料一份",
      "全體競賽<br /><br />一分鐘比賽伏地挺身<br />輸的用舌頭舔手軸拍照，贏的獲得肉一份",
      "全體競賽<br /><br />比賽跳遠距離<br />輸的用舌頭舔手軸拍照，贏的獲得菜一份",
      "憑此券可兌換一根金針菇",
      "憑此券可兌換蟹肉<small>棒</small>乙份",
      "抽一個人來玩超級黑白配<br />贏的拔輸的鼻毛乙根",
      "微積分大對決<br/><br/><br/><br/><br/>最快解出來者獲得菜一份",
      "數字加減法大對決<br/><br/><br/><br/><br/>最快解出來者獲得菜一份",
      "微積分大對決<br/><br/><br/><br/><br/>最快解出來者獲得肉一份",
      "數字加減法大對決:<br/><br/><br/><br/><br/>最快解出來者獲得肉一份",
      "微積分大對決<br/><br/><br/><br/><br/>最快解出來者獲得飲料一份",
      "數字加減法大對決<br/><br/><br/><br/><br/>最快解出來者獲得飲料一份",
      "朗讀口吻自我介紹<br><br>做不出來者罰肉一份",
      "八點檔小劇場<br />『季Ａcase 南無夾丟，溫公司丟A關門大吉』",
      "八點檔小劇場<br />『Jason 季A 灣ㄒㄧㄡˊ哇拿西無特等哎， 我丟恩戲XXX阿拉』",
      "八點檔小劇場<br />『哩Ａ心肝吶Ａ架尼熊！ 哇霸豆來吸你誒baby， 你竟連拎心就挖鐵掉』",
      "接下來的遊戲穿白紗裙進行(抽到就換人)",
    ]
    return this;
  });