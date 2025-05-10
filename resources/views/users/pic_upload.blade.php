{{-- resources/views/pic_upload.blade.php --}}
{{-- 写真アップロードページ --}}
<x-app-layout>
    {{-- タイトルスロット --}}
    <x-slot name="title">写真アップロード</x-slot>

    {{-- ヘッダーセクション --}}
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-12 p-3">
                    <h2 class="text-secondary">写真アップロード</h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- コンテンツスロット --}}
    <x-slot name="slot">
        {{-- ログインユーザーIDを hidden input にセット --}}
        <input type="hidden" name="u_id" id="u_id" value="{{ Auth::id() }}">


        <div class="container">
            <div class="row">
                <div class="col-12">
                    {{-- 中央寄せでページタイトルを再表示 --}}
                    <h3 class="text-center">写真アップロード</h3>
                </div>
            </div>

            {{-- アップロードボタンとファイル選択UI --}}
            <div class="row mt-5 pb-3">
                <div class="col-md-4">&nbsp;</div>

                <div class="col-md-4 d-grid">
                    {{-- アップロードボタン（#file_up_btでjQueryからトリガー） --}}
                    <button class="btn btn-primary" id="file_up_bt">ファイルアップロード</button>

                    {{-- ファイル選択input（非表示） --}}
                    <input type="file" id="select_file" style="display: none;" multiple>
                    <input type="file" id="change_file" style="display: none;">
                </div>

                <div class="col-md-4">&nbsp;</div>
            </div>

            {{-- アップロード後のメッセージやファイル一覧表示領域 --}}
            <hr class="mt-3">
            <div class="col-12">ファイルリスト</div>
            <div class="col-12" id="message">×</div>

            <div id="list_area"></div>

            {{-- Bootstrap モーダル起動ボタン（非表示） --}}
            <!-- Button trigger modal -->
            <button type="button" id="modal_bt" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#exampleModal" style="display: none;">Launch demo modal</button>

            {{-- モーダル本体（画像確認やタイトル入力に使用予定） --}}
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    {{-- モーダルの中身は次ページに続く --}}
                    <div class="modal-content">
                        {{-- モーダルのヘッダー：タイトルと閉じるボタン --}}
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">ファイル選択</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        {{-- モーダルの本文：画像プレビューとタイトル表示エリア --}}
                        <div class="modal-body">
                            <div id="frame"></div>
                            <p id="pic_title">&nbsp;</p>
                        </div>

                        {{-- モーダルのフッター：操作用ボタン群（キャンセル・タイトル変更・削除） --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                            <button type="button" id="title_bt" class="btn btn-info"
                                data-bs-dismiss="modal">タイトル設定</button>
                            <button type="button" id="delete_bt" class="btn btn-danger"
                                data-bs-dismiss="modal">削除</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 削除確認用のダイアログ --}}
            <div id="delete_dialog" title="削除の確認">
                <p class="text-center">ファイルを削除してもよろしいですか？</p>
            </div>

            {{-- アップロード中に表示するローディングアニメーション --}}
            <div id="upload_dialog" title="アップロード中">
                <div class="d-flex justify-content-center p-3">
                    <img src="{{ asset('images/loading.gif') }}" class="">
                </div>
            </div>

            {{-- タイトル編集用の入力フォーム（ダイアログ） --}}
            <div id="dialog-form" title="タイトル設定">
                <div class="mb-3">
                    <label for="set_title" class="form-label">タイトルを入力</label>
                    <input type="text" class="form-control" id="set_title">
                </div>
            </div>
    </x-slot>

    {{-- JavaScript処理（写真一覧の取得・表示） --}}
    <x-slot name="script">
        {{-- jQueryとjQueryUIを読み込む --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script>
            $(function() {
                var page_num = 0;

                // 初期読み込み時の処理
                changeContents(0);

                function changeContents(num) {
                    page_num = num;

                    // 読み込み中はカーソルをwait状態に
                    document.body.style.cursor = 'wait';

                    // CSRFトークンとユーザーIDを取得
                    let code = document.getElementsByName("_token").item(0).value;
                    let user_id = document.getElementsByName("u_id").item(0).value;

                    // FormDataにCSRFトークンを追加
                    var fd = new FormData();
                    fd.append("_token", code);


                    {{-- 出典ページ：P156 --}}


                    fd.append("page", num);
                    fd.append("u_id", user_id);

                    // XHRで送信（jQuery Ajax）
                    $.ajax({
                            url: "./user_pics",
                            type: "POST",
                            data: fd,
                            dataType: "html",
                            processData: false, // ファイルアップロードのためfalse
                            contentType: false, // 同上
                            timeout: 10000, // タイムアウト設定（ミリ秒）

                            // 通信失敗時の処理
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("err:" + XMLHttpRequest.status + "\n" + XMLHttpRequest.statusText);
                                document.body.style.cursor = 'auto';
                            },

                            // 通信開始前の処理（ローディング表示などに利用可）
                            beforeSend: function(xhr) {}
                        })

                        // 通信成功時の処理
                        .done(function(res) {
                            document.body.style.cursor = 'auto';
                            // console.log(res);
                            $("#list_area").html(res); // 結果HTMLを表示
                        });
                }

                // ファイルアップロードボタンが押されたら、ファイル選択inputをクリック
                $("#file_up_bt").on("click", function(e) {
                    $("#select_file").trigger("click");
                });

                // ファイル選択時の処理（複数対応）
                $("#select_file").on("change", function(e) {
                    console.log("ファイルが選択されました！");
                    $("#upload_dialog").dialog("open"); // ローディングダイアログを表示
                    for (var i = 0; i < e.target.files.length; i++) {
                        var file = e.target.files[i];
                        console.log(file.name); // デバッグ出力（省略可）
                        uploadData(file, name); // アップロード処理呼び出し
                    }

                    // アップロード完了後に一覧更新・ダイアログ閉じ
                    changeContents(0);
                    $("#upload_dialog").dialog("close");
                });

                // ファイルアップロード処理（1ファイルごと）
                // ファイルアップロード用関数
                function uploadData(file, name) {
                    // カーソルをwaitに設定（ユーザーに処理中と伝える）
                    document.body.style.cursor = 'wait';

                    // CSRFトークンとユーザーIDを取得
                    let code = document.getElementsByName("_token").item(0).value;
                    let user_id = document.getElementsByName("u_id").item(0).value;

                    // FormDataオブジェクトを用意
                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("name", name);
                    fd.append("upfile", file);
                    fd.append("u_id", user_id);

                    // XHR で送信
                    $.ajax({
                        url: "./save_pics",
                        type: "POST",
                        data: fd,
                        mode: "multiple",
                        processData: false,
                        contentType: false,
                        async: false,
                        timeout: 10000, // 単位はミリ秒
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("エラーが発生しました (status:" + XMLHttpRequest.status + ")");
                            document.body.style.cursor = 'auto';
                        },
                        beforeSend: function(xhr) {
                            // 送信前に何かしたければここに書く（今回は空）
                        },
                        success: function(res) {
                            document.body.style.cursor = 'auto';
                            console.log("アップロード成功！");
                            console.log(res);

                            // 成功後にファイルリスト再読み込み
                            changeContents(0);
                        }
                    });
                }


                // 削除ボタンクリック時の処理
                $('#delete_bt').on('click', function(e) {
                    console.log(change_id);
                    $("#delete_dialog").dialog("open");
                });

                // タイトル設定ボタンクリック時の処理
                $('#set_title_bt').on('click', function(e) {
                    console.log(change_id);
                    $("#dialog-form").dialog("open");
                });

                // 削除確認ダイアログの設定
                $("#delete_dialog").dialog({
                    autoOpen: false,
                    resizable: false,
                    height: "auto",
                    width: "auto",
                    fluid: true,
                    modal: true,
                    buttons: {
                        "削除する": function() {
                            $(this).dialog("close");
                            deleteFile();
                        },
                        "キャンセル": function() {
                            {{-- 出典ページ：P158 --}}


                            $(this).dialog("close");
                        }
                    }
                });

                // アップロードダイアログ設定
                $("#upload_dialog").dialog({
                    autoOpen: false,
                    resizable: false,
                    height: "auto",
                    width: "auto",
                    fluid: true,
                    modal: true,
                });

                // タイトル設定ダイアログ設定
                $("#dialog-form").dialog({
                    autoOpen: false,
                    height: "auto",
                    width: 600,
                    modal: true,
                    buttons: {
                        "保存": function() {
                            $(this).dialog("close");
                            saveTitle();
                        },
                        "キャンセル": function() {
                            $(this).dialog("close");
                        }
                    }
                });

                // ファイル表示モーダル呼び出し（画像クリック時に実行）
                var change_id = "";
                $(document).on("click", ".show_modal_bt", function(e) {
                    console.log("thumb click");

                    // message = "";
                    // $("#message").html(message);

                    change_id = $(this).attr("value"); // 押下画像のIDを取得
                    getMasterImage(change_id); // 画像情報を取得
                });

                // マスター画像データ取得処理
                function getMasterImage(id) {
                    // データ送信準備（カーソルをwaitに）
                    document.body.style.cursor = "wait";

                    // CSRFトークンとユーザーIDを取得
                    let code = document.getElementsByName("_token").item(0).value;
                    let user_id = document.getElementsByName("u_id").item(0).value;

                    // FormDataオブジェクトを生成し、必要データを追加
                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("id", id);
                    fd.append("u_id", user_id);
                    {{-- 出典ページ：P159 --}}
                    // XHR で送信（画像情報取得処理）
                    $.ajax({
                            url: "./get_master",
                            type: "POST",
                            data: fd,
                            mode: "multiple",
                            processData: false,
                            contentType: false,
                            async: false,
                            timeout: 10000, // 単位はミリ秒

                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("err:" + XMLHttpRequest.status + "\n" + XMLHttpRequest.statusText);
                                document.body.style.cursor = "auto";
                            },
                            beforeSend: function(xhr) {}
                        })

                        .done(function(res) {
                            document.body.style.cursor = "auto";
                            // console.log(res);
                            $("#set_title").val($(res).attr("title")); // タイトルを入力欄にセット
                            $("#pic_title").html($(res).attr("title")); // タイトルを表示欄にセット
                            $("#frame").html(res); // 画像HTMLをモーダルに表示
                            $("#modal_bt").trigger("click"); // モーダル表示をトリガー
                        });
                }

                // ファイル削除処理
                function deleteFile() {
                    console.log("delete");

                    // データ送信準備
                    document.body.style.cursor = "wait";
                    let code = document.getElementsByName("_token").item(0).value;
                    let user_id = document.getElementsByName("u_id").item(0).value;

                    // FormData オブジェクトにデータを追加
                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("delete_id", change_id);
                    fd.append("u_id", user_id);

                    // XHR で送信（削除処理）
                    $.ajax({
                            url: "./delete_pic",
                            type: "POST",
                            data: fd,
                            mode: "multiple",
                            processData: false,
                            contentType: false,
                            timeout: 10000, // 単位はミリ秒

                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("err:" + XMLHttpRequest.status + "\n" + XMLHttpRequest.statusText);
                                document.body.style.cursor = "auto";
                            },
                            {{-- 出典ページ：P160 --}}
                            beforeSend: function(xhr) {}
                        })

                        .done(function(res) {
                            document.body.style.cursor = "auto";
                            // console.log(res);
                            // message = message + "：" + res;
                            // $("#message").html(message);
                            changeContents(page_num); // 一覧を再読込
                        });
                }

                // タイトル保存処理
                function saveTitle() {
                    console.log("delete");

                    // データ送信準備
                    document.body.style.cursor = "wait";
                    let code = document.getElementsByName("_token").item(0).value;
                    let user_id = document.getElementsByName("u_id").item(0).value;

                    // FormData オブジェクトを生成し、タイトル・IDを追加
                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("save_id", change_id);
                    fd.append("title", $("#set_title").val());
                    fd.append("u_id", user_id);

                    // XHRで送信（タイトル更新）
                    $.ajax({
                            url: "./save_title",
                            type: "POST",
                            data: fd,
                            mode: "multiple",
                            processData: false,
                            contentType: false,
                            timeout: 10000, // 単位はミリ秒

                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert("err:" + XMLHttpRequest.status + "\n" + XMLHttpRequest.statusText);
                                document.body.style.cursor = "auto";
                            },
                            beforeSend: function(xhr) {}
                        })

                        .done(function(res) {
                            document.body.style.cursor = "auto";
                            console.log(res);
                        });
                }

                // ページ切り替え処理（ページネーション用）
                $(document).on("click", ".page_bt", function(e) {
                    console.log("ページ切り替えクリック！");
                    var page_num = $(this).val();
                    console.log("選択されたページ番号:", page_num);
                    changeContents(page_num);
                });
            });
        </script>
    </x-slot>
</x-app-layout>
