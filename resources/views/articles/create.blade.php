<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            新規記事投稿
        </h2>
    </x-slot>

    <div class="py-6 px-8">
        <form method="POST" action="/articles">
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700">タイトル</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label for="body" class="block font-medium text-sm text-gray-700">本文</label>
                <textarea name="body" id="body" rows="5" class="mt-1 block w-full border rounded p-2"></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">投稿</button>
        </form>
    </div>
</x-app-layout>
