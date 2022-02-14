from misc import ReqHeader, Response, get_res

HOST = "blogtest.vnprogramming.com"
PORT = 80
PATH = "/wp-login.php"


class Cred:
    """Object that holds username and password"""

    def __init__(self):
        print(f"Login into {HOST}{PATH} ")
        self.user = input("username: ")  # test
        self.password = input("password: ")  # test123QWE@AD

    def cred(self):
        return self.user, self.password

    def send(self, host=HOST, path=PATH):
        """Send username and password to server"""
        header = ReqHeader.post_login(host, path, self.cred())
        res = get_res(header)
        return res


def redirect(res_header: classmethod(Response)) -> str:
    """
    Follow redirection
    :param res_header: Response's header
    :return: 
    """
    cookie = None
    res_code = res_header.code()
    while res_code[0] == "3":
        cookie = res_header.cookie()
        if "wordpress_logged_in_" in cookie:
            return cookie
        location = res_header.location()

        req_header = ReqHeader.get(cookie, location.hostname, location.path)
        res_header = Response(get_res(req_header, location.hostname))

        res_code = res_header.code()

    return cookie


def login() -> str or None:
    """
    Process to login
    :return: cookie
    """
    credential = Cred()
    header_recv = Response(credential.send())

    cookie = redirect(header_recv)

    if cookie and "wordpress_logged_in_" in cookie:
        return cookie, credential.user

    return None, credential.user


def main():
    check, user = login()
    if check is None:
        print(f"User {user} đăng nhập thất bại")
    else:
        print(f"User {user} đăng nhập thành công")


if __name__ == '__main__':
    main()
