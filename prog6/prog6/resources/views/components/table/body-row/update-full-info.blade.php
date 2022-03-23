@props(['user','placeholder'=>$user,'disabled'=>true])
<x-table.body-row.update-basic-info :user="$user" :placeholder="$placeholder" :disabled="$disabled"/>
<td>
    <x-input class="w-full" type="text" name="username" :disabled="$disabled"
             :value="$user['username']" :placeholder="$placeholder['username']"
             :form="'form_user_'.$user['uid']"/>
</td>
<td>
    <x-input class="w-full" type="password" name="password" :disabled="$disabled"
             :placeholder="__('fields.password')" :form="'form_user_'.$user['uid']"/>
</td>
