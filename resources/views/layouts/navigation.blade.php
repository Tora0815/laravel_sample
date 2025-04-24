{{--
    resources/views/layouts/navigation.blade.php
    ──────────────────────────────────────────────
    アプリ共通のヘッダー（ナビゲーション）を定義するBladeテンプレート。
    ・ログイン済みユーザー向けのメニュー（マイページ／写真アップロード）
    ・プロフィール／ログアウト用ドロップダウン
    ・画面幅に応じてPC／モバイル表示を切り替え
    ※ Breeze の <x-app-layout> 内で @include('layouts.navigation') して使用
--}}

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    {{-- ──────────────────────────────────── --}}
    {{-- Primary Navigation Menu (PC/大画面) --}}
    {{-- ──────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- 左側︰ロゴ＆PC向けリンク --}}
            <div class="flex">
                {{-- Logo（クリックするとマイページへ） --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                {{-- Navigation Links（PC表示時のみ） --}}
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    {{-- マイページへのリンク --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('マイページ') }}
                    </x-nav-link>
                    {{-- 写真アップロードページへのリンク --}}
                    <x-nav-link :href="route('pic_upload')" :active="request()->routeIs('pic_upload')">
                        {{ __('写真アップロード') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- 右側︰設定ドロップダウン（PC表示時のみ） --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    {{-- ドロップダウンのトリガーボタン --}}
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium
                    rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none
                    transition ease-in-out duration-150">
                            {{-- ログインユーザー名を表示 --}}
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                {{-- 矢印アイコン --}}
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1
                                    1 0 111.414 1.414l-4 4a1 1 0
                                    01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    {{-- ドロップダウンメニュー本体 --}}
                    <x-slot name="content">
                        {{-- プロフィール編集 --}}
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('プロフィール') }}
                        </x-dropdown-link>
                        {{-- ログアウト --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('ログアウト') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- ハンバーガーメニュー（モバイル表示時のみ） --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md
                        text-gray-400 hover:text-gray-500 hover:bg-gray-100
                        focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        {{-- メニュー開閉アイコン切り替え --}}
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ──────────────────────────────────── --}}
    {{-- Responsive Navigation Menu (モバイル時のスライドメニュー) --}}
    {{-- ──────────────────────────────────── --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        {{-- リンク一覧 --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('マイページ') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pic_upload')" :active="request()->routeIs('pic_upload')">
                {{ __('写真アップロード') }}
            </x-responsive-nav-link>
        </div>
        {{-- プロフィール／メールアドレス／ログアウト --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                {{-- モバイルメニュー内のプロフィール --}}
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('プロフィール') }}
                </x-responsive-nav-link>
                {{-- ログアウト --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('ログアウト') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
