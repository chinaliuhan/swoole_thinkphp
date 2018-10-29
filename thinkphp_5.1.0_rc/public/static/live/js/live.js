live = {
    liveSocket: function () {
        var webSocket = new WebSocket('ws://127.0.0.1:8811');
        webSocket.onopen = function (evt) {
            console.log('直播连接成功');
        };
        webSocket.onmessage = function (evt) {
            console.log(evt.data);
            live.appendLive(evt.data)
        };

        webSocket.onclose = function (evt) {
            console.log('直播连接已关闭');
        };

        webSocket.onerror = function (evt) {
            console.log('直播连接发生错误' + evt.data);
        }
    },
    appendLive: function (data) {
        console.log(data)
        data = JSON.parse(data)
        console.log(data)
        var html = '<div class="frame">';
        html += '<h3 class="frame-header">';
        html += '<i class="icon iconfont icon-shijian"></i>第' + data.type + '节' + data.date;
        html += '</h3>';
        html += '<div class="frame-item">';
        html += '<span class="frame-dot"></span>';
        html += '<div class="frame-item-author">';
        if (data.logo) {
            html += '<img src="' + data.logo + '" width="20px" height="20px" />';
        }
        html += '</div>';
        html += '<p>' + data.title + '</p>';
        html += '<p>' + data.content + '</p>';
        html += '</div>';
        html += '</div>';

        $('#match-result').prepend(html)
    }
}