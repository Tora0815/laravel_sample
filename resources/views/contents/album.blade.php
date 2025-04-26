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
        <script>
            // 現在のページ番号
            var page_num = 0;

            /**
             * サムネイル一覧をAjaxで読み込む
             */
            function changeContents(num) {
                page_num = num;
                document.body.style.cursor = 'wait';

                let code = document.getElementsByName("_token")[0].value;
                let user_id = $("#u_id").html();

                var fd = new FormData();
                fd.append("_token", code);
                fd.append("page", num);
                fd.append("u_id", user_id);

                $.ajax({
                    url: "./show_pics",
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
                    beforeSend: function(xhr) {},
                    success: function(res) {
                        document.body.style.cursor = 'auto';
                        $("#list_area").html(res);
                    }
                });
            }

            // クリックされた画像IDを保持するための変数
            var change_id = 0;

            /**
             * サムネイル画像クリック時にモーダル呼び出し
             */
            $(document).on("click", ".show_modal_bt", function(e) {
                change_id = $(this).attr('value');
                getMasterImage(change_id);
            });

            /**
             * クリックされた画像の詳細データ（マスター画像）を取得してモーダル表示
             */
            function getMasterImage(id) {
                document.body.style.cursor = 'wait';

                let code = document.getElementsByName("_token")[0].value;
                let user_id = $("#u_id").html();

                var fd = new FormData();
                fd.append("_token", code);
                fd.append("img_id", id);
                fd.append("u_id", user_id);

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
                        $("#set_title").val($(res).attr('title'));
                        $("#f_name").html($(res).attr('title'));
                        $("#pic_title").html($(res).attr('title'));
                        $("#modal_bt").trigger("click");
                    }
                });
            }

            /**
             * ページ切り替えボタンクリック時の処理
             */
            $(document).on("click", ".page_bt", function(e) {
                changeContents($(this).val());
            });

            // ページ初期ロード時に最初のデータを読み込む
            changeContents(0);
        </script>
    </x-slot>
</x-page-base>
