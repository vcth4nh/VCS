## Độ khó của challenge: 250

## Các chức năng của trang web:
- Login: nếu login được với tài khoản `admin`, ta sẽ có flag

## Lỗ hổng ở chức năng:
- Login: JSON Interoperability 
    - Khi login, front-server (main) sẽ kiểm tra xem username có phải là `admin` không.
        - Nếu có thì chỉ cho login ở `localhost`
        - Nếu không thì chuyển username về back-server (verify) để kiểm tra thông tin

    - Back-server sử dụng parser `ujson` với python 2.7. Ở phiên bản python này các "surrogates" (ký tự unicode từ `U+D800` đến `U+DDDD`) vẫn có thể được encode/decode utf-8, mặc dù việc này không hợp lệ. Parser `ujson` khi parse data sẽ chuyển các fields thành dạng `unicode`, và loại bỏ các ký tự unicode không hợp lệ kể trên.
    
    - Front-server sử dụng parser `ujson` với python 3. Ở phiên bản python này các "surrogates" sẽ không được encode/decode utf-8, đồng thời type `string` được mặc định dạng unicode. Do đó khi parser gặp các "surrogates", chúng sẽ coi như là một string bình thường.
    
    → Việc kết hợp 2 phiên bản python khác nhau để parse json bằng thư viện `ujson` có thể làm cho kết quả của chúng không nhất quán

    - Ngoài ra parser `ujson` còn xử lý việc trùng key trong json bằng cách lấy giá trị xuất hiện cuối cùng của các key trùng

## Khai thác lỗ hổng:
- Gửi payload `{"username":"a", "username\ud8000":"admin"}`
- Giải thích:
    - Cả front và back-server đều dùng `ujson.loads(request.data)` để parse json

    - Ở front-server sau khi parse sẽ được một dict gồm 2 key là `username` và `username\ud8000`,  và do username lúc này không phải là `admin` nên server sẽ gửi lại `request.data` đến back-server để kiểm tra.
    - Phía back-server khi parse data sẽ nhận thấy trong key `username\ud8000` có đoạn unicode `\ud8000` không hợp lệ nên sẽ xóa nó đi, trở thành `username` → json lúc này trở thành `{"username":"a", "username":"admin"}`. Do bị trùng key nên value cuối cùng sẽ được lấy.\
    → `username` lúc này sẽ là `admin`
