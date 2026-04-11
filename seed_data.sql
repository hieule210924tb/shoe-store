-- Seed dữ liệu mẫu để test nhanh
-- Import file này sau khi import `database.sql`
-- Ghi chú: chạy lại toàn file có thể tạo trùng dòng orders/products nếu MySQL không báo lỗi;
--         phần UPDATE mô tả (mục 3a) an toàn khi chạy nhiều lần.

USE shoe_store;

-- 1) USERS mẫu (đổi email/pass nếu bạn muốn)
-- Pass admin123 / user123 (hash đã sinh sẵn)
INSERT INTO users (name, email, password_hash, role, created_at)
VALUES
  ('Admin Demo', 'admin@example.com', '$2y$10$5MTYoOCfxguAkDt4XOnwCeQBGZtg7v3Pm3npeoDxdyPom/9LdcQqy', 'admin', NOW()),
  ('User Demo',  'user@example.com',  '$2y$10$zUWpC7Zf7MPeeELg.cifruevjA..bE26YQcFK6UC.6AW1MxI81IfS',  'user',  NOW())
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password_hash = VALUES(password_hash),
  role = VALUES(role);

-- 2) CATEGORIES
INSERT IGNORE INTO categories (name)
VALUES
  ('Sneakers'),
  ('Sandals'),
  ('Boots'),
  ('Chạy bộ & gym'),
  ('Công sở'),
  ('Streetwear');

-- 3a) Nếu bạn đã import bản seed cũ (mô tả ngắn), cập nhật lại mô tả chi tiết
UPDATE products SET description = 'Đôi sneaker classic hướng tới phong cách tối giản nhưng vẫn đủ nổi bật để khoác lên mọi outfit trong tuần. Phần upper kết hợp canvas thoáng khí với các điểm nhấn da tổng hợp cao cấp, dễ lau chùi và bền màu sau nhiều lần giặt nhẹ. Đế giữa làm từ EVA đúc một khối giúp giảm trọng lượng tổng thể và hấp thụ lực khi bạn di chuyển liên tục trên hè phố hay trong khuôn viên trường học. Đế ngoài cao su non với họa tiết rãnh chống trơn, bám tốt trên gạch bóng lẫn nhựa đường. Form giày ôm vừa phải ở gót và thả nhẹ ở ngón; khuyên bạn giữ nguyên size thường mang. Phù hợp nhân viên văn phòng, sinh viên hay người thích phong cách clean, dễ phối với quần jean slim, chinos hoặc quần short ngắn vào cuối tuần.' WHERE name = 'Sneakers Classic' LIMIT 1;

UPDATE products SET description = 'Sneakers Pro được thiết kế cho nhịp sống năng động: từ buổi tập nhẹ tại phòng gym đến chuyến đi bộ dài trong công viên. Lớp lưới kỹ thuật ở thân giày tăng luồng gió, hạn chế cảm giác bí khi mang trong thời gian dài. Hệ thống dây buộc đa điểm giúp cổ chân được cố định ổn định, giảm trượt gót khi chạy nước rút hoặc đổi hướng đột ngột. Đệm giữa bán cứng vừa phải: không quá mềm để mất lực đẩy, cũng không quá cứng gây mỏi cẳng. Đế ngoài cao su carbon hóa tăng độ bền ma sát ở vùng tiếp xúc nhiều. Form hơi rộng nửa size ở phần mũi cho ngón chân thoải mái; nếu bàn chân hẹp có thể chọn dây buộc chặt hơn một nấc. Đây là lựa chọn an toàn cho người mới tập cardio hoặc cần một đôi sneaker đa năng cho cả tuần.' WHERE name = 'Sneakers Pro' LIMIT 1;

UPDATE products SET description = 'Tông màu trắng ngà tinh tế, dễ phối với quần tối hoặc sáng màu mà không tạo cảm giác lệch tông. Bề mặt synthetic mịn, hạn chế bám bụi đường và dễ lau bằng khăn ẩm sau mỗi lần đi mưa nhẹ. Đế trắng đồng bộ với upper tạo một khối liền mạch, phù hợp phong cách minimalist và streetwear Hàn. Form gọn, không quá cồng kềnh nên nhìn chân thon và cân đối khi chụp ảnh OOTD. Lớp đệm mỏng vừa đủ cho đi bộ hàng ngày; nếu bạn cần đứng cả ngày nên cân nhắc thêm lót giày orthotic. Sneakers White là món đầu tư hợp lý khi bạn muốn một đôi giày trung tính, lên hình đẹp và không lỗi thời theo mùa.' WHERE name = 'Sneakers White' LIMIT 1;

UPDATE products SET description = 'Sandals Comfort tập trung vào cảm giác đi êm và thư giãn cho bàn chân sau giờ làm việc hay những ngày hè oi bức. Quai đeo điều chỉnh được bằng khóa dán Velcro, dễ thao tác khi tay đang cầm đồ hoặc khi vội ra cửa. Đế PU dày vừa phải với rãnh chống trơn, phân tán áp lực ở gót và lòng bàn chân khi đi trên gạch ẩm. Phần tiếp xúc da chân được làm mềm, hạn chế phồng rộp khi mang sockless. Trọng lượng nhẹ giúp bỏ vào balo du lịch mà không chiếm nhiều diện tích. Phù hợp đi biển ngắn ngày, dạo phố buổi tối hoặc mang trong nhà thay dép cứng. Không khuyến khích chạy bộ cường độ cao vì thiết kế hướng tới thư giãn chứ không phải giày thể thao.' WHERE name = 'Sandals Comfort' LIMIT 1;

UPDATE products SET description = 'Sandals Sport mang đến sự cân bằng giữa thoáng mát của sandal và độ ổn định gần giống giày sneaker. Quai dệt chắc chắn ôm sát mu bàn chân, hạn chế trượt khi bạn vận động nhanh hoặc leo bậc thang ngoài trời. Đế cao su đúc với pattern gai sâu, bám tốt trên sân pickleball, đường nhựa hoặc lối đi công viên có sỏi nhỏ. Phần đệm gót dày hơn một chút so với sandal thường để giảm sóc khi bước dài. Thoát nước nhanh sau khi dội qua vũng nông, phù hợp du lịch miền nhiệt đới. Bạn có thể mang kèm tất cổ thấp nếu muốn phong cách thể thao hơn. Lưu ý: tránh ngâm nước biển quá lâu để bảo quản độ bền quai và khóa kim loại.' WHERE name = 'Sandals Sport' LIMIT 1;

UPDATE products SET description = 'Boots Winter được may lại với phom cổ cao vừa phải, giúp che gót chân và một phần cổ chân khi bạn mặc quần dài hoặc quần ống hơi rộng. Lớp lót bên trong dùng vải nỉ mỏng giữ ấm nhẹ mà không gây bí khi vào phòng có máy sưởi. Mũi giày gia cố chống va đập nhẹ, hữu ích khi di chuyển trong bãi đỗ xe hay công trường có vật liệu rơi vãi. Đế cao su dày, rãnh sâu để tăng ma sát trên lá ướt hoặc đường phủ sương. Khóa kéo bên hông (nếu có trên phiên bản cụ thể) hoặc dây buộc truyền thống giúp bạn dễ điều chỉnh độ chặt theo tất dày mỏng. Phù hợp mùa se lạnh miền Bắc, đi làm hoặc đi chơi tối. Không phải ủng tuyết chuyên dụng nhưng đủ ấm cho nền nhiệt độ trên 10 độ C nếu kết hợp tất len.' WHERE name = 'Boots Winter' LIMIT 1;

UPDATE products SET description = 'Boots Street nhấn mạnh silhouette thô, đường chỉ nổi và màu da trầm hoặc đen bóng nhẹ tùy phiên bản, tạo điểm nhìn mạnh cho outfit streetwear và layer áo khoác dày. Thân boots làm từ da tổng hợp cao cấp, bề mặt dễ đánh bóng lại sau một thời gian sử dụng. Cổ giày có đệm bọc xốp mỏng để không cọ vào cổ chân khi bạn cử động nhiều. Đế platform vừa phải tăng chiều cao trực quan nhưng vẫn giữ trọng tâm ổn định; nên làm quen vài ngày nếu bạn chưa quen giày đế dày. Phối tốt với quần jean ống rộng, cargo hoặc váy midi và tất đen. Đây là item cá tính, phù hợp chụp lookbook, đi concert hoặc dạo khu phố đông người vào cuối tuần.' WHERE name = 'Boots Street' LIMIT 1;

UPDATE products SET description = 'Boots Basic là dòng an toàn cho người mới thử boots lần đầu: thiết kế cổ điển, ít chi tiết thừa, dễ phối với quần tây và quần jean slim. Chất liệu da tổng hợp mềm, cần vài buổi đi để form giày ôm theo bàn chân mà không gây phồng rộp nghiêm trọng. Đế cao su tự nhiên màu nâu hoặc đen đồng bộ với upper, may chắc ở mũi và gót. Trọng lượng trung bình, không nặng như boots công trường nên đi trong nhà ga sân bay cũng chấp nhận được. Giá tầm trung nhưng độ bền ổn nếu bạn xoay vòng 2–3 đôi giày trong tuần thay vì chỉ mang một đôi mỗi ngày. Một lựa chọn hợp lý cho sinh viên năm cuối, nhân viên mới đi làm hoặc người cần boots đa năng cho cả tuần mà không muốn quá nổi bật.' WHERE name = 'Boots Basic' LIMIT 1;

-- 3b) Thêm / bổ sung sản phẩm (INSERT … SELECT … WHERE NOT EXISTS: chạy lại không nhân đôi cùng tên)
INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Air Stream Runner 2.0',
'Mẫu chạy bộ entry-level nhưng được tinh chỉnh đế và upper để bạn cảm nhận rõ từng nhịp chạy ở cường độ vừa phải. Lớp lưới kỹ thuật nhiều lỗ thoáng giúp bàn chân mát sau 30 phút treadmill; các miếng dán phản quang nhỏ ở gót hỗ trợ khi chạy sáng sớm trên đường phố có xe máy. Đế giữa pha hạt nhẹ, giảm mỏi cẳng so với giày quá cứng. Rãnh đế chia vùng linh hoạt, uốn theo bước chân khi lên dốc nhẹ. Form hơi rộng ở mũi; người chân hẹp có thể buộc dây chặt hơn một nấc. Phù hợp chạy 5K, đi bộ nhanh hoặc làm giày tập gym đa năng khi bạn không muốn mang hai đôi khác nhau trong túi.',
NULL, 2190000, 28
FROM categories c WHERE c.name = 'Chạy bộ & gym' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Air Stream Runner 2.0' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'VeloTrack Sprint Pro',
'Định hướng cho buổi interval và chạy nước rút ngắn trên track hoặc sân cỏ nhân tạo. Upper mỏng ôm chặt giảm lực cản gió; lưỡi gà và cổ đệm vừa đủ để không cọ cổ chân khi gập gối sâu. Đế carbon hóa một phần ở vùng tiếp xúc mặt đất, tăng độ nảy nhưng vẫn kiểm soát được khi hạ gót. Trọng lượng tổng thể được cắt giảm từng gam để bạn cảm giác nhẹ hơn ở những km cuối buổi. Không nên dùng cho chạy trail đá dễ vì đế tối ưu cho mặt phẳng. Khuyến nghị xoay vòng với đôi recovery mềm hơn trong tuần để cơ bắp chân phục hồi tốt hơn.',
NULL, 2890000, 16
FROM categories c WHERE c.name = 'Chạy bộ & gym' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'VeloTrack Sprint Pro' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Mono Step Leather Oxford',
'Giày buộc dây phong cách Oxford dành cho môi trường công sở và sự kiện semi-formal: đường chỉ closed-lance tinh tế, mũi tròn vừa không quá già dặn. Da tổng hợp cao cấp xử lý bề mặt chống thấm nhẹ mưa phùn; lau khô và đánh xi định kỳ để giữ độ bóng. Đế cao su mỏng, linh hoạt khi lái xe hoặc đứng họp dài; có thể thay lót nếu bạn cần arch support riêng. Màu nâu cognac và đen dễ phối với suit xám, quần tây xanh navy hoặc chinos be. Form chuẩn EU; người bàn chân rộng nên thử nửa size lớn hơn. Đây là đôi giày giúp outfit trông chỉn chu ngay cả khi bạn chỉ mặc sơ mi trắng đơn giản.',
NULL, 2490000, 20
FROM categories c WHERE c.name = 'Công sở' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Mono Step Leather Oxford' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Heritage Penny Loafer',
'Loafer không dây, tiện khi qua cửa an ninh sân bay hoặc khi bạn muốn xỏ nhanh trước giờ họp. Miếng da ngang mũi (penny keeper) tạo điểm nhấn cổ điển, phù hợp smart casual: quần tây gấu gọn, blazer không cà vạt. Đế khâu viền chắc chắn, đế ngoài cao su chống trơn văn phòng sàn đá bóng. Bên trong lót da mềm giảm ma sát gót. Cần vài buổi để da giãn nhẹ theo bàn chân; nên mang tất cổ ngắn dày vừa phải để tránh trượt gót. Không phù hợp leo núi hay trời mưa to kéo dài; lau khô ngay nếu dính nước mưa để tránh vết nước loang.',
NULL, 2290000, 18
FROM categories c WHERE c.name = 'Công sở' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Heritage Penny Loafer' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Urban Pulse Max Cushion',
'Sneaker đế dày dòng lifestyle: đệm siêu mềm cho cảm giác lưỡng như đi trên thảm khi bạn đứng xếp hàng hoặc đi shopping cuối tuần. Upper da lộn pha lưới tạo chiều sâu màu sắc dưới ánh nắng. Đế platform tăng chiều cao trực quan 2–3 cm nhưng vẫn giữ trọng tâm thấp để tránh lật cổ chân khi bước nhanh. Dây buộc dày, mắt xỏ kim loại chịu lực tốt. Phối với quần ống rộng che bớt đế để tỷ lệ cơ thể cân hơn. Tránh ngâm nước vì da lộn cần bàn chải chuyên dụng để làm sạch. Một lựa chọn nổi bật cho streetwear và ảnh lookbook.',
NULL, 2690000, 22
FROM categories c WHERE c.name = 'Streetwear' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Urban Pulse Max Cushion' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Grit Canvas High-Top',
'High-top canvas lấy cảm hứng từ giày trượt và văn hóa đường phố: cổ cao ôm mắt cá chân, hỗ trợ nhẹ khi đi xe máy scooter trong phố. Vải canvas dày, may đôi đường chỉ ở vùng mũi chịu ma sát. Đế cao su waffle cổ điển bám tốt trên ván hoặc sàn gỗ. Bên trong có lót mỏng, có thể tháo rời để giặt riêng. Phù hợp quần jean ống thẳng, quần túi hộp hoặc váy ngắn và tất cao tạo layer. Form hơi dài ở mũi; khuyên thử giày buổi chiều khi chân hơi sưng để chọn size chính xác. Không dùng cho chạy bộ cường độ cao vì đế không tối ưu cho shock dọc.',
NULL, 1590000, 35
FROM categories c WHERE c.name = 'Streetwear' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Grit Canvas High-Top' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Coastal Walk Pro Sandal',
'Sandal quai ngang kép điều chỉnh được, phù hợp đi biển ngắn ngày hoặc resort. Đế EVA two-tone nhẹ, rãnh thoát nước nhanh sau khi ngâm chân. Quai nylon chịu nước biển và nắng gắt tốt hơn da thật trong môi trường ẩm. Miếng đệm lòng bàn chân có họa tiền massage nhẹ khi bước. Khóa kim loại chống gỉ ở mức sử dụng thông thường; rửa phơi khô sau chuyến đi để kéo dài tuổi thọ. Phối với quần short linen, áo linen tay ngắn. Không khuyến khích leo đá trơn vì đế hướng tới thoải mái hơn là bám vách dốc.',
NULL, 890000, 42
FROM categories c WHERE c.name = 'Sandals' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Coastal Walk Pro Sandal' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Summit Grip Winter Boot',
'Boot cổ cao hơn Winter cơ bản một chút, lớp lót thermal dày hơn cho những ngày gió lạnh. Khóa kéo bên kết hợp dây buộc trang trí giúp tháo mặc nhanh mà vẫn giữ phong cách rugged. Đế gai sâu hơn cho đường lá ướt hoặc cỏ sương. Mũi gia cố chống va đập; cổ đệm xốp không cọ vào ống quần jeans. Phù hợp trekking nhẹ, cắm trại cuối tuần hoặc đi làm khu công nghiệp lộng gió. Tránh ngâm nước ngập quá cổ giày; lau khô và nhét giấy báo qua đêm nếu bị ướt đẫm. Nặng hơn sneaker nên không lý tưởng mang cả ngày trong nhà văn phòng có thảm dày.',
NULL, 2790000, 11
FROM categories c WHERE c.name = 'Boots' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Summit Grip Winter Boot' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Aero Mesh Light Runner',
'Giày chạy siêu nhẹ dưới 250g mỗi chiếc (size trung bình), upper monofilament thoáng tối đa. Đế giữa có groove xoắn giúp lực chuyển mượt từ gót sang mũi. Phù hợp tempo run ngắn hoặc giảm chấn trong buổi HIIT có nhiều bước nhảy. Không có miếng chống xoắn cứng như giày marathon đỉnh cao nên hạn chế cự ly quá dài nếu bạn chưa quen. Form ôm; người bàn chân rộng nên cân nhắc bản wide nếu có. Màu pastel dễ phối đồ tập nữ; nam có thể chọn tông đen xám.',
NULL, 1990000, 24
FROM categories c WHERE c.name = 'Chạy bộ & gym' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Aero Mesh Light Runner' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Metro Slip Knit Office',
'Slip-on dệt kim co giãn, không dây, tiện cho văn phòng smart casual nơi bạn phải tháo giày qua cửa nhiều lần. Upper ôm như tất dày nhưng vẫn giữ form nhờ sợi polyester tái chế pha spandex. Đế PU chống trơn sàn đá bóng, đệm vừa phải cho đi bộ nội bộ công ty. Giặt tay nhẹ hoặc khăn ẩm; không cho vào máy giặt nóng để tránh co sợi. Phối với quần âu không xếp ly, áo polo. Không dùng cho công trường hoặc nơi có vật sắc nhọn vì upper mềm.',
NULL, 1390000, 30
FROM categories c WHERE c.name = 'Công sở' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Metro Slip Knit Office' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Neon Lane Retro Sneaker',
'Phối màu retro 90s: neon chạm nhẹ trên nền trắng xám, dễ nổi bật trong ảnh street nhưng không quá chói khi mang ban ngày. Đế chunky nhiều tầng tạo chiều cao, đệm EVA dày cho cảm giác êm khi đi phố dài. Lưỡi gà đệm cổ chân; lót trong có thể tháo. Phù hợp fan phong cách Y2K, phối váy ngắn và tất trắng hoặc quần suông. Tránh để nắng gắt làm bạc màu neon; cất tủ khô ráo.',
NULL, 1890000, 26
FROM categories c WHERE c.name = 'Streetwear' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Neon Lane Retro Sneaker' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Breeze Open Toe Comfort',
'Sandal xỏ ngón với quai mềm bọc da tổng hợp, giảm cọ kẽ ngón khi đi xa. Đế latex nhẹ, lõm nhẹ ở lòng bàn chân theo ergonomics cơ bản. Thoát nước nhanh, phù hợp đi bể bơi khách sạn hoặc spa. Không leo núi; không chạy bộ. Phối quần short, váy maxi mỏng. Lau khô sau khi dùng để tránh mùi ẩm.',
NULL, 450000, 55
FROM categories c WHERE c.name = 'Sandals' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Breeze Open Toe Comfort' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Courtline Low Sneaker',
'Sneaker court cổ thấp: mũi may chắc, viền chỉ nổi, phù hợp sân pickleball nhẹ hoặc đi học. Upper da tổng hợp mịn, lau bằng khăn ẩm. Đế cao su non bám sân trong nhà và sân pickleball ngoài trời. Form vừa chân châu Á; size chuẩn. Phối đồ học sinh, sinh viên hoặc smart casual cuối tuần.',
NULL, 1190000, 40
FROM categories c WHERE c.name = 'Sneakers' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Courtline Low Sneaker' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Ridge Hiker Mid Boot',
'Boot cổ giữa cho địa hình đồi nhẹ và đường mòn: đế Vibram-style (tổng hợp) gai sâu, chống trơn lá ướt. Mũi rubber bump chống va đá. Khóa dây đi kèm hook kim loại để siết cổ chân khi xuống dốc. Trọng lượng nặng hơn sneaker; cần làm quen ở những buổi đi ngắn trước khi trekking dài. Phù hợp balo xanh lá, quần convertible. Không mang làm giày công sở trừ khi công ty bạn rất casual.',
NULL, 3190000, 10
FROM categories c WHERE c.name = 'Boots' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Ridge Hiker Mid Boot' LIMIT 1);

-- 3c) Sản phẩm gốc (chỉ thêm nếu chưa có — lần đầu import DB trống)
INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Sneakers Classic',
'Đôi sneaker classic hướng tới phong cách tối giản nhưng vẫn đủ nổi bật để khoác lên mọi outfit trong tuần. Phần upper kết hợp canvas thoáng khí với các điểm nhấn da tổng hợp cao cấp, dễ lau chùi và bền màu sau nhiều lần giặt nhẹ. Đế giữa làm từ EVA đúc một khối giúp giảm trọng lượng tổng thể và hấp thụ lực khi bạn di chuyển liên tục trên hè phố hay trong khuôn viên trường học. Đế ngoài cao su non với họa tiết rãnh chống trơn, bám tốt trên gạch bóng lẫn nhựa đường. Form giày ôm vừa phải ở gót và thả nhẹ ở ngón; khuyên bạn giữ nguyên size thường mang. Phù hợp nhân viên văn phòng, sinh viên hay người thích phong cách clean, dễ phối với quần jean slim, chinos hoặc quần short ngắn vào cuối tuần.',
NULL, 1290000, 25
FROM categories c WHERE c.name = 'Sneakers' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Sneakers Classic' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Sneakers Pro',
'Sneakers Pro được thiết kế cho nhịp sống năng động: từ buổi tập nhẹ tại phòng gym đến chuyến đi bộ dài trong công viên. Lớp lưới kỹ thuật ở thân giày tăng luồng gió, hạn chế cảm giác bí khi mang trong thời gian dài. Hệ thống dây buộc đa điểm giúp cổ chân được cố định ổn định, giảm trượt gót khi chạy nước rút hoặc đổi hướng đột ngột. Đệm giữa bán cứng vừa phải: không quá mềm để mất lực đẩy, cũng không quá cứng gây mỏi cẳng. Đế ngoài cao su carbon hóa tăng độ bền ma sát ở vùng tiếp xúc nhiều. Form hơi rộng nửa size ở phần mũi cho ngón chân thoải mái; nếu bàn chân hẹp có thể chọn dây buộc chặt hơn một nấc. Đây là lựa chọn an toàn cho người mới tập cardio hoặc cần một đôi sneaker đa năng cho cả tuần.',
NULL, 1790000, 18
FROM categories c WHERE c.name = 'Sneakers' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Sneakers Pro' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Sneakers White',
'Tông màu trắng ngà tinh tế, dễ phối với quần tối hoặc sáng màu mà không tạo cảm giác lệch tông. Bề mặt synthetic mịn, hạn chế bám bụi đường và dễ lau bằng khăn ẩm sau mỗi lần đi mưa nhẹ. Đế trắng đồng bộ với upper tạo một khối liền mạch, phù hợp phong cách minimalist và streetwear Hàn. Form gọn, không quá cồng kềnh nên nhìn chân thon và cân đối khi chụp ảnh OOTD. Lớp đệm mỏng vừa đủ cho đi bộ hàng ngày; nếu bạn cần đứng cả ngày nên cân nhắc thêm lót giày orthotic. Sneakers White là món đầu tư hợp lý khi bạn muốn một đôi giày trung tính, lên hình đẹp và không lỗi thời theo mùa.',
NULL, 990000, 30
FROM categories c WHERE c.name = 'Sneakers' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Sneakers White' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Sandals Comfort',
'Sandals Comfort tập trung vào cảm giác đi êm và thư giãn cho bàn chân sau giờ làm việc hay những ngày hè oi bức. Quai đeo điều chỉnh được bằng khóa dán Velcro, dễ thao tác khi tay đang cầm đồ hoặc khi vội ra cửa. Đế PU dày vừa phải với rãnh chống trơn, phân tán áp lực ở gót và lòng bàn chân khi đi trên gạch ẩm. Phần tiếp xúc da chân được làm mềm, hạn chế phồng rộp khi mang sockless. Trọng lượng nhẹ giúp bỏ vào balo du lịch mà không chiếm nhiều diện tích. Phù hợp đi biển ngắn ngày, dạo phố buổi tối hoặc mang trong nhà thay dép cứng. Không khuyến khích chạy bộ cường độ cao vì thiết kế hướng tới thư giãn chứ không phải giày thể thao.',
NULL, 590000, 40
FROM categories c WHERE c.name = 'Sandals' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Sandals Comfort' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Sandals Sport',
'Sandals Sport mang đến sự cân bằng giữa thoáng mát của sandal và độ ổn định gần giống giày sneaker. Quai dệt chắc chắn ôm sát mu bàn chân, hạn chế trượt khi bạn vận động nhanh hoặc leo bậc thang ngoài trời. Đế cao su đúc với pattern gai sâu, bám tốt trên sân pickleball, đường nhựa hoặc lối đi công viên có sỏi nhỏ. Phần đệm gót dày hơn một chút so với sandal thường để giảm sóc khi bước dài. Thoát nước nhanh sau khi dội qua vũng nông, phù hợp du lịch miền nhiệt đới. Bạn có thể mang kèm tất cổ thấp nếu muốn phong cách thể thao hơn. Lưu ý: tránh ngâm nước biển quá lâu để bảo quản độ bền quai và khóa kim loại.',
NULL, 790000, 22
FROM categories c WHERE c.name = 'Sandals' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Sandals Sport' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Boots Winter',
'Boots Winter được may lại với phom cổ cao vừa phải, giúp che gót chân và một phần cổ chân khi bạn mặc quần dài hoặc quần ống hơi rộng. Lớp lót bên trong dùng vải nỉ mỏng giữ ấm nhẹ mà không gây bí khi vào phòng có máy sưởi. Mũi giày gia cố chống va đập nhẹ, hữu ích khi di chuyển trong bãi đỗ xe hay công trường có vật liệu rơi vãi. Đế cao su dày, rãnh sâu để tăng ma sát trên lá ướt hoặc đường phủ sương. Khóa kéo bên hông (nếu có trên phiên bản cụ thể) hoặc dây buộc truyền thống giúp bạn dễ điều chỉnh độ chặt theo tất dày mỏng. Phù hợp mùa se lạnh miền Bắc, đi làm hoặc đi chơi tối. Không phải ủng tuyết chuyên dụng nhưng đủ ấm cho nền nhiệt độ trên 10 độ C nếu kết hợp tất len.',
NULL, 2190000, 12
FROM categories c WHERE c.name = 'Boots' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Boots Winter' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Boots Street',
'Boots Street nhấn mạnh silhouette thô, đường chỉ nổi và màu da trầm hoặc đen bóng nhẹ tùy phiên bản, tạo điểm nhìn mạnh cho outfit streetwear và layer áo khoác dày. Thân boots làm từ da tổng hợp cao cấp, bề mặt dễ đánh bóng lại sau một thời gian sử dụng. Cổ giày có đệm bọc xốp mỏng để không cọ vào cổ chân khi bạn cử động nhiều. Đế platform vừa phải tăng chiều cao trực quan nhưng vẫn giữ trọng tâm ổn định; nên làm quen vài ngày nếu bạn chưa quen giày đế dày. Phối tốt với quần jean ống rộng, cargo hoặc váy midi và tất đen. Đây là item cá tính, phù hợp chụp lookbook, đi concert hoặc dạo khu phố đông người vào cuối tuần.',
NULL, 2590000, 9
FROM categories c WHERE c.name = 'Boots' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Boots Street' LIMIT 1);

INSERT INTO products (category_id, name, description, image_path, price, stock_qty)
SELECT c.id, 'Boots Basic',
'Boots Basic là dòng an toàn cho người mới thử boots lần đầu: thiết kế cổ điển, ít chi tiết thừa, dễ phối với quần tây và quần jean slim. Chất liệu da tổng hợp mềm, cần vài buổi đi để form giày ôm theo bàn chân mà không gây phồng rộp nghiêm trọng. Đế cao su tự nhiên màu nâu hoặc đen đồng bộ với upper, may chắc ở mũi và gót. Trọng lượng trung bình, không nặng như boots công trường nên đi trong nhà ga sân bay cũng chấp nhận được. Giá tầm trung nhưng độ bền ổn nếu bạn xoay vòng 2–3 đôi giày trong tuần thay vì chỉ mang một đôi mỗi ngày. Một lựa chọn hợp lý cho sinh viên năm cuối, nhân viên mới đi làm hoặc người cần boots đa năng cho cả tuần mà không muốn quá nổi bật.',
NULL, 1490000, 15
FROM categories c WHERE c.name = 'Boots' LIMIT 1
AND NOT EXISTS (SELECT 1 FROM products p WHERE p.name = 'Boots Basic' LIMIT 1);

-- 4) ORDERS mẫu (thêm đơn mới theo tên sản phẩm — có thể trùng nếu import lặp; có thể xóa tay các đơn demo cũ)
SET @demo_user_id := (SELECT id FROM users WHERE email = 'user@example.com' LIMIT 1);

INSERT INTO orders (user_id, total_amount, status, created_at)
SELECT @demo_user_id, 2450000, 'paid', '2026-04-01 10:30:00'
WHERE @demo_user_id IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM orders WHERE user_id = @demo_user_id AND total_amount = 2450000 AND created_at = '2026-04-01 10:30:00' LIMIT 1);
SET @demo_order_id_1 := (SELECT id FROM orders WHERE user_id = @demo_user_id AND total_amount = 2450000 AND created_at = '2026-04-01 10:30:00' ORDER BY id DESC LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_1, p.id, 1, 1290000 FROM products p WHERE p.name = 'Sneakers Classic' LIMIT 1
AND @demo_order_id_1 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_1 AND oi.product_id = p.id LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_1, p.id, 2, 590000 FROM products p WHERE p.name = 'Sandals Comfort' LIMIT 1
AND @demo_order_id_1 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_1 AND oi.product_id = p.id LIMIT 1);

INSERT INTO orders (user_id, total_amount, status, created_at)
SELECT @demo_user_id, 3590000, 'paid', '2026-04-02 15:05:00'
WHERE @demo_user_id IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM orders WHERE user_id = @demo_user_id AND total_amount = 3590000 AND created_at = '2026-04-02 15:05:00' LIMIT 1);
SET @demo_order_id_2 := (SELECT id FROM orders WHERE user_id = @demo_user_id AND total_amount = 3590000 AND created_at = '2026-04-02 15:05:00' ORDER BY id DESC LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_2, p.id, 1, 1490000 FROM products p WHERE p.name = 'Boots Basic' LIMIT 1
AND @demo_order_id_2 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_2 AND oi.product_id = p.id LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_2, p.id, 1, 1790000 FROM products p WHERE p.name = 'Sneakers Pro' LIMIT 1
AND @demo_order_id_2 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_2 AND oi.product_id = p.id LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_2, p.id, 1, 790000 FROM products p WHERE p.name = 'Sandals Sport' LIMIT 1
AND @demo_order_id_2 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_2 AND oi.product_id = p.id LIMIT 1);

INSERT INTO orders (user_id, total_amount, status, created_at)
SELECT @demo_user_id, 5180000, 'pending', '2026-04-08 09:15:00'
WHERE @demo_user_id IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM orders WHERE user_id = @demo_user_id AND total_amount = 5180000 AND created_at = '2026-04-08 09:15:00' LIMIT 1);
SET @demo_order_id_3 := (SELECT id FROM orders WHERE user_id = @demo_user_id AND total_amount = 5180000 AND created_at = '2026-04-08 09:15:00' ORDER BY id DESC LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_3, p.id, 1, 2890000 FROM products p WHERE p.name = 'VeloTrack Sprint Pro' LIMIT 1
AND @demo_order_id_3 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_3 AND oi.product_id = p.id LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_3, p.id, 1, 2290000 FROM products p WHERE p.name = 'Heritage Penny Loafer' LIMIT 1
AND @demo_order_id_3 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_3 AND oi.product_id = p.id LIMIT 1);

INSERT INTO orders (user_id, total_amount, status, created_at)
SELECT @demo_user_id, 1890000, 'paid', '2026-04-09 18:40:00'
WHERE @demo_user_id IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM orders WHERE user_id = @demo_user_id AND total_amount = 1890000 AND created_at = '2026-04-09 18:40:00' LIMIT 1);
SET @demo_order_id_4 := (SELECT id FROM orders WHERE user_id = @demo_user_id AND total_amount = 1890000 AND created_at = '2026-04-09 18:40:00' ORDER BY id DESC LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_4, p.id, 1, 1890000 FROM products p WHERE p.name = 'Neon Lane Retro Sneaker' LIMIT 1
AND @demo_order_id_4 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_4 AND oi.product_id = p.id LIMIT 1);

INSERT INTO orders (user_id, total_amount, status, created_at)
SELECT @demo_user_id, 4080000, 'cancelled', '2026-04-05 11:00:00'
WHERE @demo_user_id IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM orders WHERE user_id = @demo_user_id AND total_amount = 4080000 AND created_at = '2026-04-05 11:00:00' LIMIT 1);
SET @demo_order_id_5 := (SELECT id FROM orders WHERE user_id = @demo_user_id AND total_amount = 4080000 AND created_at = '2026-04-05 11:00:00' ORDER BY id DESC LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_5, p.id, 1, 3190000 FROM products p WHERE p.name = 'Ridge Hiker Mid Boot' LIMIT 1
AND @demo_order_id_5 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_5 AND oi.product_id = p.id LIMIT 1);

INSERT INTO order_items (order_id, product_id, quantity, unit_price)
SELECT @demo_order_id_5, p.id, 1, 890000 FROM products p WHERE p.name = 'Coastal Walk Pro Sandal' LIMIT 1
AND @demo_order_id_5 IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM order_items oi WHERE oi.order_id = @demo_order_id_5 AND oi.product_id = p.id LIMIT 1);
