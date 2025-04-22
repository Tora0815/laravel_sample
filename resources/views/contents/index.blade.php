{{-- 
    トップページビュー（ゲスト向け）
    - page-base レイアウトを使用
    - Bootstrap風クラスも追加しやすい構成
--}}

<x-page-base>
    <x-slot name="title">ホーム</x-slot>

    <x-slot name="slot">
        <div class="container mx-auto px-4">
            <h1 class="text-xl font-semibold text-gray-800">サンプルページ</h1>
        </div>
    </x-slot>

    <x-slot name="script">
        {{-- 必要ならJSをここに --}}
    </x-slot>
</x-page-base>
