# Hệ thống đặt lịch tư vấn

## 1. Giới thiệu

**Hệ thống đặt lịch tư vấn** là một website được xây dựng bằng PHP, cho phép sinh viên đăng ký lịch hẹn với giảng viên chuyên về Ngoại ngữ.

Hệ thống hỗ trợ sinh viên nhập thông tin cá nhân, lựa chọn giảng viên, ngày giờ và nội dung cần tư vấn. Các lịch đã đặt được hiển thị trong danh sách để sinh viên theo dõi và hủy lịch khi cần.


## 2. Chức năng chính

### Đặt lịch

Sinh viên có thể:

- Nhập họ và tên.
- Nhập mã sinh viên.
- Chọn giảng viên Ngoại ngữ.
- Chọn ngày hẹn.
- Chọn giờ hẹn.
- Nhập nội dung cần tư vấn.
- Đặt nhiều lịch khác nhau.

### Kiểm tra lịch trùng

Hệ thống kiểm tra:
- Giảng viên.
- Ngày.
- Giờ.

Nếu giảng viên đã có lịch ở cùng ngày và cùng giờ, hệ thống sẽ thông báo và yêu cầu chọn thời gian khác.

### Quản lý lịch đã đặt

Các lịch sau khi đặt sẽ được hiển thị trong bảng **"Lịch của sinh viên"**.

Thông tin gồm:

- STT
- Giảng viên
- Ngày
- Giờ
- Nội dung
- Trạng thái
- Thao tác

### Trạng thái lịch

Hệ thống tự động xác định trạng thái dựa vào ngày và giờ hiện tại:

- **Chưa diễn ra:** Lịch hẹn chưa đến.
- **Đang diễn ra:** Lịch đang trong thời gian hẹn.
- **Đã hoàn thành:** Thời gian hẹn đã kết thúc.
- **Đã hủy:** Sinh viên đã hủy lịch.

### Hủy lịch

Sinh viên chỉ có thể hủy lịch khi lịch **chưa diễn ra**.

Khi nhấn nút **Hủy**, hệ thống sẽ hiển thị thông báo xác nhận.

Sau khi hủy:

- Lịch không bị xóa.
- Trạng thái chuyển thành **Đã hủy**.
- Lịch vẫn được hiển thị trong danh sách.


## 3. Công nghệ sử dụng

| Công nghệ | Mục đích |
|---|---|
| PHP | Xử lý dữ liệu và các chức năng của hệ thống |
| HTML | Xây dựng cấu trúc trang web |
| CSS | Thiết kế giao diện |
| Session PHP | Lưu trữ danh sách lịch trong phiên làm việc |


## 4. Cấu trúc thư mục

he-thong-dat-lich/
│
└── datlich.php
