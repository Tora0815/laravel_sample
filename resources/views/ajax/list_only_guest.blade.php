{{-- resources/views/ajax/list_only_guest.blade.php --}}

{{--
    写真リスト（ゲスト用）をタブ形式で表示するための部分ビュー
    getpics() から呼び出され、$data_list, $tab_count, $page_num を受け取る
--}}

<div class="row mt-2">
    {{-- タブ切り替えボタン --}}
    <div class="col-sm-12 text-center">
        @for ($i = 0; $i < $tab_count; $i++)
            @if ($i == $page_num)
                <button type="button" class="btn btn-secondary page_bt"
                    value="{{ $i }}">{{ $i + 1 }}</button>
            @else
                <button type="button" class="btn btn-outline-secondary page_bt"
                    value="{{ $i }}">{{ $i + 1 }}</button>
            @endif
        @endfor
    </div>

    {{-- サムネイル一覧表示 --}}
    @foreach ($data_list as $list)
        <div class="col-6 col-md-2 p-2">
            <div class="text-center">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/thumb_images/' . $list->thumb_name))) }}"
                    class="w-100 show_modal_bt" value="{{ $list->id }}" title="{{ $list->title }}" />
            </div>
        </div>
    @endforeach
</div>
