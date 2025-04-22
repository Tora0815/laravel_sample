<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- ▼ PCサイズのナビゲーションバー（中央寄せ・ロゴ＋リンク群） -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- サイトロゴ -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('index') }}">
                        <x-application-logo class="block h-10 w-10 fill-current text-gray-600" />
                    </a>
                </div>

                <!-- ナビリンク（PC時のみ表示） -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    {{-- 各ページへのリンク --}}
                    <x-nav-link href="{{ route('index') }}" :active="request()->routeIs('index')">ホーム</x-nav-link>
                    <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">サイトについて</x-nav-link>
                    <x-nav-link href="{{ route('kojin') }}" :active="request()->routeIs('kojin')">個人情報の取扱について</x-nav-link>
                    <x-nav-link href="{{ route('inquiry') }}" :active="request()->routeIs('inquiry')">お問い合わせ</x-nav-link>
                </div>
            </div>

            <!-- ログイン状態による切り替え -->
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        {{-- ログイン済み → マイページへ --}}
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">マイページ</a>
                    @else
                        {{-- 未ログイン時のナビ --}}
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">ログイン</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">登録</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>

    <!-- ▼ ハンバーガーメニュー（SPサイズ時の表示） -->
    <div class="-mr-2 flex items-center sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
            {{-- ハンバーガーアイコン（2本線 or × の切替） --}}
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- ▼ SP時のドロワーナビメニュー -->
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- SP用ナビリンク（PCと同じ内容） --}}
            <x-responsive-nav-link href="{{ route('index') }}" :active="request()->routeIs('index')">ホーム</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">サイトについて</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('kojin') }}" :active="request()->routeIs('kojin')">個人情報の取扱について</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('inquiry') }}" :active="request()->routeIs('inquiry')">お問い合わせ</x-responsive-nav-link>

            @if (Route::has('login'))
                @auth
                    <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">マイページ</x-responsive-nav-link>
                @else
                    <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">ログイン</x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">登録</x-responsive-nav-link>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
