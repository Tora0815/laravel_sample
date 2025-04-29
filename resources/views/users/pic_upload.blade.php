{{-- resources/views/users/pic_upload.blade.php --}}
{{-- (書籍 p.155) --}}
<x-app-layout>
    <x-slot name="title">写真アップロード</x-slot>

    {{-- ヘッダー部 --}}
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
        @csrf {{-- CSRFトークン --}}

        {{-- 隠し要素：ユーザーID --}}
        <div id="u_id" class="d-none">{{ Auth::user()->id }}</div>

        {{-- アップロードボタンとファイル選択 --}}
        <div class="container">
            <div class="row mt-5">
                <div class="col-md-4"></div>
                <div class="col-md-4 d-grid">
                    <button type="button" id="file_up_bt" class="btn btn-primary">ファイルアップロード</button>
                    <input type="file" id="select_file" class="d-none" multiple>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>

        <hr>

        {{-- メッセージ & サムネイル表示エリア --}}
        <div class="row mt-3">
            <div class="col-12" id="message"></div>
            <div id="list_area" class="col-12"></div>
        </div>

        {{-- モーダル: ファイル選択(拡大表示) --}}
        <button type="button" id="modal_bt" class="btn btn-primary d-none" data-bs-toggle="modal"
            data-bs-target="#exampleModal"></button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ファイル選択</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p id="fname"></p>
                        <p id="pic_title"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="button" class="btn btn-info" id="set_title_bt"
                            data-bs-dismiss="modal">タイトル設定</button>
                        <button type="button" class="btn btn-danger" id="delete_bt" data-bs-dismiss="modal">削除</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ダイアログ: 削除確認 --}}
        <div id="delete_dialog" title="削除の確認" style="display:none;">
            <p class="text-center">ファイルを削除してもよろしいですか？</p>
        </div>

        {{-- ダイアログ: アップロード中 --}}
        <div id="upload_dialog" title="アップロード中" class="d-flex justify-content-center p-3" style="display:none;">
            <img src="{{ asset('images/loading.gif') }}" class="w-25 mb-2" alt="Loading">
            <p class="text-center">ファイルをアップロードしています。</p>
        </div>

        {{-- ダイアログ: タイトル設定 --}}
        <div id="dialog-form" title="タイトル設定" style="display:none;">
            <div class="mb-3">
                <label for="set_title" class="form-label">タイトルを入力</label>
                <input type="text" class="form-control" id="set_title">
            </div>
        </div>

        {{-- jQuery --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        {{-- 独自JS: Ajax + jQuery UI ダイアログ処理 --}}
        <script>
            let page_num = 0;
            let change_id = 0;

            // サムネイル一覧取得
            function changeContents(num) {
                page_num = num;
                document.body.style.cursor = 'wait';
                let token = document.querySelector('[name="_token"]').value;
                let uid = document.getElementById('u_id').textContent;
                let fd = new FormData();
                fd.append('_token', token);
                fd.append('u_id', uid);
                fd.append('page_num', num);
                $.ajax({
                    url: '/user_pics',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: res => {
                        document.body.style.cursor = 'auto';
                        $('#list_area').html(res);
                    },
                    error: () => {
                        alert('一覧取得エラー');
                        document.body.style.cursor = 'auto';
                    }
                });
            }

            // ファイルアップロード
            function uploadData(file) {
                document.body.style.cursor = 'wait';
                let token = document.querySelector('[name="_token"]').value;
                let uid = document.getElementById('u_id').textContent;
                let fd = new FormData();
                fd.append('_token', token);
                fd.append('upfile', file);
                fd.append('u_id', uid);
                $.ajax({
                    url: '/save_pics',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: res => {
                        document.body.style.cursor = 'auto';
                        $('#message').html(res);
                        changeContents(0);
                    },
                    error: () => {
                        alert('アップロード失敗');
                        document.body.style.cursor = 'auto';
                    }
                });
            }

            // 拡大表示用 Ajax
            function getMasterImage(id) {
                document.body.style.cursor = 'wait';
                let token = document.querySelector('[name="_token"]').value;
                let uid = document.getElementById('u_id').textContent;
                let fd = new FormData();
                fd.append('_token', token);
                fd.append('img_id', id);
                fd.append('u_id', uid);
                $.ajax({
                    url: '/get_master',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: res => {
                        document.body.style.cursor = 'auto';
                        $('#fname').text(id);
                        $('#pic_title').text($(res).attr('title'));
                        $('#modal_bt').trigger('click');
                    },
                    error: () => {
                        alert('拡大取得エラー');
                        document.body.style.cursor = 'auto';
                    }
                });
            }

            // ファイル削除
            function deleteFile() {
                document.body.style.cursor = 'wait';
                let token = document.querySelector('[name="_token"]').value;
                let uid = document.getElementById('u_id').textContent;
                let fd = new FormData();
                fd.append('_token', token);
                fd.append('delete_id', change_id);
                fd.append('u_id', uid);
                $.ajax({
                    url: '/delete_pic',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: () => {
                        document.body.style.cursor = 'auto';
                        changeContents(page_num);
                    },
                    error: () => {
                        alert('削除失敗');
                        document.body.style.cursor = 'auto';
                    }
                });
            }

            // タイトル保存
            function saveTitle() {
                document.body.style.cursor = 'wait';
                let token = document.querySelector('[name="_token"]').value;
                let uid = document.getElementById('u_id').textContent;
                let title = $('#set_title').val();
                let fd = new FormData();
                fd.append('_token', token);
                fd.append('save_id', change_id);
                fd.append('title', title);
                fd.append('u_id', uid);
                $.ajax({
                    url: '/save_title',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: () => {
                        document.body.style.cursor = 'auto';
                        changeContents(page_num);
                    },
                    error: () => {
                        alert('タイトル更新失敗');
                        document.body.style.cursor = 'auto';
                    }
                });
            }

            $(function() {
                // 初期一覧取得
                changeContents(0);

                // ファイルアップロード
                $('#file_up_bt').click(() => $('#select_file').trigger('click'));
                $('#select_file').change(e => {
                    $('#upload_dialog').dialog('open');
                    Array.from(e.target.files).forEach(file => uploadData(file));
                    $('#upload_dialog').dialog('close');
                });

                // サムネイルクリック → モーダル表示
                $(document).on('click', '.show_modal_bt', function() {
                    change_id = $(this).attr('value');
                    getMasterImage(change_id);
                });

                // 削除ボタン
                $('#delete_bt').click(() => $('#delete_dialog').dialog('open'));
                $('#delete_dialog').dialog({
                    autoOpen: false,
                    modal: true,
                    buttons: {
                        '削除する': function() {
                            deleteFile();
                            $(this).dialog('close');
                        },
                        'キャンセル': function() {
                            $(this).dialog('close');
                        }
                    }
                });

                // タイトル設定ボタン
                $('#set_title_bt').click(() => $('#dialog-form').dialog('open'));
                $('#dialog-form').dialog({
                    autoOpen: false,
                    modal: true,
                    width: 600,
                    buttons: {
                        '保存': function() {
                            saveTitle();
                            $(this).dialog('close');
                        },
                        'キャンセル': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        </script>

        {{-- Vite ビルド後の JS --}}
        @vite(['resources/js/app.js'])
    </x-slot>
</x-app-layout>
