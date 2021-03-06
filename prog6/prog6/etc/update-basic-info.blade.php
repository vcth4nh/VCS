@props(['user','placeholder'=>$user,'disabled'=>true])
<input type="hidden" name="uid" value="{{$user['uid']}}" form="form_user_{{$user['uid']}}"/>
<td>
    <x-input class="w-full" type="text" name="fullname" :disabled="$disabled"
             :value="$user['fullname']" :placeholder="$placeholder['fullname']" :form="'form_user_'.$user['uid']"/>
</td>
<td>
    <x-input class="w-full" type="text" name="phone" :disabled="$disabled"
             :value="$user['phone']" :placeholder="$placeholder['phone']" :form="'form_user_'.$user['uid']"/>
</td>
<td>
    <x-input class="w-full" type="text" name="email" :disabled="$disabled"
             :value="$user['email']" :placeholder="$placeholder['email']" :form="'form_user_'.$user['uid']"/>
</td>
