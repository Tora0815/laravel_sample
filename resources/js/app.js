import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

import $ from "jquery";
window.$ = $;
window.jQuery = $;

$(function () {
    console.log("✅ DOM Ready");

    $("#file_up_bt").on("click", function () {
        console.log("✅ アップロードボタン押された");
        $("#select_file").trigger("click");
    });

    $("#select_file").on("change", function (e) {
        console.log("✅ ファイルが選択されました");
        let files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            uploadData(files[i]);
        }
    });

    changeContents(0);
});

function changeContents(page) {
    let fd = new FormData();
    fd.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );
    fd.append("u_id", $("#u_id").val());
    fd.append("page", page);

    $.ajax({
        url: "/user_pics",
        method: "POST",
        data: fd,
        contentType: false,
        processData: false,
    }).done(function (res) {
        $("#list_area").html(res);
    });
}

function uploadData(file) {
    let fd = new FormData();
    fd.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );
    fd.append("u_id", $("#u_id").val());
    fd.append("upfile", file);

    $.ajax({
        url: "/save_pics",
        method: "POST",
        data: fd,
        contentType: false,
        processData: false,
    }).done(function (res) {
        changeContents(0);
    });
}
