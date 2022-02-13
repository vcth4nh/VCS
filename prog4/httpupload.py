from httppost import ReqHeader, get_res, login

HOST = "blogtest.vnprogramming.com"
PORT = 80
PATH = "/wp-admin/media-new.php"
PATH_UPLOAD = "/wp-admin/async-upload.php"
PATH_ADMIN = "/wp-admin/admin-ajax.php"


def failed() -> None:
    import sys
    print("Upload failed")
    sys.exit(-1)


class Gen:
    @staticmethod
    def boundary() -> str:
        import random
        import string
        return '----WebKitFormBoundary' + ''.join(random.sample(string.ascii_letters + string.digits, 16))

    @staticmethod
    def body_upload(boundary: str, form_dict: dict) -> bytes or None:
        """
        Generate body part to upload image
        :param form_dict: type dict, include 'post_id' and '_wpnonce'
        :param boundary: boundary defined in header
        :return: message's body to upload image
        """
        body = []
        file_name = input("File location: ")
        image_form = Gen.image_form(boundary, file_name)
        if not image_form:
            return None

        name = (f"--{boundary}\r\n"
                f"Content-Disposition: form-data; name=\"name\"\r\n\r\n"
                f"{file_name}\r\n")
        body.append(name.encode())

        for key, value in form_dict.items():
            chunk = (f"--{boundary}\r\n"
                     f"Content-Disposition: form-data; name=\"{key}\"\r\n\r\n"
                     f"{value}\r\n")
            body.append(chunk.encode())

        body.extend(image_form)
        body = b"".join(body)

        return body

    @staticmethod
    def image_form(boundary: str, file_name: str) -> list or None:
        """
        Generate image form in message's body
        :param boundary: boundary defined in header
        :param file_name: file name
        :return: image form in message's body
        """
        image = [None, None, None]
        image[0] = (f"--{boundary}\r\n"
                    f"Content-Disposition: form-data; name=\"async-upload\"; filename=\"{file_name}\"\r\n"
                    f"Content-Type: {file_type(file_name)}\r\n\r\n").encode()
        try:
            with open(file_name, 'rb') as file:
                image[1] = file.read()
        except IOError:
            return None
        image[2] = f"\r\n--{boundary}--\r\n".encode()

        return image

    @staticmethod
    def body_query(boundary: str, file_id: int) -> str:
        """
        Generate body part of message to get uploaded image's url
        :param boundary: boundary defined in header
        :param file_id: uploaded image's id, taken from server's response after a successful image upload
        :return: message's body to get uploaded image's url
        """
        body = (f"--{boundary}\r\n"
                f"Content-Disposition: form-data; name=\"action\"\r\n\r\n"
                f"get-attachment\r\n"
                f"--{boundary}\r\n"
                f"Content-Disposition: form-data; name=\"id\"\r\n\r\n"
                f"{file_id}\r\n"
                f"--{boundary}--\r\n")

        return body


class Parse:
    @staticmethod
    def wpnonce(res: str) -> dict or None:
        """
        Parse response to get '_wpnonce' (compulsory) and 'post_id'
        :param res: response from server
        :return: dictionary including '_wpnonce', 'post_id' and their value
        """
        from bs4 import BeautifulSoup
        soup = BeautifulSoup(res, 'html.parser')
        soup = soup.body.find_all('input', attrs={"type": "hidden"})
        if not soup:
            return None

        form_dict = {}
        for item in soup:
            if "post_id" in str(item):
                form_dict["post_id"] = item.get("value")
            if "_wpnonce" in str(item):
                form_dict["_wpnonce"] = item.get("value")

        return form_dict

    @staticmethod
    def body(res: str) -> str or None:
        """
        Parse response to get response's body
        :param res: response from server
        :return: response's body
        """
        body = res.split("\r\n\r\n", 1)
        if not body:
            return None

        return body[1]

    @staticmethod
    def url(res: str) -> str or None:
        """
        Parse response to get the uploaded image's url
        :param res: response from server
        :return: uploaded image's url
        """
        import re

        url = re.findall('"url":"([^"]*)"', res)
        if not url:
            return None

        return url[0].replace("\\/", "/")


def file_type(img_name: str) -> str or None:
    """
    Get file's mime type based on file's extension
    :param img_name: image name
    :return: file's mime type
    """
    import mimetypes

    mime_type, encoding = mimetypes.guess_type(img_name)
    if mime_type and "image" in mime_type:
        return mime_type

    return None


def file_url(cookie: str, file_id: int) -> str or None:
    """
    Process to get uploaded image's url
    :param cookie: cookies for authentication
    :param file_id: uploaded image's id
    :return: uploaded image's url
    """
    boundary = Gen.boundary()
    body = Gen.body_query(boundary, file_id)
    if not body:
        return None

    header = ReqHeader.post_form(cookie, boundary, len(body), PATH_ADMIN)
    msg = header + body

    url = Parse.url(get_res(msg))
    return url


def upload() -> str or None:
    """
    Process to upload image
    :return: uploaded image's url
    """
    cookie = login()
    if not cookie:
        return None

    header = ReqHeader.get(cookie, HOST, PATH)
    res = get_res(header)
    if not res:
        return None

    form_dict = Parse.wpnonce(res)
    if not form_dict:
        return None

    boundary = Gen.boundary()

    body = Gen.body_upload(boundary, form_dict)
    if not body:
        return None
    header = ReqHeader.post_form(cookie, boundary, len(body), PATH_UPLOAD).encode()
    msg = header + body

    res = get_res(msg)
    if not res:
        return None

    body_recv = Parse.body(res)
    if not body_recv.isdigit():
        return None

    url = file_url(cookie, int(body_recv))

    return url


def main():
    url = upload()
    if not url:
        failed()
    print(f"Upload success. File upload url:\n{url}")


if __name__ == '__main__':
    main()
