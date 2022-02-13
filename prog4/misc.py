import re

HOST = "blogtest.vnprogramming.com"
PORT = 80
PATH_LOGIN = "/wp-login.php"
RECV_BUF = 1024


class ReqHeader:
    @staticmethod
    def get(cookie: str, host=HOST, path=PATH_LOGIN) -> str:
        """Generate GET request's header"""
        return (f"GET {path} HTTP/1.1\r\n"
                f"Cookie: {cookie}\r\n"
                f"Host: {host}\r\n"
                f"Connection: close\r\n\r\n")

    @staticmethod
    def post_form(cookie: str, boundary: str, length: int, path: str) -> str:
        """Generate POST request's header with form-data"""
        return (f"POST {path} HTTP/1.1\r\n"
                f"Host: blogtest.vnprogramming.com\r\n"
                f"Content-Length: {length}\r\n"
                f"Content-Type: multipart/form-data; boundary={boundary}\r\n"
                f"Cookie: {cookie}\r\n"
                f"Connection: close\r\n\r\n")

    @staticmethod
    def post_login(host, path, credential) -> str:
        """Generate POST request's header with x-www-form-urlencoded"""
        user, password = credential
        body = (f"log={user}&pwd={password}"
                f"&wp-submit=Log+In&redirect_to=http%3A%2F%2Fblogtest.vnprogramming.com%2Fwp-admin%2F&testcookie=1")
        return (f"POST {path} HTTP/1.1\r\n"
                f"Host: {host}\r\n"
                f"Content-Length: {len(body)}\r\n"
                f"Content-Type: application/x-www-form-urlencoded\r\n"
                f"Cookie: wordpress_test_cookie=WP%20Cookie%20check\r\n"
                f"Connection: close\r\n\r\n"
                + body)


class Response:
    """Object that holds response's data"""

    def __init__(self, data: str or bytes):
        if type(data).__name__ == "str":
            self.res_header, self.res_body = tuple(data.split("\r\n\r\n", 1))
            self.res_header += "\r\n\r\n"
        else:
            self.res_header, self.res_body = tuple(data.split(b"\r\n\r\n", 1))
            self.res_header = self.res_header.decode() + "\r\n\r\n"

    def header(self) -> str:
        """:return response's header"""
        return self.res_header

    def body(self) -> str or bytes:
        """:return response's body"""
        return self.res_body

    def re(self, string1, string2=""):
        match = re.findall(fr"^{string1}: (.*){string2}.*\r$", self.res_header, flags=re.MULTILINE)
        return match

    def length(self):
        """Get Content-Length"""
        length = self.re("Content-Length")
        return length[0] if length else None

    def cookie(self):
        """Get Set-Cookie"""
        cookie_list = "; ".join(self.re("Set-Cookie", ";"))
        return cookie_list

    def location(self):
        """Get redirect location"""
        from urllib.parse import urlparse
        location = self.re("Location")
        return urlparse(location[0]) if location else None

    def code(self):
        """Get response code"""
        res_code = re.findall(r"^HTTP/1\.1 (\d\d\d) .*\r$", self.res_header, flags=re.MULTILINE)
        return res_code[0] if res_code else None

    def type(self):
        """Get Content-Type"""
        content_type = self.re("Content-Type")
        return content_type[0] if type else None


# class ResHeader:
#     """Object that holds response's header data"""
#
#     def __init__(self, res_header):
#         self.res_header = res_header
#
#     def re(self, string1, string2=""):
#         match = re.findall(fr"^{string1}: (.*){string2}.*\r$", self.res_header, flags=re.MULTILINE)
#         return match
#
#     def length(self):
#         """Get Content-Length"""
#         length = self.re("Content-Length")
#         return length[0] if length else None
#
#     def cookie(self):
#         """Get Set-Cookie"""
#         cookie_list = "; ".join(self.re("Set-Cookie", ";"))
#         return cookie_list
#
#     def location(self):
#         """Get redirect location"""
#         from urllib.parse import urlparse
#         location = self.re("Location")
#         return urlparse(location[0]) if location else None
#
#     def code(self):
#         """Get response code"""
#         res_code = re.findall(r"^HTTP/1\.1 (\d\d\d) .*\r$", self.res_header, flags=re.MULTILINE)
#         return res_code[0] if res_code else None
#
#     def type(self):
#         """Get Content-Type"""
#         content_type = self.re("Content-Type")
#         return content_type[0] if type else None
#
#     def __str__(self):
#         return self.res_header


def get_res(req: bytes or str, host=HOST, port=PORT, raw=False) -> str or bytes or None:
    """
    Send request and receive response from server
    :param req: request to send to server
    :param host:
    :param port:
    :param raw: control return type (True -> return bytes, else return str)
    :return: response from server
    """
    import socket

    req = req.encode() if type(req).__name__ == "str" else req
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.connect((host, port))
        s.sendall(req)
        res = recvall(s)
        if not res:
            return None
    return res if raw else res.decode()


def recvall(s) -> bytes:
    """Receive complete response from server """
    buf = b''
    chunk = s.recv(RECV_BUF)
    while len(chunk) != 0:
        buf += chunk
        chunk = s.recv(RECV_BUF)
    return buf
