<div id='avatar'>
    <h2 class="mb-0">Avatar</h2>
    <img src='' alt="avatar" class='avatar'/>
    <div class="inline-block align-top">
        <form action="" method="post" class="mb-0" enctype="multipart/form-data">
            @csrf
            <p class="my-0"><b>Thay ảnh đại diện mới</b></p>
            <x-input type="file" name="file" id="upload-avatar"/>
            <x-button type="submit" name="upload_ava" value="upload_ava" class="small-btn">Tải lên</x-button>
        </form>
        {{--        <p class="error"><?php upload_noti(AVATAR_FOLDER) ?></p>--}}
    </div>
</div>
