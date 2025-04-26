{{-- 
    トップページビュー（ゲスト向け）
    - page-base レイアウトを使用
    - Bootstrap風クラスも追加しやすい構成
--}}

<x-page-base>
    {{-- ページタイトル --}}
    <x-slot name="title">みんなのアルバム</x-slot>

    {{-- ページメインコンテンツ --}}
    <x-slot name="slot">
        <div class="container">
            <div class="row mt-5">
                <div class="col-12">
                    <h1 class="text-center">みんなのアルバム</h1>
                </div>
            </div>

            <div class="row mt-5">
                @foreach ($image_array as $image)
                    <div class="col-sm-3 p-2">
                        <div class="card p-1">
                            <form method="post" action="/show_album">
                                @csrf
                                <input type="hidden" name="u_id" value="{{ $image['id'] }}">
                                <p class="text-center">{{ $image['user_name'] }}</p>
                                <p class="text-center">
                                    <input type="image" src="{{ $image['img'] }}" class="w-100 album pointer" value="{{ $image['id'] }}">
                                </p>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-slot>

    {{-- JavaScript用slot --}}
    <x-slot name="script">
        <script>
            // ここに必要ならJS処理を追記
        </script>
    </x-slot>
</x-page-base>