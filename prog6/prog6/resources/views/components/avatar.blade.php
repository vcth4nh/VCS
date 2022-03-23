<div id='avatar'>
    <h2 class="mb-0">Avatar</h2>
{{--    @if($avatar)--}}
        <img src='{{storage_path('app/tuPZ9FPq3ODa5K07EXkEWx81qHN9RprepEnJ4mQo.webp')}}' alt="avatar"/>
{{--    @else--}}
        <h4>Chưa có ảnh đại diện</h4>
{{--    @endif--}}
    <div class="inline-block align-top">
        <form action="{{ route('avatar.store') }}" method="post" class="mb-0" enctype="multipart/form-data">
            @csrf
            <p class="my-0"><b>Thay ảnh đại diện mới</b></p>
            <x-input type="file" name="avatar" id="upload-avatar"/>
            <x-button type="submit" name="upload_ava" value="upload_ava" class="small-btn">Tải lên</x-button>
        </form>
        {{--        <p class="error"><?php upload_noti(AVATAR_FOLDER) ?></p>--}}
    </div>
</div>
