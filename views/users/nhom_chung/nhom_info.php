<div class="grid gap-6 md:grid-cols-12">
    <div class="md:col-span-7">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700 h-full">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Thông tin chung</h4>
            </div>
            
            <div class="space-y-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400 font-medium">
                        Mã nhóm <span class="text-red-600">*</span>
                    </span>
                    <?php if (isset($isEdit) && $isEdit === true): ?>
                        <div class="relative mt-1">
                            <input type="text" value="<?= $group->maNhom ?>" 
                                class="block w-full text-sm dark:bg-gray-700 dark:text-gray-300 form-input bg-gray-100 dark:bg-gray-600 cursor-not-allowed opacity-75 font-mono uppercase" 
                                disabled readonly>
                            <input type="hidden" name="maNhom" id="maNhom" value="<?= $group->maNhom ?>">
                        </div>
                        <span class="text-xs text-gray-500 mt-1"><i class="fas fa-lock mr-1"></i> Mã nhóm cố định</span>
                    <?php else: ?>
                        <input type="text" id="maNhom" name="maNhom" 
                            class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-red-400 font-mono uppercase" 
                            placeholder="VD: QUAN_LY_KHO" required maxlength="20">
                        <span class="text-xs text-red-600 hidden mt-1" id="maNhom_error"></span>
                        <span class="text-xs text-gray-500 mt-1" id="maNhom_helper"></span>
                    <?php endif; ?>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400 font-medium">
                        Tên nhóm vai trò <span class="text-red-600">*</span>
                    </span>
                    <input type="text" id="tenNhom" name="tenNhom" 
                        value="<?= (isset($isEdit) && $isEdit === true) ? $group->tenNhom : '' ?>" 
                        class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-input focus:border-red-400" 
                        placeholder="VD: Nhân viên kỹ thuật" required>
                    <span class="text-xs text-red-600 hidden mt-1" id="tenNhom_error"></span>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400 font-medium">Mô tả chức năng</span>
                    <textarea id="moTa" name="moTa" 
                        class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 form-textarea focus:border-red-400" 
                        rows="3" placeholder="Mô tả quyền hạn..."><?= (isset($isEdit) && $isEdit === true) ? $group->moTa : '' ?></textarea>
                </label>
            </div>
        </div>
    </div>

    <div class="md:col-span-5">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border border-gray-200 dark:border-gray-700 h-full">
            <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fas fa-code mr-2 text-red-600"></i> Gợi ý đặt mã nhóm
                </h4>
            </div>
            
            <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                <p class="font-medium text-gray-800 dark:text-gray-200">Mã nhóm nên viết hoa, không dấu:</p>
                
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-terminal text-red-600 mt-1 mr-2 text-xs"></i>
                        <span><strong>Mã theo vai trò:</strong> ADM (Quản trị), SUP (Giám sát), STAFF (Nhân viên).</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-terminal text-red-600 mt-1 mr-2 text-xs"></i>
                        <span><strong>Mã theo khu vực:</strong> VL (Vĩnh Long), CN (Cần Thơ).</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-terminal text-red-600 mt-1 mr-2 text-xs"></i>
                        <span><strong>Mã theo cấp độ:</strong> FULL (Toàn quyền), VIEW_ONLY (Chỉ xem).</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>