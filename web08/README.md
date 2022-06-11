# Web08

# CSRF

### ****CSRF vulnerability with no defenses****

Sử dụng CSRF PoC generator để tạo form CSRF từ request POST đổi email

![Untitled](writeup_media/Untitled.png)

Copy paste và store vào Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%201.png)

---

### ****CSRF where token validation depends on request method****

Đổi thử request POST đổi email thành request GET không có CSRF token, nhận thấy vẫn có thể đổi email

![Untitled](writeup_media/Untitled%202.png)

Dùng CSRF PoC generator để tạo form GET đổi email

![Untitled](writeup_media/Untitled%203.png)

Copy paste và store vào Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%204.png)

---

### ****CSRF where token validation depends on token being present****

Nhận thấy server side sẽ chỉ check CSRF token khi tồn tại param csrf, nếu không thì sẽ không check

![Untitled](writeup_media/Untitled%205.png)

Dùng CSRF PoC generator để tạo form POST đổi email

![Untitled](writeup_media/Untitled%206.png)

Copy paste và store vào Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%207.png)

---

### ****CSRF where token is not tied to user session****

Thử dùng chức năng đổi email bằng user `carlos`, nhận thấy mỗi lần đổi email thì CSRF token lại bị đổi

![Untitled](writeup_media/Untitled%208.png)

![Untitled](writeup_media/Untitled%209.png)

Nhận thấy ta có thể dùng CSRF token có hiệu lực của user `wiener` cho user `carlos`

![Untitled](writeup_media/Untitled%2010.png)

![Untitled](writeup_media/Untitled%2011.png)

→ Server không kiểm tra sự liên quan giữa CSRF token với Session cookie

→ Ta có thể dùng CSRF token có hiệu lực bất kì để thực hiện CSRF

Copy CSRF token đang có hiệu lực của user `carlos`

![Untitled](writeup_media/Untitled%2012.png)

Tạo form bằng CSRF PoC generator và paste CSRF token vừa copy vào

![Untitled](writeup_media/Untitled%2013.png)

Copy paste và store vào Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%2014.png)

---

### ****CSRF where token is tied to non-session cookie****

Nhận thấy khi sau khi logout, cookie `session` bị thay đổi nhưng cookie `csrfKey` và `csrf token` vẫn giữ nguyên

![Untitled](writeup_media/Untitled%2015.png)

![Untitled](writeup_media/Untitled%2016.png)

![Untitled](writeup_media/Untitled%2017.png)

![Untitled](writeup_media/Untitled%2018.png)

→ Khả năng cao `csrf token` sẽ gắn liền với cookie `csrfKey`, đồng thời cookie `csrfKey` độc lập với cookie `session`

Ngoài ra khi dùng chức năng search, server trả về response có Set-Cookie là chuỗi mà ta vừa search

![Untitled](writeup_media/Untitled%2019.png)

Thử thực hiện CLRF injection để set lại cookie `csrfKey`

![Untitled](writeup_media/Untitled%2020.png)

![Untitled](writeup_media/Untitled%2021.png)

Sử dụng CSRF PoC generator để tạo form POST đổi email, thêm tag `img` với src dẫn đến đường link search set cookie `csrfKey` như phía trên. Như vậy khi truy cập website của ta, browser của victim sẽ thực hiện GET đên src của `img` và được set cookie `csrfKey`. Do đây không phải link ảnh nên `onerror` sẽ được trigger và thực hiện submit form với `csrf token` tương ứng với cookie `csrfKey` mà ta vừa set.

![Untitled](writeup_media/Untitled%2022.png)

Copy paste và store vào Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%2023.png)

---

### ****CSRF where token is duplicated in cookie****

Để ý thấy chức năng đổi email sẽ check `csrf token` theo cookie `csrf`, nếu giống thì hợp lệ còn không sẽ phản hồi lại "Invalid CSRF token”

![Untitled](writeup_media/Untitled%2024.png)

![Untitled](writeup_media/Untitled%2025.png)

Tương tự lab ****CSRF where token is tied to non-session cookie****, ta lợi dụng CRLF injection ở chức năng search để sửa cookie `csrf`

![Untitled](writeup_media/Untitled%2026.png)

---

### ****CSRF where Referer validation depends on header being present****

Nhận thấy website không sử dụng `csrf token` để validate POST request

![Untitled](writeup_media/Untitled%2027.png)

Sử dụng CSRF PoC generator để tạo form POST đổi email

![Untitled](writeup_media/Untitled%2028.png)

Copy paste vào Exploit server

![Untitled](writeup_media/Untitled%2029.png)

Tuy nhiên khi View exploit, ta sẽ bị response “Invalid referer header”

![Untitled](writeup_media/Untitled%2030.png)

Có thể do server check header Referrer để xem request POST đó có được gửi từ cùng một host không.

Dùng tag `meta` để bỏ header Referrer khi browser gửi POST request

```jsx
<meta name="referrer" content="never">
```

Sửa lại nội dung web trên Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%2031.png)

---

### ****CSRF with broken Referer validation****

Website không sử dụng `csrf token` mà dùng header Referer để chống CSRF.

Thử bỏ header Referer đi, ta vẫn sẽ bị thông báo “Invalid referrer header”

![Untitled](writeup_media/Untitled%2032.png)

Như vậy điều kiện tiên quyết là ta vẫn phải cần header Referrer. Thử sửa request với header Refferer có chứa url của `/my-account/change-email`

![Untitled](writeup_media/Untitled%2033.png)

Thành công → server chỉ check chuỗi Referer có tồn tại url đến `/my-account/change-email`

Sửa hàm `history.pushState()` thành

```jsx
history.pushState('', '', '/exploit?acce1f3d1e662eeac0b80228002c00ba.web-security-academy.net')
```

Đồng thời thêm tag `meta`

```html
<meta name="referrer" content="unsafe-url">
```

![Untitled](writeup_media/Untitled%2034.png)

Copy paste và store vào Exploit server rồi gửi cho victim để hoàn thành lab

![Untitled](writeup_media/Untitled%2035.png)