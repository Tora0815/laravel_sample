{{--
    ファイル: resources/views/contents/album.blade.php
    目的:
    - 指定ユーザーのアルバム一覧を表示するページ
    - モーダルを使った画像詳細表示
    - Ajaxを使ってサムネイル一覧・ページングを動的にロードする
--}}

<x-page-base>
    {{-- ページタイトル設定 --}}
    <x-slot name="title">アルバム閲覧</x-slot>

    {{-- メインコンテンツ --}}
    <x-slot name="slot">
        @csrf

        {{-- ユーザーID保持(hidden要素) --}}
        <div id="u_id" class="hidden">{{ $show_user_id }}</div>

        <div class="container">
            {{-- アルバムタイトル表示 --}}
            <div class="row mt-5">
                <div class="col-12">
                    <h1 class="text-center">{{ $user_name }} さんのアルバム</h1>
                </div>
            </div>

            {{-- サムネイルリスト表示領域 --}}
            <div class="row mt-5">
                <div class="col-12" id="message"></div>
                <div id="list_area"></div>
            </div>
        </div>

        {{-- モーダルを開くためのトリガーボタン（通常は非表示） --}}
        <button type="button" id="modal_bt" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#exampleModal" style="display: none;">
            Launch demo modal
        </button>

        {{-- モーダル本体 --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            タイトル: <span id="pic_title"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="f_name">&nbsp;</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ページ専用JavaScript --}}
    <x-slot name="script">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            $(function() {
                // 現在表示中のページ番号（ページング用）
                var page_num = 0;

                // モーダル表示対象の画像ID（グローバルに保持）
                var change_id = 0;

                /**
                 * サムネイル一覧をAjaxで取得して表示する処理
                 * @param {int} num - ページ番号（0から始まる）
                 */
                function changeContents(num) {
                    page_num = num;
                    document.body.style.cursor = 'wait'; // ローディング中にカーソル変更

                    // CSRFトークンと対象ユーザーIDを取得
                    let code = $('meta[name="csrf-token"]').attr('content');
                    let user_id = $("#u_id").html();

                    // Ajax送信用のFormData作成
                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("page", num);
                    fd.append("u_id", user_id);

                    // Ajaxで画像一覧を取得
                    $.ajax({
                        url: "./show_pics", // Laravelルートと一致させる
                        type: "POST",
                        data: fd,
                        mode: "multiple",
                        processData: false,
                        contentType: false,
                        async: false,
                        timeout: 10000,
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown); // 通信エラー表示
                            document.body.style.cursor = 'auto';
                        },
                        success: function(res) {
                            document.body.style.cursor = 'auto';
                            $("#list_area").html(res); // サムネイルHTMLを埋め込み
                        }
                    });
                }

                /**
                 * サムネイル画像がクリックされたときの処理
                 * モーダル表示のため、画像IDを記録して取得処理へ
                 */
                $(document).on("click", ".show_modal_bt", function(e) {
                    change_id = $(this).attr('value'); // 画像IDを取得
                    getMasterImage(change_id); // マスター画像取得
                });

                /**
                 * 指定された画像のマスター画像データを取得し、モーダルに表示
                 * @param {int} id - 対象画像のID
                 */
                function getMasterImage(id) {
                    document.body.style.cursor = 'wait';

                    let code = $('meta[name="csrf-token"]').attr('content');
                    let user_id = $("#u_id").html();

                    var fd = new FormData();
                    fd.append("_token", code);
                    fd.append("img_id", id);
                    fd.append("u_id", user_id);

                    // Ajaxでマスター画像を取得（HTML形式）
                    $.ajax({
                        url: "./pic_up",
                        type: "POST",
                        data: fd,
                        mode: "multiple",
                        processData: false,
                        contentType: false,
                        async: false,
                        timeout: 10000,
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            document.body.style.cursor = 'auto';
                        },
                        success: function(res) {
                            document.body.style.cursor = 'auto';

                            // resに含まれるtitle属性を取得・表示
                            $("#set_title").val($(res).attr('title'));
                            $("#f_name").html($(res).attr('title'));
                            $("#pic_title").html($(res).attr('title'));

                            // 非表示ボタンをトリガーしてモーダル表示
                            $("#modal_bt").trigger("click");
                        }
                    });
                }

                /**
                 * ページ番号ボタンが押されたら、changeContents()を再実行
                 */
                $(document).on("click", ".page_bt", function(e) {
                    changeContents($(this).val());
                });

                // ページ表示時に最初の一覧（ページ0）を読み込む
                changeContents(0);
            });
        </script>
    </x-slot>

</x-page-base>
