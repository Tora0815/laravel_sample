{{--
    写真アップロードページ本体ビュー
    ・ログインユーザー専用（auth ミドルウェア適用済み）
    ・ユーザーIDをhiddenで保持し、Ajax通信に使用
    ・画像アップロード → サムネイル表示 → ページネーション含む非同期更新
    ・JavaScript（resources/js/app.js）と連携
--}}

<x-app-layout>
    {{-- ページタイトル（ヘッダーの <title> に反映） --}}
    <x-slot name="title">写真アップロード</x-slot>

    {{-- ページヘッダー部分 --}}
    <x-slot name="header">
        <div class="container">
            <h2 class="text-secondary py-3">写真アップロード</h2>
        </div>
    </x-slot>

    {{-- メインコンテンツ部分 --}}
    <x-slot name="slot">
        <div class="container">
            <div class="mb-3">
                管理者 {{ Auth::user()->name }}
                {{-- ログインユーザーID（JS側でAjax送信時に使用） --}}
                <input type="hidden" id="u_id" value="{{ Auth::id() }}">
            </div>

            {{-- アップロードボタンとファイル選択エリア --}}
            <div class="row mb-3">
                <div class="col-md-8">
                    {{-- ファイル選択をトリガーする表示用ボタン --}}
                    <button type="button" class="btn btn-primary" id="file_up_bt">ファイルアップロード</button>
                    {{-- 実際のinput[type=file]（非表示／multiple対応） --}}
                    <input type="file" id="select_file" style="display:none;" multiple>
                </div>
            </div>

            {{-- サムネイルリストおよびメッセージ出力領域 --}}
            <div class="row">
                <div class="col-12">
                    {{-- エラーメッセージ／成功メッセージ表示用 --}}
                    <div id="message"></div>

                    {{-- サムネイル表示／ページネーション含むエリア（Ajax差し替え対象） --}}
                    <div id="list_area"></div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- JavaScriptは app.js に記述済み（個別スクリプト不要） --}}
</x-app-layout>