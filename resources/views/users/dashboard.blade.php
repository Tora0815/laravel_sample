{{-- 
    会員ページ：ダッシュボード
    ────────────────────────────────
    ・ログイン後に /dashboard で表示
    ・app.blade.php の共通レイアウトを <x-app-layout> で利用
--}}

<x-app-layout>
    {{-- ▼ ページタイトル --}}
    <x-slot name="title">
        マイページ
    </x-slot>

    {{-- ▼ ページヘッダー --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    {{-- ▼ ページ本文 --}}
    <x-slot name="slot">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You are logged in!
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ▼ 必要なら追加スクリプト --}}
    <x-slot name="script">
        {{-- ここにページ固有のJSを入れる --}}
    </x-slot>
</x-app-layout>
