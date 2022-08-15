# Web14

- [****HTTP Host header attacks****](#http-host-header-attacks)
    - [Basic password reset poisoning](#basic-password-reset-poisoning)
    - [Web cache poisoning via ambiguous requests](#web-cache-poisoning-via-ambiguous-requests)
    - [Host header authentication bypass](#host-header-authentication-bypass)
    - [Routing-based SSRF](#routing-based-ssrf)
    - [SSRF via flawed request parsing](#ssrf-via-flawed-request-parsing)
    - [Password reset poisoning via dangling markup](#password-reset-poisoning-via-dangling-markup)

# ****HTTP Host header attacks****

### Basic password reset poisoning

Khi sử dụng tính năng Quên mật khẩu, ta có thể thấy nếu đổi header Host, phần host trong link đổi password gửi về mail cũng sẽ được đổi theo

![Untitled](wu_media/Untitled.png)

![Untitled](wu_media/Untitled%201.png)

Do đó ta có thể đổi header Host thành host của exploit server để khi victim click vào link, ta sẽ có được token reset của victim

![Untitled](wu_media/Untitled%202.png)

![Untitled](wu_media/Untitled%203.png)

Dùng token đó để truy câp trang đổi password của victim, đổi pass và login để hoàn thành lab

---

### Web cache poisoning via ambiguous requests

Có thể thấy backend sẽ lấy header Host đầu tiên để truy cập website, nhưng nếu thêm header Host thứ hai thì nó sẽ được reflect trên website

![Untitled](wu_media/Untitled%204.png)

Inject đoạn code để  thực hiện XSS. Page sẽ được lưu vào cache và các victim sau này khi truy cập trang chủ cũng sẽ bị XSS.

![Untitled](wu_media/Untitled%205.png)

---

### Host header authentication bypass

Truy cập `/robots.txt`, ta có được path vào trang `admin`

![Untitled](wu_media/Untitled%206.png)

Truy cập `/admin`, ta thấy thông báo path chỉ dành cho local users.

![Untitled](wu_media/Untitled%207.png)

Đổi header Host thành `localhost` để bypass

![Untitled](wu_media/Untitled%208.png)

Gửi GET request đến `/admin/delete?username=carlos` để xóa user `carlos`

![Untitled](wu_media/Untitled%209.png)

---

### Routing-based SSRF

Dùng Intruder để bruteforce private IP ở header Host

![Untitled](wu_media/Untitled%2010.png)

Có thể thấy ở địa chỉ `192.168.0.70`, ta được redirect đến `/admin`

![Untitled](wu_media/Untitled%2011.png)

Ở đây ta có thể gửi request để xóa user

![Untitled](wu_media/Untitled%2012.png)

![Untitled](wu_media/Untitled%2013.png)

---

### SSRF via flawed request parsing

Xem solution

---

### Password reset poisoning via dangling markup

Nhận thấy website không parse phần port ở header Host

![Untitled](wu_media/Untitled%2014.png)

Và phần port được sử dụng vào phần link click me của email

![Untitled](wu_media/Untitled%2015.png)

Xem solution