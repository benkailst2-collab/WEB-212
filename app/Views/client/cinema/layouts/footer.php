  </main>

  <!-- Footer DARK MODE -->
  <footer class="bg-gray-900 border-t border-gray-800 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-10">
      <!-- Col 1: Logo & intro -->
      <div class="md:col-span-1">
        <a href="/cinema" class="flex items-center gap-2 text-xl font-bold mb-4">
          <i data-lucide="film" class="w-6 h-6 text-amber-400"></i>
          <span class="bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">
            MovieGo
          </span>
        </a>
        <p class="text-gray-400 text-xs leading-relaxed">
          Hệ thống rạp chiếu phim hiện đại hàng đầu Việt Nam. Trải nghiệm điện ảnh đỉnh cao với âm thanh Dolby Atmos và màn hình IMAX Laser sắc nét.
        </p>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h4 class="text-white text-sm font-semibold mb-3">Khám phá</h4>
        <ul class="space-y-2 text-xs text-gray-400">
          <li><a href="/cinema#now-showing" class="hover:text-amber-400 transition-colors">Phim đang chiếu</a></li>
          <li><a href="/cinema#coming-soon" class="hover:text-amber-400 transition-colors">Phim sắp chiếu</a></li>
          <li><a href="/cinema" class="hover:text-amber-400 transition-colors">Suất chiếu đặc biệt</a></li>
          <li><a href="/cinema" class="hover:text-amber-400 transition-colors">Khuyến mãi thành viên</a></li>
        </ul>
      </div>

      <!-- Col 3: Customer Care -->
      <div>
        <h4 class="text-white text-sm font-semibold mb-3">Hỗ trợ khách hàng</h4>
        <ul class="space-y-2 text-xs text-gray-400">
          <li><a href="#" class="hover:text-amber-400 transition-colors">Quy định đặt vé online</a></li>
          <li><a href="#" class="hover:text-amber-400 transition-colors">Chính sách bảo mật</a></li>
          <li><a href="#" class="hover:text-amber-400 transition-colors">Câu hỏi thường gặp (FAQ)</a></li>
          <li><a href="/admin/cinema" class="hover:text-amber-400 transition-colors">Cổng quản trị nội bộ</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact & Social -->
      <div>
        <h4 class="text-white text-sm font-semibold mb-3">Liên hệ MovieGo</h4>
        <ul class="space-y-2.5 text-xs text-gray-400">
          <li class="flex items-start gap-2">
            <i data-lucide="map-pin" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
            <span>271 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh</span>
          </li>
          <li class="flex items-center gap-2">
            <i data-lucide="phone" class="w-4 h-4 text-amber-400 shrink-0"></i>
            <span>Hotline: 1900 6017 (8:00 - 22:00)</span>
          </li>
          <li class="flex items-center gap-2">
            <i data-lucide="mail" class="w-4 h-4 text-amber-400 shrink-0"></i>
            <span>support@moviego.vn</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="border-t border-gray-800 text-center py-5 text-xs text-gray-500">
      © <?= date('Y') ?> MovieGo Cinema. Toàn bộ giao diện Frontend được tối ưu hóa cho Mobile & Desktop.
    </div>
  </footer>

  <!-- Initialize Lucide Icons -->
  <script>
    if (window.lucide) {
      lucide.createIcons();
    }
  </script>
</body>
</html>
