{{-- resources/views/users/pic_upload.blade.php --}}
<x-app-layout>
    <x-slot name="title">写真アップロード</x-slot>

    <x-slot name="header">
        <div class="container">
            <h2 class="text-secondary py-3">写真アップロード</h2>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div class="container">
            <div class="mb-3">
                管理者 {{ Auth::user()->name }}
                <input type="hidden" id="u_id" value="{{ Auth::id() }}">
            </div>

            <div class="row mb-3">
                <div class="col-md-8">
                    <button type="button" class="btn btn-primary" id="file_up_bt">ファイルアップロード</button>
                    <input type="file" id="select_file" style="display:none;" multiple>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div id="message"></div>
                    <div id="list_area"></div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- 💡 JSは app.js に記述されているため、ここでは追加不要 --}}
</x-app-layout>
