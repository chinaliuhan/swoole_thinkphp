chart = {
    socketConnect: function () {
        var charWs = new WebSocket('ws://127.0.0.1:8812');
        charWs.onopen = function (evt) {
            console.log('聊天室连接成功');
        };
        charWs.onmessage = function (evt) {
            console.log(evt.data);
            chart.html(JSON.parse(evt.data));
        };

        charWs.onclose = function (evt) {
            console.log('聊天室连接已关闭');
        };

        charWs.onerror = function (evt) {
            console.log('聊天室连接发生错误' + evt.data);
        }
    }
    ,
    send: function () {
        $('#discuss-box').keyup(function (evt) {
            if (evt.keyCode != 13) {
                return false;
            }
            var text = $(this).val();
            var data = {'content': text, 'game_id': 1};
            $.post('/index/chart/index', data, function (d) {
                $(this).val('');
            }, 'json');
        });
    },
    html: function (data) {
        var html = '<div class="comment">';
        html += '<span>用户:' + data.user + '</span>';
        html += '<span>' + data.content + '</span>';
        html += '</div>';

        $('#comments').prepend(html);

    }
}




