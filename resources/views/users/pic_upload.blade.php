{{-- resources/views/users/pic_upload.blade.php --}}

<x-app-layout>
    {{-- ページタイトル --}}
    <x-slot name="title">写真アップロード</x-slot>

    {{-- ヘッダー --}}
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-12 p-3">
                    <h2 class="text-secondary">写真アップロード</h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- メインコンテンツ --}}
    <x-slot name="slot">
        @csrf

        {{-- ユーザーID（非表示） --}}
        <div id="u_id" class="hidden">{{ Auth::user()->id }}</div>

        {{-- ファイルアップロード --}}
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center">写真アップロード</h3>
                </div>
            </div>

            <div class="row mt-5 pb-3">
                <div class="col-md-4">&nbsp;</div>

                <div class="col-md-4 d-grid">
                    <button type="button" id="file_up_bt" class="btn btn-primary">ファイルアップロード</button>
                    <input type="file" id="select_file" style="display: none" multiple>
                    <input type="file" id="change_file" style="display: none">
                </div>

                <div class="col-md-4">&nbsp;</div>
            </div>
        </div>

        <hr>

        {{-- ファイルリスト --}}
        <div class="row mt-3">
            <div class="col-12" id="message"></div>
            <div id="list_area"></div>
        </div>

        {{-- モーダル部 --}}
        <button type="button" id="modal_bt" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display: none">
            Launch demo modal
        </button>

        {{-- ファイル選択モーダル --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ファイル選択</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="pic_title">&nbsp;</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="button" class="btn btn-danger" id="delete_bt">削除</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 削除確認ダイアログ --}}
        <div id="delete_dialog" title="削除の確認">
            <p>ファイルを削除してもよろしいですか？</p>
        </div>

        {{-- アップロード中ダイアログ --}}
        <div id="upload_dialog" title="ファイルアップロード中" class="d-flex justify-content-center p-3">
            <img src="{{ asset('images/loading.gif') }}" class="w-25">
        </div>

        {{-- タイトル変更ダイアログ --}}
        <div id="dialog-form" title="タイトル変更">
            <div class="mb-3">
                <label for="set_title" class="form-label">タイトルを入力</label>
                <input type="text" class="form-control" id="set_title">
            </div>
        </div>

        {{-- JavaScriptエリア --}}
        {{-- jQueryの読み込み（必ず先に読み込む） --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        {{-- ここから独自JS --}}
        <script>
            var page_num = 0;

            function changeContents(num) {
                page_num = num;
                document.body.style.cursor = 'wait';
                let code = document.querySelector('[name="_token"]').value;
                let user_id = document.getElementById('u_id').textContent.trim();
                var fd = new FormData();
                fd.append("_token", code);
                fd.append("u_id", user_id);
                fd.append("page_num", num);

                $.ajax({
                    url: '/user_pics',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    timeout: 10000,
                    error: function(xhr, status, error) {
                        alert(error);
                        document.body.style.cursor = 'auto';
                    },
                    beforeSend: function() {},
                    success: function(res) {
                        document.body.style.cursor = 'auto';
                        $('#list_area').html(res);
                    }
                });
            }

            $(function() {
                $('#file_up_bt').on('click', function() {
                    $('#select_file').trigger('click');
                });

                $('#select_file').on('change', function(e) {
                    $('#upload_dialog').dialog('open');
                    for (var i = 0; i < e.target.files.length; i++) {
                        uploadData(e.target.files[i], e.target.files[i].name);
                    }
                    $('#upload_dialog').dialog('close');
                });
            });

            function uploadData(file, name) {
                let code = document.querySelector('[name="_token"]').value;
                let user_id = document.getElementById('u_id').textContent.trim();
                var fd = new FormData();
                fd.append("_token", code);
                fd.append("upfile", file);
                fd.append("u_id", user_id);

                $.ajax({
                    url: '/save_pics',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    timeout: 10000,
                    error: function(xhr, status, error) {
                        alert(error);
                        document.body.style.cursor = 'auto';
                    },
                    beforeSend: function() {},
                    success: function(res) {
                        document.body.style.cursor = 'auto';
                        $('#message').html(res);
                        changeContents(0);
                    }
                });
            }
        </script>

        {{-- Viteビルド後のJS読み込み --}}
        @vite(['resources/js/app.js'])
    </x-slot>
</x-app-layout>
