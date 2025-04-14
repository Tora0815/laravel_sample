<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            記事一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($articles as $article)
                <div class="border-b py-4">
                    <h3 class="text-xl font-bold">
                        <a href="/articles/{{ $article->id }}" class="text-blue-600 hover:underline">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-600">{{ Str::limit($article->body, 100) }}</p>

                    <div class="mt-2">
                        <a href="/articles/{{ $article->id }}" class="text-blue-500 hover:underline mr-3">詳細</a>
                        <a href="/articles/{{ $article->id }}/edit" class="text-green-500 hover:underline">編集</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
