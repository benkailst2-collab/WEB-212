    </main>
  </div>
</div>

<script>
  function toggleSidebar(open) {
    const sb = document.getElementById('admin-sidebar');
    const ov = document.getElementById('sidebar-overlay');
    if (open) {
      sb.classList.remove('-translate-x-full');
      ov.classList.remove('hidden');
    } else {
      sb.classList.add('-translate-x-full');
      ov.classList.add('hidden');
    }
  }

  if (window.lucide) {
    lucide.createIcons();
  }
</script>
</body>
</html>
