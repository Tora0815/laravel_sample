<div class="row mt-2">
    <div class="col-sm-12 text-center">
        {{-- ページネーションボタンの描画 --}}
        @for ($i = 0; $i < $tab_count; $i++)
            @if ($i == $page_num)
                {{-- 現在ページは強調（btn-secondary） --}}
                <button type="button" class="btn btn-secondary page_bt" value="{{ $i }}">{{ $i + 1 }}</button>
            @else
                {{-- 他のページは薄いボタン（btn-outline） --}}
                <button type="button" class="btn btn-outline-secondary page_bt" value="{{ $i }}">{{ $i + 1 }}</button>
            @endif
        @endfor
    </div>
</div>

{{-- 一覧の画像表示領域 --}}
<div class="row">
    @foreach ($data_list as $list)
        <div class="col-md-6 col-md-2 p-2">
            {{-- モーダルを開く用のボタン（画像クリック） --}}
            <img src="{{ $list['img'] }}" class="w-100 show_modal_bt" 
                value="{{ $list['id'] }}" title="{{ $list['title'] }}">
        </div>
    @endforeach
</div>

{{-- ボタンを下部にも配置（ページ送り） --}}
<div class="row">
    <div class="col-sm-12 text-center pb-3">
        @for ($i = 0; $i < $tab_count; $i++)
            <button type="button" class="btn btn-outline-secondary page_bt" value="{{ $i }}">{{ $i + 1 }}</button>
        @endfor
    </div>
</div>
