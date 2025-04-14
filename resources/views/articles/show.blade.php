<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">記事詳細</h2>
    </x-slot>

    <div class="py-6 px-8">
        <h3 class="text-2xl font-bold mb-2">{{ $article->title }}</h3>
        <p class="mb-4 text-gray-700">{{ $article->body }}</p>

        <a href="/articles" class="text-blue-500 hover:underline">← 一覧へ戻る</a>
    </div>
</x-app-layout>
