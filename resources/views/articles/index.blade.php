<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            記事一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($articles as $article)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4">
                    <h3 class="text-lg font-bold">
                        <a href="{{ url("/articles/{$article->id}/edit") }}" class="text-blue-500 hover:underline">
                            {{ $article->title }}
                        </a>
                    </h3>


                    <p>{{ $article->body }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
