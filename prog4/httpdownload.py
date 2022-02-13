from misc import Response, get_res
from urllib.parse import urlparse

HOST = "blogtest.vnprogramming.com"
PORT = 80

print(f"Download file from {HOST}")
url = input("Path to file: ")
path = urlparse(url).path

payload = (f"GET {path} HTTP/1.1\r\n"
           f"Host: {HOST}\r\n"
           f"Connection: close\r\n\r\n")

res = Response(get_res(payload.encode(), raw=True))

if res.code() != "200" or "image" not in res.type():
    print("Không tồn tại file ảnh")
    exit(-1)

save_location = input("Save to: ")
with open(save_location, "wb") as image:
    image.write(res.body())

print(f"Kích thước file ảnh: {res.length()}")
