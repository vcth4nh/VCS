@props(['user','placeholder'=>$user,'disabled'=>true])
<input type="hidden" name="uid" value="{{$user['uid']}}" form="form_user_{{$user['uid']}}"/>
<td>
    <x-input class="w-full" type="text" name="fullname" :disabled="$disabled"
             :value="$user['fullname']" :placeholder="$placeholder['fullname']" :form="'form_user_'.$user['uid']"/>
</td>
<td class="w-[15%]">
    <x-input class="w-full" type="text" name="phone" :disabled="$disabled"
             :value="$user['phone']" :placeholder="$placeholder['phone']" :form="'form_user_'.$user['uid']"/>
</td>
<td class="w-[17%]">
    <x-input class="w-full" type="text" name="email" :disabled="$disabled"
             :value="$user['email']" :placeholder="$placeholder['email']" :form="'form_user_'.$user['uid']"/>
</td>
<td class="w-[10%]">
    <x-input class="w-full" type="text" name="username" :disabled="$disabled"
             :value="$user['username']" :placeholder="$placeholder['username']"
             :form="'form_user_'.$user['uid']"/>
</td>
<td class="w-[20%]">
    <x-input class="w-full" type="password" name="password" :disabled="$disabled"
             :placeholder="__('fields.password')" :form="'form_user_'.$user['uid']"/>
</td>
