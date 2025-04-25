{{--
    ユーザーのプロフィール編集画面
    - Breeze認証済みユーザー専用
    - Bootstrapレイアウト対応
--}}

<x-app-layout>
    <x-slot name="title">プロフィール編集</x-slot>

    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-12 p-3">
                    <h2 class="text-secondary">登録情報編集</h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div class="container">
            <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

            <form method="POST" action="{{ route('profile.update') }}" class="row g-3 h-adr pt-3">
                @csrf
                @method('PATCH')
                <span class="p-country-name" style="display:none;">Japan</span>

                {{-- ユーザーID hidden --}}
                <input type="hidden" name="u_id" value="{{ $master_data->id }}">

                @if (count($sub_data) > 0)
                    {{-- 氏名 --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_name">名前</label>
                            <input type="text" class="form-control" id="u_name" name="u_name"
                                value="{{ old('u_name', $master_data->name) }}">
                        </div>
                    </div>

                    {{-- メールアドレス --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_mail">メールアドレス</label>
                            <input type="email" class="form-control" id="u_mail" name="u_mail"
                                value="{{ $master_data->email }}" readonly>
                        </div>
                    </div>

                    {{-- 郵便番号 --}}
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="u_yubin">郵便番号 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-postal-code" maxlength="8"
                                id="u_yubin" name="u_yubin"
                                value="{{ old('u_yubin', $sub_data[0]->yubin) }}" required>
                        </div>
                    </div>

                    {{-- 都道府県 --}}
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="u_jusho1">都道府県 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-region" id="u_jusho1" name="u_jusho1"
                                value="{{ old('u_jusho1', $sub_data[0]->jusho1) }}" required>
                        </div>
                    </div>

                    {{-- 市区町村・番地 --}}
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="u_jusho2">市区町村・番地 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control p-street-address" id="u_jusho2" name="u_jusho2"
                                value="{{ old('u_jusho2', $sub_data[0]->jusho2) }}" required>
                        </div>
                    </div>

                    {{-- 建物名 --}}
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="u_jusho3">建物名</label>
                            <input type="text" class="form-control" id="u_jusho3" name="u_jusho3"
                                value="{{ old('u_jusho3', $sub_data[0]->jusho3) }}">
                        </div>
                    </div>

                    {{-- 電話番号 --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="u_tel">電話番号 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="u_tel" name="u_tel"
                                value="{{ old('u_tel', $sub_data[0]->tel) }}" required>
                        </div>
                    </div>

                    {{-- 備考 --}}
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="u_biko">備考</label>
                            <input type="text" class="form-control" id="u_biko" name="u_biko"
                                value="{{ old('u_biko', $sub_data[0]->biko) }}">
                        </div>
                    </div>
                @else
                    {{-- 初回登録（氏名・メールのみ） --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_name">名前</label>
                            <input type="text" class="form-control" id="u_name" name="u_name"
                                value="{{ old('u_name', $master_data->name) }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="u_mail">メールアドレス</label>
                            <input type="email" class="form-control" id="u_mail" name="u_mail"
                                value="{{ $master_data->email }}" readonly>
                        </div>
                    </div>
                @endif

                {{-- ボタン --}}
                <div class="col-12 text-center p-3 pb-5">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">キャンセル</a>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
        </div>
    </x-slot>

    <x-slot name="script">
        <script>
            // 住所自動補完（yubinbango）以外のJSをここに
        </script>
    </x-slot>
</x-app-layout>
