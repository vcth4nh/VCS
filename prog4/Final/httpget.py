from misc import get_res
from bs4 import BeautifulSoup

HOST = "blogtest.vnprogramming.com"
PORT = 80
PATH = "/"

req_header = (f"GET {PATH} HTTP/1.1\r\n"
              f"Host: {HOST}\r\n"
              f"Connection: close\r\n\r\n")

res = get_res(req_header)

soup = BeautifulSoup(res, 'html.parser')
print(f"Title: {soup.title.string}")
