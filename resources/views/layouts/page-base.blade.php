{{-- 
    ゲスト向け共通レイアウト（Vite構成対応）
    - Laravel 12 + Vite に準拠
    - ナビゲーションは layouts/navigation_guest.blade.php を使用
    - SP/PCの構造切り替えや @auth / @guest にも対応
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ▼ ページタイトル：個別に $title を渡せる --}}
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    {{-- ▼ Vite構成用のCSS/JS読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    {{-- ▼ ゲスト用ナビゲーション --}}
    @include('layouts.navigation_guest')

    {{-- ▼ ヘッダー表示（$header スロット） --}}
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- ▼ メインコンテンツ --}}
    <main class="py-6">
        {{ $slot }}
    </main>

    {{-- ▼ 追加スクリプトが必要な場合 --}}
    {{ $script ?? '' }}
</body>
</html>
