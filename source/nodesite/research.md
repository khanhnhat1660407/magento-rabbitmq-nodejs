Postgres: 

- Hệ thống quản lý cơ sở dữ liệu quan hệ đối tượng (ORDBMS)

- ORDBMS: Object Relational Database Management

- Cung cấp đầy đủ cá tính năng

    • complex queries  				    : truy vấn phức tạp 
    • foreign keys					    : khóa ngoại 
    • triggers						    : trigger
    • updatable views				    : cập nhật lượt view
    • transactional integrity			: bảo toàn transactional 
    • multiversion concurrency control	: Kiểm soát đồng thời 
      
- Người dùng có khả năng mở rộng bằng cách thêm mới:
    • data types					: loại dữ liệu 
    • functions						: functions
    • operators						: toán tử 
    • aggregate functions			: hàm tổng hợp mới 
    • index methods					: 
    • procedural languages			: PostgreSQL cho phép các hàm do người dùng định nghĩa được viết bằng các ngôn ngữ khác ngoài SQL và C
      
- PostgreSQL có thể được sử dụng, sửa đổi và phân phối bởi bất kỳ ai miễn phí cho bất kỳ mục đích nào, có thể là tư nhân, thương mại hoặc học thuật.
- Máy chủ PostgreSQL có thể xử lý nhiều kết nối đồng thời từ các máy khách. Để đạt được điều này, nó bắt đầu (forks) một quy trình mới cho mỗi kết nối
- Hỗ trợ truy vấn song song parallel query bằng cách tận dụng nhiều CPU để trả lời các truy vấn nhanh hơn. Nhiều truy vấn có thể chạy nhanh hơn gấp đôi khi sử dụng truy vấn song song và một số truy vấn có thể chạy nhanh hơn bốn lần hoặc thậm chí nhiều hơn. Các truy vấn chạm vào một lượng lớn dữ liệu nhưng chỉ trả lại một vài hàng cho người dùng thường sẽ có lợi nhất.
- PostgreSQL đánh indexes cho các bảng  hỗ trợ query tìm kiếm nhanh hơn 

- Hỗ trợ fulltext search 

Ex: SELECT * from users where 'Nguyen & Nam'::tsquery @@ user_name::tsvector;
-> tim tat ca cac user co ho nguyen & ten nam 
Ex: SELECT * from users where to_tsquery('Nguyen & Nam') @@ to_tsvector(user_name)
-> tim tat ca cac user trong ho ten co chua 'Nguyen' hoac chua 'Nam'

Another:
tsvector @@ tsquery
tsquery  @@ tsvector
text @@ tsquery
text @@ text

More Fulltext search: 
- https://www.postgresql.org/docs/10/textsearch-intro.html#TEXTSEARCH-DOCUMENT
More Examples: 
- https://www.postgresql.org/docs/10/textsearch-tables.html#TEXTSEARCH-TABLES-SEARCH


- Kết hợp fulltext search và index để tăng tốc độ search -> chưa đọc 

Advanced Features: Tính năng nâng cao

1. Views: tính năng cho phép tạo view cho kết quả tra về của một câu truy vấn, khi đó không cần phải viết câu truy vấn đó nữa mà chỉ cần truy vấn vào view 
Ex:
-> Tạo view: CREATE VIEW myview AS
                SELECT city, temp_lo, temp_hi, prcp, date, location
                    FROM weather, cities
                    WHERE city = name;

-> SELECT * FROM myview;
-> DROP VIEW myview;

2. Foreign Keys: Khóa ngoại
-> duy trì tính toàn vẹn tham chiếu 
3. Transactions: 
-> Bảo đảm cho 1 transactions được hoàn thành, tạo các điểm save point để rollback khi cần 
4. Window function: 
5. Inheritance

