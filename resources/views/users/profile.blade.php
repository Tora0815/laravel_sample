{{-- 
    resources/views/users/profile.blade.php
    ----------------------------------------
    ユーザーのプロフィール編集画面
    - Breeze認証済みユーザー専用
    - Bootstrap5対応でレイアウト調整
--}}

<x-app-layout>
    {{-- ページタイトル --}}
    <x-slot name="title">プロフィール編集</x-slot>

    {{-- ヘッダー --}}
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-12 py-3">
                    <h2 class="text-secondary">登録情報編集</h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- メインコンテンツ --}}
    <x-slot name="slot">
        <div class="container py-5">
            {{-- 郵便番号自動補完ライブラリ --}}
            <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="{{ route('profile.update') }}" class="h-adr">
                        @csrf
                        @method('PATCH')
                        <span class="p-country-name" style="display:none;">Japan</span>

                        <input type="hidden" name="u_id" value="{{ $master_data->id }}">

                        <div class="mb-3">
                            <label for="u_name" class="form-label">名前</label>
                            <input type="text" class="form-control" id="u_name" name="u_name" value="{{ old('u_name', $master_data->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="u_mail" class="form-label">メールアドレス</label>
                            <input type="email" class="form-control" id="u_mail" name="u_mail" value="{{ $master_data->email }}" readonly>
                        </div>

                        @if(count($sub_data) > 0)
                            <div class="mb-3">
                                <label for="u_yubin" class="form-label">郵便番号 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control p-postal-code" id="u_yubin" name="u_yubin" value="{{ old('u_yubin', $sub_data[0]->yubin) }}" maxlength="8" required>
                            </div>

                            <div class="mb-3">
                                <label for="u_jusho1" class="form-label">都道府県 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control p-region" id="u_jusho1" name="u_jusho1" value="{{ old('u_jusho1', $sub_data[0]->jusho1) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="u_jusho2" class="form-label">市区町村・番地 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control p-street-address" id="u_jusho2" name="u_jusho2" value="{{ old('u_jusho2', $sub_data[0]->jusho2) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="u_jusho3" class="form-label">建物名（任意）</label>
                                <input type="text" class="form-control" id="u_jusho3" name="u_jusho3" value="{{ old('u_jusho3', $sub_data[0]->jusho3) }}">
                            </div>

                            <div class="mb-3">
                                <label for="u_tel" class="form-label">電話番号 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="u_tel" name="u_tel" value="{{ old('u_tel', $sub_data[0]->tel) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="u_biko" class="form-label">備考（任意）</label>
                                <input type="text" class="form-control" id="u_biko" name="u_biko" value="{{ old('u_biko', $sub_data[0]->biko) }}">
                            </div>
                        @endif

                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-3">キャンセル</a>
                            <button type="submit" class="btn btn-primary">更新</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </x-slot>

    {{-- 固有スクリプト --}}
    <x-slot name="script">
        <script>
            // 住所自動補完など必要ならここに
        </script>
    </x-slot>
</x-app-layout>
