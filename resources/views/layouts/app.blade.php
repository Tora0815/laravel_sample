{{-- 
    会員向け共通レイアウトテンプレート（app.blade.php）
    - Breezeの<x-app-layout> から呼び出される
    - tailwind.css + Vite + スロット構成
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    {{-- ▼ ViteによるCSS/JSの読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ▼ jQuery本体とjQuery UIを追加（順番が超大事） --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- jQuery本体 --}}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> {{-- jQueryUIのCSS --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> {{-- jQueryUI本体 --}}
</head>



<body class="font-sans antialiased bg-gray-100">
    {{-- ▼ ナビゲーション（ログイン済みユーザー用） --}}
    @include('layouts.navigation')

    {{-- ▼ ヘッダー（ページ個別でスロット指定） --}}
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- ▼ ページ本体 --}}
    <main class="py-6">
        {{ $slot }}
    </main>

    {{-- ▼ スクリプト（必要なら） --}}
    {{ $script ?? '' }}
</body>

</html>
