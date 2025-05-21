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
            <div class="col-12" id="message"></div>

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
                            <button type="button" id="set_title_bt" class="btn btn-info"
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

                let currentPicId = null;

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
                            $("#list_area").html(res); // 結果HTMLを表示
                        });
                }

                // ファイルアップロードボタンが押されたら、ファイル選択inputをクリック
                $("#file_up_bt").on("click", function(e) {
                    $("#select_file").trigger("click");
                });

                // ファイル選択時の処理（複数対応）
                $("#select_file").on("change", function(e) {
                    $("#upload_dialog").dialog("open"); // ローディングダイアログを表示
                    for (var i = 0; i < e.target.files.length; i++) {
                        var file = e.target.files[i];
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
                    fd.append("upload_file", file);
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
                            // 成功後にファイルリスト再読み込み
                            changeContents(0);
                        }
                    });
                }


                // 削除ボタンクリック時の処理
                $('#delete_bt').on('click', function(e) {
                    $("#delete_dialog").dialog("open");
                });

                // タイトル設定ボタンクリック時の処理
                $('#set_title_bt').on('click', function(e) {
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
                var change_id = ""
                $(document).on("click", ".show_modal_bt", function(e) {
                    // 押下画像のIDを取得してグローバル変数にも保存
                    const id = $(this).attr("value");
                    currentPicId = id;
                    getMasterImage(id); // 画像情報を取得
                });

                // マスター画像データ取得処理
                function getMasterImage(id) {
                    // データ送信準備（カーソルをwaitに）
                    document.body.style.cursor = "wait";

                    // ———— CSRFトークンの取得 ————
                    // layout/app.blade.php の <head> に以下が必要です
                    // <meta name="csrf-token" content="{{ csrf_token() }}">
                    const token = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');

                    // ———— ユーザーIDの取得 ————
                    const user_id = document.getElementsByName("u_id")[0].value;

                    // FormDataオブジェクトを生成し、必要データを追加
                    const fd = new FormData();
                    fd.append("_token", token);
                    fd.append("img_id", id);
                    fd.append("u_id", user_id);

                    // ———— Ajax 呼び出し ————
                    $.ajax({
                            url: "{{ route('pic.master') }}",
                            type: "POST",
                            data: fd,
                            dataType: "html", // ← HTML（SVG／<img>タグ）として受け取る
                            timeout: 10000,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                document.body.style.cursor = "wait";
                            },
                            error: function(xhr) {
                                document.body.style.cursor = "auto";
                            }
                        })

                        .done(function(res) {
                            // ① まずHTMLをモーダルに挿入
                            $("#frame").html(res);

                            // ② 挿入後にdata-pic-idを読み取ってcurrentPicIdをセット
                            currentPicId = $("#frame img").data("pic-id");
                            // title属性も拾ってセット
                            const title = $("#frame").find("svg, img").attr("title") || "";
                            $("#pic_title").text(title);
                            $("#set_title").val(title);

                            // モーダルを表示
                            new bootstrap.Modal(
                                document.getElementById('exampleModal')
                            ).show();

                            document.body.style.cursor = "auto";

                        });
                }

                // ファイル削除処理
                function deleteFile() {
                    // FormData の準備
                    let fd = new FormData();
                    fd.append("_token", $('meta[name="csrf-token"]').attr("content"));
                    fd.append("delete_id", currentPicId);

                    // FormData の中身を確認
                    for (let [k, v] of fd.entries()) {}

                    // AJAX 実行
                    $.ajax({
                            url: "/delete_pic",
                            type: "POST",
                            data: fd,
                            processData: false,
                            contentType: false,
                        })
                        .done(function(res) {
                            alert("削除に成功しました");
                            changeContents(0); // 一覧を再読み込み（画像リスト更新）
                            currentPicId = null; // 削除済みIDをクリアする ← これが重要！
                            $("#exampleModal").modal("hide"); // モーダルを閉じる（必要であれば）
                        })
                        .fail(function(xhr) {
                            alert("削除に失敗しました");
                        });
                }


                // タイトル保存処理
                function saveTitle() {
                    console.log("▶ saveTitle() called, change_id =", change_id, "newTitle =", $("#set_title").val());

                    // データ送信準備
                    document.body.style.cursor = "wait";
                    let code = document.getElementsByName("_token").item(0).value;
                    let user_id = document.getElementsByName("u_id").item(0).value;

                    // FormData オブジェクトを生成し、タイトル・IDを追加
                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("save_id", currentPicId);
                    fd.append("title", $("#set_title").val());
                    fd.append("u_id", user_id);

                    // XHRで送信（タイトル更新）
                    $.ajax({
                            url: "{{ route('pic.title') }}",
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
                        });
                }

                // ページ切り替え処理（ページネーション用）
                $(document).on("click", ".page_bt", function(e) {
                    var page_num = $(this).val();
                    changeContents(page_num);
                });
                // タイトル設定ボタンが押されたか確認
                $(document).on("click", "#title_bt", function() {
                    const newTitle = $("#set_title").val();
                    // ここで Ajax 送信するなら…例:
                    // updateTitleAjax(picId, newTitle);
                });

                // 削除ボタンが押されたか確認
                $(document).on("click", "#delete_bt", function() {
                    // 例として、モーダル内の <img data-pic-id="…"> から取得
                    const picId = $("#frame img").data("pic-id");
                });

            });
        </script>
    </x-slot>
</x-app-layout>
