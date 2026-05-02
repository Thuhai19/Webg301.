# RPG Game Manager — PHP thuần (MVC)

Website quản lý nhân vật, kỹ năng, vật phẩm và người chơi cho game RPG, chạy trên **XAMPP** (Apache + MySQL + PHP).

## Cấu trúc thư mục

```
WEB.rpggame/
├── app/
│   ├── config/       # Cấu hình (DB, helpers)
│   ├── controllers/  # Điều phối request, gọi Model, render View
│   ├── core/         # Controller cơ sở, bootstrap, autoload
│   └── models/       # Truy vấn database (PDO)
├── frontend/
│   ├── views/        # Layout chung (header, footer, navbar)
│   ├── auth/         # Giao diện đăng nhập
│   ├── dashboard/    # Trang dashboard
│   ├── players/      # View CRUD người chơi
│   ├── characters/   # View CRUD nhân vật + gán skill/item
│   ├── skills/       # View CRUD kỹ năng
│   └── items/        # View CRUD vật phẩm
├── public/
│   └── index.php     # Front controller + routing
├── database/         # File SQL tạo database
└── .htaccess
```

## Cài đặt trên XAMPP

1. **Copy project** vào `C:\xampp\htdocs\WEB.rpggame` (hoặc tên thư mục bạn dùng).

2. **Khởi động** Apache và MySQL trong XAMPP Control Panel.

3. **Tạo database**: mở phpMyAdmin (`http://localhost/phpmyadmin`), tab **SQL**, dán và chạy toàn bộ nội dung file `database/schema.sql`.

4. **Cấu hình kết nối MySQL** (nếu cần): sửa `app/config/database.php` — mặc định user `root`, password rỗng, database `rpg_game_db`.

5. **URL gốc (BASE_URL)**: nếu bạn truy cập `http://localhost/WEB.rpggame/public/index.php`, để `BASE_URL` trong `app/config/config.php` là chuỗi rỗng `''`. Nếu cấu hình ảo host khác, đặt tiền tố đường dẫn web, ví dụ: `define('BASE_URL', '/WEB.rpggame/public');` (không có dấu `/` cuối; hàm `base_url()` sẽ tự thêm).

6. **Đăng nhập mặc định** (sau khi import SQL):

   - **Username:** `admin`  
   - **Password:** `admin123`

   Chỉ các tài khoản có trong bảng `users` mới đăng nhập được (không có trang đăng ký; thêm user trực tiếp trong DB nếu cần).

   Nếu bạn đã tạo DB từ bản cũ có cột `role`, chạy thêm `database/migrate_remove_role.sql` (sau khi sao lưu).

## Routing (đơn giản)

Mọi request đi qua `index.php` với query string:

| Tham số | Ý nghĩa |
|--------|---------|
| `controller` | `auth`, `dashboard`, `player`, `character`, `skill`, `item` |
| `action` | Tên method trong Controller, ví dụ `index`, `create`, `store`, `show`, `edit`, `update`, `delete` |

Ví dụ: `index.php?controller=player&action=index` — danh sách người chơi.

**Tìm theo ID:** trên các trang danh sách, dùng `search_id`, ví dụ: `index.php?controller=player&action=index&search_id=1`.

## Luồng MVC (giải thích ngắn)

1. **Model** (`app/models/*.php`): chứa class truy vấn dữ liệu bằng **PDO** và **prepared statements**. Không chứa HTML.

2. **View** (`frontend/auth/`, `frontend/players/`, … và `frontend/views/layout/`): chỉ hiển thị; nhận biến từ controller (ví dụ `$players`, `$pageTitle`).

3. **Controller** (`app/controllers/*.php`): nhận request, gọi Model, chuyển dữ liệu sang View qua `$this->render('đường/dẫn/view.php', $data)`.

4. **Front Controller** (`public/index.php`): đọc `controller` và `action`, khởi tạo class Controller tương ứng và gọi đúng method.

5. **Session:** sau khi đăng nhập thành công, thông tin user lưu trong `$_SESSION`; các trang quản trị gọi `requireAdmin()` trong `app/core/Controller.php`.

## Yêu cầu

- PHP 7.4+ (khuyến nghị 8.x), extension `pdo_mysql`
- MySQL / MariaDB

## Bảo mật (học tập)

Mật khẩu lưu bằng `password_hash` / `password_verify`. Trên môi trường thật cần HTTPS, quyền file, và đổi mật khẩu admin mặc định.

<!-- _ kill của player và kill by của character có thể thành list để xem khi player kill nhiều character, chứa nhiều item và character bị kill bởi nhiều player
-xóa cột thông tin kill, item và kill by của player và character.
- cho kill, item của player vào bên trong  -->