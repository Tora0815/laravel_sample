{{--
    resources/views/users/profile.blade.php
    ----------------------------------------
    ユーザーのプロフィール編集画面
    - 登録済みユーザーの氏名・メール・住所・電話番号を表示・編集
    - Breeze 認証済みユーザー専用
    - 初回登録時は必須情報（氏名・メール）のみ表示
    - 住所自動補完ライブラリ（yubinbango.js）対応
    - 更新・キャンセル操作後はダッシュボードへリダイレクト
--}}

<x-app-layout>
    {{-- ページタイトル：ブラウザタブや SEO 用に設定 --}}
    <x-slot name="title">プロフィール編集</x-slot>

    {{-- ヘッダー部分：画面上部のタイトルエリア --}}
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-12 p-3">
                    {{-- h2 はアクセシビリティのために適切な見出し階層で --}}
                    <h2 class="text-secondary">登録情報編集</h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- メインコンテンツ：フォーム本体 --}}
    <x-slot name="slot">
        <div class="container">
            {{-- 郵便番号自動補完ライブラリ --}}
            <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

            {{-- プロフィール更新フォーム --}}
            <form method="POST" action="{{ route('profile.update') }}" class="row g-3 h-adr pt-3">
                @csrf {{-- CSRF トークン --}}
                <span class="p-country-name" style="display:none;">Japan</span>

                {{-- ユーザー ID は hidden で受け渡し --}}
                <input type="hidden" name="u_id" value="{{ $master_data->id }}">

                {{-- サブデータ（住所・電話など）が登録済みか判定 --}}
                @if(count($sub_data) > 0)
                    {{-- 氏名 --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_name">名前</label>
                            <input type="text" class="form-control" id="u_name" name="u_name"
                                value="{{ $master_data->name }}">
                        </div>
                    </div>

                    {{-- メール（読み取り専用） --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_mail">メールアドレス</label>
                            <input type="text" class="form-control" id="u_mail" name="u_mail"
                                value="{{ $master_data->email }}" readonly>
                        </div>
                    </div>

                    {{-- 郵便番号 --}}
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="u_yubin">郵便番号 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-postal-code" maxlength="8"
                                id="u_yubin" name="u_yubin" value="{{ $sub_data[0]->yubin }}" required>
                        </div>
                    </div>

                    {{-- 都道府県 --}}
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="u_jusho1">都道府県 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-region" id="u_jusho1" name="u_jusho1"
                                value="{{ $sub_data[0]->jusho1 }}" required>
                        </div>
                    </div>

                    {{-- 市区町村・番地 --}}
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="u_jusho2">市区町村・番地 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-street-address" id="u_jusho2"
                                name="u_jusho2" value="{{ $sub_data[0]->jusho2 }}" required>
                        </div>
                    </div>

                    {{-- 建物名（任意） --}}
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="u_jusho3">建物名</label>
                            <input type="text" class="form-control" id="u_jusho3" name="u_jusho3"
                                value="{{ $sub_data[0]->jusho3 }}">
                        </div>
                    </div>

                    {{-- 電話番号 --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="u_tel">電話番号 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="u_tel" name="u_tel"
                                value="{{ $sub_data[0]->tel }}" required>
                        </div>
                    </div>

                    {{-- 備考（任意） --}}
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="u_biko">備考</label>
                            <input type="text" class="form-control" id="u_biko" name="u_biko"
                                value="{{ $sub_data[0]->biko }}">
                        </div>
                    </div>
                @else
                    {{-- 初回登録時：住所未登録時は名前・メールのみ --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_name">名前</label>
                            <input type="text" class="form-control" id="u_name" name="u_name"
                                value="{{ $master_data->name }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_mail">メールアドレス</label>
                            <input type="text" class="form-control" id="u_mail" name="u_mail"
                                value="{{ $master_data->email }}" readonly>
                        </div>
                    </div>
                @endif

                {{-- アクションボタン --}}
                <div class="col-sm-12 text-center p-3 pb-5">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary" name="change" value="change">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </x-slot>

    {{-- ページ固有スクリプト --}}
    <x-slot name="script">
        <script>
            // TODO: 住所自動補完以外のスクリプトはここにまとめて管理
        </script>
    </x-slot>
</x-app-layout>
