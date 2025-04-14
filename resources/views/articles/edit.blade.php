<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            記事を編集する
        </h2>
    </x-slot>

    <div class="py-6 px-8">
        <form method="POST" action="/articles/{{ $article->id }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700">タイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}"
                    class="mt-1 block w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label for="body" class="block font-medium text-sm text-gray-700">本文</label>
                <textarea name="body" id="body" rows="5" class="mt-1 block w-full border rounded p-2">{{ old('body', $article->body) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">更新</button>


            <a href="/articles" class="ml-4 text-gray-500 hover:underline">戻る</a>
        </form>
    </div>
</x-app-layout>
