# Checkpoint

# Challenge 1

Đăng kí tài khoản và login vào website. Ở trang profile có chức năng đổi tên. 

![Untitled](wu_media/Untitled.png)

Để ý dưới avatar có `role: user` → ta có thể thử thêm `role=admin` vào request đổi tên

![Untitled](wu_media/Untitled%201.png)

Reload profile → lấy được flag

![Untitled](wu_media/Untitled%202.png)

---

# Challenge 2

Xác định được server sử dụng MySQL do comment bằng dấu `#` thì không bị `Internal Server Error`

![Untitled](wu_media/Untitled%203.png)

Sử dụng `SQLmap` để quét SQLi ở param `username`. Copy paste request ra file `~/SQLi.txt`

![Untitled](wu_media/Untitled%204.png)

Chạy `SQLmap`. Do ta đã biết server sử dụng MySQL nên sẽ thêm option `--dbms=MySQL`

```bash
python3 sqlmap.py -r ~/SQLi.txt -p username --level=5 --risk=3 --dbms=MySQL --dump --batch
```

![Untitled](wu_media/Untitled%205.png)

---

# Challenge 3

Sau một hồi bruteforce, ta có được username và password `test:test`

Login vào, xem source code phần avatar có đoạn khả nghi 

![Untitled](wu_media/Untitled%206.png)

Thử `/image.php?f=../../../../etc/passwd`

![Untitled](wu_media/Untitled%207.png)

Như vậy ta có thể khai thác LFI để tìm flag.

Đọc file `../index.php` ta có flag

![Untitled](wu_media/Untitled%208.png)

---

# Challenge 4

Đăng kí tài khoản và login vào website. Ở trang profile có chức năng thay avatar.

Ta tạo file `ava.php` với nội dung và upload thử

```php
<?php system("ls"); ?>
```

Tuy nhiên website chỉ cho phép ta upload một vài dạng file ảnh.

![Untitled](wu_media/Untitled%209.png)

![Untitled](wu_media/Untitled%2010.png)

Tìm lại request POST đó và gửi vào `Repeater`. Thay `Content-Type: image/png`

![Untitled](wu_media/Untitled%2011.png)

Ta đã bypass được check file type và có vị trí của file vừa upload. Gửi request GET đến vị trí đó.

![Untitled](wu_media/Untitled%2012.png)

Lệnh `ls` đã được thực thi. Từ đây ta dễ dàng tìm và đọc file flag. Liệt kê file ở thư mục mẹ, ta thấy có file `flag.txt`

```php
<?php system("ls -a .."); ?>
```

![Untitled](wu_media/Untitled%2013.png)

![Untitled](wu_media/Untitled%2014.png)

Đọc file `flag.txt`

```php
<?php system("cat ../flag.txt"); ?>
```

![Untitled](wu_media/Untitled%2015.png)

![Untitled](wu_media/Untitled%2016.png)