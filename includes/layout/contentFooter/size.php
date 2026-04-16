<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';

$pageTitle = 'Chọn size';
require_once __DIR__ . '/../header.php';
?>

<div class="bg-white border mt-16 rounded-lg p-5 md:p-7">
  <h1 class="text-2xl font-bold">Chọn size</h1>

  <div class="mt-4 space-y-4 text-gray-700 leading-relaxed">
    <p>
      Để chọn cho mình 1 Size giày phù hợp với mình không phải là khó, nhưng cũng không phải là dễ, vì mỗi loại giày và 1 thương hiệu sẽ có 1 Size khác nhau, vậy làm cách nào để có 1 Size giày phù hợp với mình nhất?
    </p>

    <p>Sau đây hãy cũng 1Sneaker cùng bạn đi tìm câu trả lời cho thông tin này nhé.</p>

    <h2 class="text-lg font-semibold">1. Các bước đo size chân</h2>
    <p>Đầu tiên bạn hãy chuẩn bị những dụng cụ sau đây:</p>

    <ul class="list-disc pl-5 space-y-1">
      <li>Bút chì</li>
      <li>Tờ giấy trắng phải to hơn bàn chân</li>
      <li>Và 1 cây thước đủ có thể đo</li>
    </ul>

    <p class="font-medium">Các bước tiến hành:</p>

    <p><span class="font-semibold">Bước 1:</span> Đặt một tờ giấy lên sàn nhà với một đầu chạm cạnh tường. Đứng trên tờ giấy sao cho gót chân vừa chạm nhẹ vào cạnh tường.</p>

    <p><span class="font-semibold">Bước 2:</span> Đánh dấu điểm dài nhất của ngón chân. Nếu bàn chân bạn không đều (bên dài, bên thì ngắn) hãy đo bàn chân dài hơn.</p>
    <p class="text-sm text-gray-600"><span class="font-medium">Mẹo:</span> Sẽ dễ hơn nếu bạn đứng và nhờ người khác đánh dấu.</p>

    <p><span class="font-semibold">Bước 3:</span> Dùng thước để đo khoảng cách từ gót chân đến điểm dài nhất của ngón chân. Sử dụng bảng chuyển đổi size bên dưới để quy từ centimet ra size giày của bạn.</p>

    <h2 class="text-lg font-semibold">2. Bảng chuyển đổi size giày chuẩn theo giới tính</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div class="border rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 font-semibold">Bảng chuyển đổi size giày nam</div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="text-gray-600">
              <tr class="border-t">
                <th class="text-left px-4 py-2">US</th>
                <th class="text-left px-4 py-2">EU</th>
                <th class="text-left px-4 py-2">Chiều dài bàn chân</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <tr class="border-t"><td class="px-4 py-2">7</td><td class="px-4 py-2">40</td><td class="px-4 py-2">~24.4 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">7.5</td><td class="px-4 py-2">40-41</td><td class="px-4 py-2">~24.8 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">8</td><td class="px-4 py-2">41</td><td class="px-4 py-2">~25.2 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">8.5</td><td class="px-4 py-2">41-42</td><td class="px-4 py-2">~25.7 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">9</td><td class="px-4 py-2">42</td><td class="px-4 py-2">~26 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">9.5</td><td class="px-4 py-2">42-43</td><td class="px-4 py-2">~26.5 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">10</td><td class="px-4 py-2">43</td><td class="px-4 py-2">~26.8 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">10.5</td><td class="px-4 py-2">43-44</td><td class="px-4 py-2">~27.3 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">11</td><td class="px-4 py-2">44</td><td class="px-4 py-2">~27.8 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">11.5</td><td class="px-4 py-2">44-45</td><td class="px-4 py-2">~28.3 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">12</td><td class="px-4 py-2">45</td><td class="px-4 py-2">~28.6 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">13</td><td class="px-4 py-2">46</td><td class="px-4 py-2">~29.4 cm</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="border rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 font-semibold">Bảng chuyển đổi size giày nữ</div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="text-gray-600">
              <tr class="border-t">
                <th class="text-left px-4 py-2">US</th>
                <th class="text-left px-4 py-2">EU</th>
                <th class="text-left px-4 py-2">Chiều dài bàn chân</th>
              </tr>
            </thead>
            <tbody class="text-gray-700">
              <tr class="border-t"><td class="px-4 py-2">6</td><td class="px-4 py-2">36-37</td><td class="px-4 py-2">~22.5 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">6.5</td><td class="px-4 py-2">37</td><td class="px-4 py-2">~23 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">7</td><td class="px-4 py-2">37-38</td><td class="px-4 py-2">~23.5 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">7.5</td><td class="px-4 py-2">38</td><td class="px-4 py-2">~23.8 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">8</td><td class="px-4 py-2">38-39</td><td class="px-4 py-2">~24 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">8.5</td><td class="px-4 py-2">39</td><td class="px-4 py-2">~24.6 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">9</td><td class="px-4 py-2">39-40</td><td class="px-4 py-2">~25 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">9.5</td><td class="px-4 py-2">40</td><td class="px-4 py-2">~25.4 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">10</td><td class="px-4 py-2">40-41</td><td class="px-4 py-2">~25.9 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">10.5</td><td class="px-4 py-2">41</td><td class="px-4 py-2">~26.2 cm</td></tr>
              <tr class="border-t"><td class="px-4 py-2">11</td><td class="px-4 py-2">41-42</td><td class="px-4 py-2">~26.7 cm</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>