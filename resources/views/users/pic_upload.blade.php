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

    <x-slot name="slot">
        @csrf

        {{-- ユーザーID表示 (静的にhidden) --}}
        <div id="u_id" class="hidden">{{ Auth::user()->name }}</div>

        {{-- 写真アップロードフォーム --}}
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center">写真アップロード</h3>
                </div>
            </div>

            <div class="row mt-5 pb-3">
                <div class="col-md-4">&nbsp;</div>

                {{-- ファイルアップロードボタン --}}
                <div class="col-md-4 d-grid">
                    <button type="button" id="file_up_bt" class="btn btn-primary"> ファイルアップロード </button>
                    <input type="file" id="select_file" style="display: none" multiple>
                    <input type="file" id="change_file" style="display: none">
                </div>

                <div class="col-md-4">&nbsp;</div>
            </div>
        </div>

        <hr>

        {{-- 写真一覧表示 --}}
        <div class="row mt-3">
            <div class="col-12" id="message"></div>
            <div id="list_area"></div>
        </div>

        {{-- モーダル表示部 --}}
        <button type="button" id="modal_bt" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display: none">
            Launch demo modal
        </button>

        {{-- 写真選択用モーダル --}}
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
            <p>ファイルを削除してよいですか？</p>
        </div>

        {{-- アップロード中表示 --}}
        <div id="upload_dialog" title="ファイルアップロード中" class="d-flex justify-content-center p-3">
            <img src="{{ asset('images/loading.gif') }}" class="w-25">
        </div>

        {{-- タイトル変更用ダイアログ --}}
        <div id="dialog-form" title="タイトル変更">
            <div class="mb-3">
                <label for="set_title" class="form-label">タイトルを入力</label>
                <input type="text" class="form-control" id="set_title">
            </div>
        </div>

        {{-- ここからJavaScript記述 --}}
        @vite(['resources/js/app.js'])

    </x-slot>
</x-app-layout>
