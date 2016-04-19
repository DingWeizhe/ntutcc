$(function() {
    loadUsers();
});
var page = 0;
var users = [];
var user_idx = 0;
var loadImageInterval = 0;
var loadedImage = [];

function checkNeedLoadUser() {
    if ($("body").scrollTop() + $(window).height() > $("body").height() - 100) {
        loadUsers();
        $(window).off('scroll', checkNeedLoadUser);
    }

}

function loadUsers() {
    $.getJSON("/user/wallAJAX/" + page + "/" + $("input[name='gender']").val(), {}, function(data) {
        users = data;
        for (i = 0; i < 900; i++) {
            tmp = users[0];
            r = parseInt(Math.random() * 30);
            users[0] = users[r];
            users[r] = tmp;
        }
        loadUserImages();
    });
}

function loadUserImages() {
    userImages = [];
    for (var i in users) {
        userImages.push(
            $("<img>")
            .data("obj", users[i])
            .attr("src", "https://graph.facebook.com/" + users[i]['fb_id'] + "/picture?width=192")
            .on('load', afterLoadImage)
        );
    }
}

function afterLoadImage() {
    if (loadedImage.indexOf(this) == -1) {
        loadedImage.push(this);
    }
    userImages.splice(userImages.indexOf(this), 1);
    if (userImages.length == 0) {
        page++;
        setTimeout(loadUsers, 500);
    }

}

function pushUser2Wall() {
    if (loadedImage.length == 0) {
        setTimeout(pushUser2Wall, 100);
        return;
    }

    var min_i = 1;
    for (var i = 2; i < 6; i++) {
        if ($(".col-" + i).height() < $(".col-" + min_i).height()) {
            min_i = i;
        }
    }

    userImg = loadedImage.shift();
    user = $(userImg).data("obj");
    $(".col-" + min_i)
        .append(
            $("<a>")
            .attr("href", user['publishFB'] ? "https://www.facebook.com/" + user['fb_id'] : "#")
            .append(
                $("<div>")
                .addClass("user")
                .css({
                    opacity: 0
                })
                .animate({
                    opacity: 1
                })
                .append(userImg)
                .append(
                    $("<div>")
                    .addClass("name")
                    .html(user['name'])
                    .append(
                        $("<div>")
                        .addClass("status")
                        .html(user['status'])
                    )
                )



            )
    );
    setTimeout(pushUser2Wall, 300);
}

pushUser2Wall();