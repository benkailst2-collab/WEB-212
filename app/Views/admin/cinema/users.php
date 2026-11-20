<?php
/**
 * app/Views/admin/cinema/users.php - Quản lý Người dùng & Khách hàng
 */
$pageTitle = 'Quản Lý Người Dùng - MovieGo Admin';
$activeMenu = 'users';
include __DIR__ . '/layouts/sidebar.php';

$users = $users ?? [];
$msg   = $msg ?? '';
?>

<div class="space-y-6">
  <?php if (!empty($msg)): ?>
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
      <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
      <span><?= htmlspecialchars($msg) ?></span>
    </div>
  <?php endif; ?>

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-xl sm:text-2xl font-black text-gray-900">Quản lý Tài Khoản Người Dùng (<?= count($users) ?>)</h1>
      <p class="text-xs text-gray-500 mt-0.5">Danh sách tài khoản khách hàng và quản trị viên trong hệ thống CSDL.</p>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
          <tr>
            <th class="py-3 px-4">Họ và tên</th>
            <th class="py-3 px-4">Email</th>
            <th class="py-3 px-4">Số điện thoại</th>
            <th class="py-3 px-4">Ngày đăng ký</th>
            <th class="py-3 px-4">Vai trò</th>
            <th class="py-3 px-4 text-right">Phân quyền</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($users as $u): ?>
            <tr class="hover:bg-gray-50/80 transition-colors">
              <td class="py-3.5 px-4 font-bold text-gray-900 flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[11px]">
                  <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                </div>
                <span><?= htmlspecialchars($u['full_name']) ?></span>
              </td>
              <td class="py-3.5 px-4 text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
              <td class="py-3.5 px-4 font-mono text-gray-500"><?= htmlspecialchars($u['phone'] ?: 'Chưa cập nhật') ?></td>
              <td class="py-3.5 px-4 text-gray-400"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
              <td class="py-3.5 px-4">
                <?php if ($u['role'] === 'admin'): ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Quản trị viên (Admin)</span>
                <?php else: ?>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">Khách hàng (Customer)</span>
                <?php endif; ?>
              </td>
              <td class="py-3.5 px-4 text-right">
                <form method="post" action="/admin/cinema/users" class="inline-flex items-center gap-2">
                  <input type="hidden" name="action" value="change_role">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <select name="role" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-[11px] text-gray-700 focus:outline-none focus:border-blue-600">
                    <option value="customer" <?= $u['role'] === 'customer' ? 'selected' : '' ?>>Khách hàng</option>
                    <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                  </select>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
