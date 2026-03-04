CREATE TABLE users (
    user_id VARCHAR(10) PRIMARY KEY,
    username VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL, 
    password_hash VARCHAR(255) NOT NULL,
    fullname VARCHAR(100),
    roles VARCHAR(10) CHECK (roles IN ('Customer', 'Admin'))
);
-- Tạo bảng categories
CREATE TABLE categories (
    category_id VARCHAR(10) PRIMARY KEY,
    category_name VARCHAR(60) NOT NULL,
    category_desc TEXT 
);
-- Tạo bảng products
CREATE TABLE products (
    product_id VARCHAR(10) PRIMARY KEY,
    product_name VARCHAR(120) NOT NULL,
    product_desc VARCHAR(255),
    product_price INTEGER NOT NULL CHECK (product_price >= 0),  -- Giá sản phẩm, bắt buộc và >= 0
    stock_quantity INTEGER NOT NULL CHECK (stock_quantity >= 0),  -- Số lượng trong kho, bắt buộc và >= 0
    product_status VARCHAR(20) DEFAULT 'in_stock' CHECK (product_status IN ('in_stock', 'out_of_stock')),
    user_id VARCHAR(10) NOT NULL REFERENCES users(user_id),  -- Khóa ngoại đến users, người tạo
    category_id VARCHAR(10) NOT NULL REFERENCES categories(category_id),  -- Khóa ngoại đến categories, danh mục
    image_urls VARCHAR(255)  -- Hình minh họa sản phẩm (có thể null)
);
-- Tạo bảng orders
CREATE TABLE orders (
    order_id VARCHAR(10) PRIMARY KEY,
    product_quantity INTEGER NOT NULL CHECK (product_quantity >= 0),  -- Số lượng sản phẩm trong đơn hàng, bắt buộc và >= 0
    total_amount INTEGER NOT NULL CHECK (total_amount >= 0),  -- Tổng tiền, bắt buộc và >= 0
    order_date TIMESTAMP NOT NULL,  -- Ngày đặt hàng, bắt buộc
    status VARCHAR(20) DEFAULT 'success' CHECK (status IN ('success', 'failed')),
    user_id VARCHAR(10) NOT NULL REFERENCES users(user_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Ngày tạo, mặc định thời gian hiện tại
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Ngày cập nhật, mặc định thời gian hiện tại
);
-- Tạo bảng order_items
CREATE TABLE order_items (
    item_id VARCHAR(10) PRIMARY KEY,  -- Khóa chính, ID mục
    order_id VARCHAR(10) NOT NULL REFERENCES orders(order_id), 
    product_id VARCHAR(10) NOT NULL REFERENCES products(product_id),
    quantity INTEGER NOT NULL CHECK (quantity >= 0),  -- Số lượng, bắt buộc và >= 0
    price INTEGER NOT NULL CHECK (price >= 0)  -- Giá, bắt buộc và >= 0
);
   
-- Thêm 10 dữ liệu mẫu cho users (người dùng mới liên quan đến thời trang)
INSERT INTO users (user_id, username, email, password_hash, fullname, roles)
VALUES
	('U001', 'john_doe', 'john@example.com', '$2b$10$examplehashedpassword', 'John Doe', 'Customer'),
    ('U002', 'admin_user', 'admin@example.com', '$2b$10$anotherhashedpassword', 'Admin User', 'Admin'),
 	('U003', 'fashion_buyer', 'buyer@example.com', '$2b$10$newhashedpassword1', 'Alice Fashion', 'Customer'),
    ('U004', 'style_admin', 'styleadmin@example.com', '$2b$10$newhashedpassword2', 'Bob Style', 'Admin'),
    ('U005', 'trendy_shop', 'trendy@example.com', '$2b$10$hash1', 'Trendy Shopper', 'Customer'),
    ('U006', 'style_queen', 'queen@example.com', '$2b$10$hash2', 'Style Queen', 'Customer'),
    ('U007', 'fashion_guru', 'guru@example.com', '$2b$10$hash3', 'Fashion Guru', 'Admin'),
    ('U008', 'outfit_addict', 'addict@example.com', '$2b$10$hash4', 'Outfit Addict', 'Customer'),
    ('U009', 'chic_admin', 'chic@example.com', '$2b$10$hash5', 'Chic Admin', 'Admin'),
    ('U010', 'vogue_buyer', 'vogue@example.com', '$2b$10$hash6', 'Vogue Buyer', 'Customer'),
    ('U011', 'glam_user', 'glam@example.com', '$2b$10$hash7', 'Glam User', 'Customer'),
    ('U012', 'retro_style', 'retro@example.com', '$2b$10$hash8', 'Retro Style', 'Customer'),
    ('U013', 'mod_admin', 'mod@example.com', '$2b$10$hash9', 'Mod Admin', 'Admin'),
    ('U014', 'elegant_shop', 'elegant@example.com', '$2b$10$hash10', 'Elegant Shop', 'Customer');

-- Thêm 3 dữ liệu mẫu cho categories (danh mục thời trang bổ sung)
INSERT INTO categories (category_id, category_name, category_desc)
VALUES
	('CAT001', 'Electronics', 'Danh mục các sản phẩm điện tử như điện thoại, máy tính.'),
    ('CAT002', 'Books', 'Sách và tài liệu học tập.'),
	('CAT003', 'Clothing', 'Danh mục quần áo như áo sơ mi, váy, quần jeans.'),
    ('CAT004', 'Shoes', 'Giày dép thời trang như giày cao gót, sneaker.'),
    ('CAT005', 'Accessories', 'Phụ kiện như túi xách, kính râm, trang sức.'),
    ('CAT006', 'Dresses', 'Váy dạ tiệc, váy công sở, váy hè.'),
    ('CAT007', 'Jeans & Pants', 'Quần jeans, quần âu, quần thể thao.'),
    ('CAT008', 'Hats & Caps', 'Mũ nón thời trang, mũ lưỡi trai.');

-- Thêm 10 dữ liệu mẫu cho products (sản phẩm thời trang)
INSERT INTO products (product_id, product_name, product_desc, product_price, stock_quantity, product_status, user_id, category_id, image_urls)
VALUES 
 	('P001', 'iPhone 14', 'Điện thoại thông minh cao cấp', 999, 50, 'in_stock', 'U001', 'CAT001', 'https://example.com/image1.jpg,https://example.com/image2.jpg'),
    ('P002', 'Harry Potter Book', 'Sách giả tưởng', 20, 100, 'in_stock', 'U002', 'CAT002', NULL),
	('P003', 'Cotton T-Shirt', 'Áo thun cotton thoải mái, màu trắng', 25, 200, 'in_stock', 'U001', 'CAT003', 'https://example.com/tshirt1.jpg,https://example.com/tshirt2.jpg'),
    ('P004', 'Leather Jacket', 'Áo khoác da cao cấp, phong cách biker', 150, 50, 'in_stock', 'U001', 'CAT003', 'https://example.com/jacket.jpg'),
    ('P005', 'High Heels', 'Giày cao gót da, size 38', 80, 30, 'in_stock', 'U002', 'CAT004', 'https://example.com/heels.jpg'),
    ('P006', 'Designer Handbag', 'Túi xách thiết kế, da thật', 200, 20, 'in_stock', 'U002', 'CAT005', 'https://example.com/handbag.jpg'),
    ('P007', 'Summer Dress', 'Váy hè nhẹ nhàng, hoa văn', 45, 150, 'in_stock', 'U001', 'CAT006', 'https://example.com/dress1.jpg'),
    ('P008', 'Skinny Jeans', 'Quần jeans ôm sát, màu xanh', 60, 100, 'in_stock', 'U002', 'CAT007', 'https://example.com/jeans1.jpg'),
    ('P009', 'Baseball Cap', 'Mũ lưỡi trai thể thao', 15, 300, 'in_stock', 'U003', 'CAT008', 'https://example.com/cap1.jpg'),
    ('P010', 'Evening Gown', 'Váy dạ tiệc lấp lánh', 120, 40, 'in_stock', 'U004', 'CAT006', 'https://example.com/gown.jpg'),
    ('P011', 'Cargo Pants', 'Quần túi rộng, phong cách quân đội', 50, 80, 'in_stock', 'U005', 'CAT007', 'https://example.com/pants1.jpg'),
    ('P012', 'Sun Hat', 'Mũ rộng vành chống nắng', 25, 200, 'in_stock', 'U006', 'CAT008', 'https://example.com/hat1.jpg'),
    ('P013', 'Cocktail Dress', 'Váy cocktail ngắn', 70, 60, 'in_stock', 'U007', 'CAT006', 'https://example.com/cocktail.jpg'),
    ('P014', 'Denim Jacket', 'Áo khoác jeans cổ điển', 85, 90, 'in_stock', 'U008', 'CAT003', 'https://example.com/denimjacket.jpg'),
    ('P015', 'Sneakers', 'Giày sneaker trắng cơ bản', 55, 120, 'in_stock', 'U009', 'CAT004', 'https://example.com/sneakers.jpg'),
    ('P016', 'Silk Scarf', 'Khăn lụa cao cấp', 30, 250, 'in_stock', 'U010', 'CAT005', 'https://example.com/scarf.jpg');

-- Thêm 10 dữ liệu mẫu cho orders (đơn hàng thời trang)
INSERT INTO orders (order_id, product_quantity, total_amount, order_date, status, user_id, created_at, updated_at)
VALUES
	('O001', 2, 1998, '2023-10-01 10:00:00', 'success', 'U001', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('O002', 1, 20, '2023-10-02 14:30:00', 'failed', 'U002', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
 	('O003', 3, 255, '2023-10-03 09:15:00', 'success', 'U003', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O004', 2, 400, '2023-10-04 16:45:00', 'success', 'U004', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O005', 2, 105, '2023-10-05 11:00:00', 'success', 'U005', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O006', 3, 235, '2023-10-06 12:30:00', 'success', 'U006', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O007', 2, 130, '2023-10-07 13:45:00', 'success', 'U007', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O008', 3, 170, '2023-10-08 14:00:00', 'failed', 'U008', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('O009', 2, 175, '2023-10-09 15:15:00', 'success', 'U009', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O010', 3, 115, '2023-10-10 16:30:00', 'success', 'U010', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O011', 2, 125, '2023-10-11 17:00:00', 'success', 'U011', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), 
    ('O012', 3, 190, '2023-10-12 18:15:00', 'success', 'U012', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),  
    ('O013', 2, 90, '2023-10-13 19:30:00', 'failed', 'U013', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),  
    ('O014', 3, 250, '2023-10-14 20:00:00', 'success', 'U014', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Thêm khoảng 25 dữ liệu mẫu cho order_items (chi tiết đơn hàng thời trang, khớp với orders)
INSERT INTO order_items (item_id, order_id, product_id, quantity, price)
VALUES
 	('I001', 'O001', 'P001', 1, 999), 
    ('I002', 'O001', 'P002', 1, 20),  
    ('I003', 'O002', 'P002', 1, 20),
	('I004', 'O003', 'P003', 1, 25), 
    ('I005', 'O003', 'P004', 1, 150), 
    ('I006', 'O003', 'P005', 1, 80), 
    ('I007', 'O004', 'P006', 1, 200), 
    ('I008', 'O004', 'P006', 1, 200),
    ('I009', 'O005', 'P007', 1, 45), ('I010', 'O005', 'P009', 1, 15), ('I011', 'O005', 'P016', 1, 45),  
    ('I012', 'O006', 'P008', 1, 60), ('I013', 'O006', 'P010', 1, 120), ('I014', 'O006', 'P012', 1, 25), 
    ('I015', 'O007', 'P011', 1, 50), ('I016', 'O007', 'P013', 1, 70), ('I017', 'O007', 'P015', 1, 55), 
    ('I018', 'O008', 'P014', 1, 85), ('I019', 'O008', 'P016', 1, 30), ('I020', 'O008', 'P009', 1, 15), 
    ('I021', 'O009', 'P015', 1, 55), ('I022', 'O009', 'P010', 1, 120), 
    ('I023', 'O010', 'P016', 1, 30), ('I024', 'O010', 'P009', 1, 15), ('I025', 'O010', 'P012', 1, 25),
    ('I026', 'O011', 'P007', 1, 45), ('I027', 'O011', 'P008', 1, 60), ('I028', 'O011', 'P011', 1, 50), 
    ('I029', 'O012', 'P013', 1, 70), ('I030', 'O012', 'P014', 1, 85), ('I031', 'O012', 'P015', 1, 55),
    ('I032', 'O013', 'P012', 1, 25), ('I033', 'O013', 'P016', 1, 30), ('I034', 'O013', 'P009', 1, 15),  
    ('I035', 'O014', 'P010', 1, 120), ('I036', 'O014', 'P011', 1, 50), ('I037', 'O014', 'P007', 1, 45);

