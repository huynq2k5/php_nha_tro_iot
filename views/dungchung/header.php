<header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-blue-600 dark:text-blue-300">
        
        <!-- Left section: Mobile hamburger -->
        <button class="p-1 -ml-1 mr-5 rounded-md md:hidden focus:outline-none focus:shadow-outline-blue" 
                @click="toggleSideMenu" 
                aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>

        <!-- Center section: Search input -->
        <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl mr-6 focus-within:text-blue-500">
                <div class="absolute inset-y-0 flex items-center pl-2">
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-blue-300 focus:outline-none focus:shadow-outline-blue form-input" 
                       type="text" 
                       placeholder="Tìm kiếm chức năng..." 
                       aria-label="Search"
                       id="header-search-input" />
                <ul id="search-results" class="absolute w-full mt-2 bg-white rounded-md shadow-lg dark:bg-gray-800 hidden z-50 border border-gray-200 dark:border-gray-700">
                </ul>
            </div>
        </div>

        <!-- Right section: Icons menu -->
        <ul class="flex items-center flex-shrink-0 space-x-6">
            
            <!-- Theme toggler -->
            <li class="flex">
                <button class="rounded-md focus:outline-none focus:shadow-outline-blue" 
                        @click="toggleTheme" 
                        aria-label="Toggle color mode">
                    <!-- Dark mode icon -->
                    <template x-if="!dark">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </template>
                    <!-- Light mode icon -->
                    <template x-if="dark">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                        </svg>
                    </template>
                </button>
            </li>

            <!-- Profile menu -->
            <li class="relative">
                <?php
                $tenHienThi = $_SESSION['user_name'] ?? 'User';
                $tenMahoa = urlencode($tenHienThi);
                $anhDaiDien = $_SESSION['user_avatar'] ?? '';

                $nguonAnh = !empty($anhDaiDien) ? $anhDaiDien : "https://ui-avatars.com/api/?name={$tenMahoa}&background=dc2626&color=fff";
                ?>

                <button class="align-middle rounded-full focus:shadow-outline-blue focus:outline-none" 
                        @click="toggleProfileMenu" 
                        @keydown.escape="closeProfileMenu" 
                        aria-label="Account" 
                        aria-haspopup="true">
                    <img class="object-cover w-8 h-8 rounded-full" 
                        src="<?= htmlspecialchars($nguonAnh) ?>" 
                        alt="<?= htmlspecialchars($tenHienThi) ?>" 
                        aria-hidden="true" />
                </button>
                
                <!-- Profile dropdown -->
                <template x-if="isProfileMenuOpen">
                    <ul x-transition:leave="transition ease-in duration-150" 
                        x-transition:leave-start="opacity-100" 
                        x-transition:leave-end="opacity-0" 
                        @click.away="closeProfileMenu" 
                        @keydown.escape="closeProfileMenu" 
                        class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700" 
                        aria-label="submenu">
                        
                        <!-- Hồ sơ -->
                        <li class="flex">
                            <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="index.php?page=profile">
                                <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Hồ sơ</span>
                            </a>
                        </li>
                        
                        <!-- Đăng xuất -->
                        <li class="flex">
                            <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="index.php?page=logout">
                                <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Đăng xuất</span>
                            </a>
                        </li>
                    </ul>
                </template>
            </li>
        </ul>
    </div>
</header>
<script>
    const inputTimKiem = document.getElementById('header-search-input');
    const vungKetQua = document.getElementById('search-results');
    let indexHighlight = -1; // Theo dõi vị trí đang chọn

    // Hàm cập nhật giao diện khi di chuyển mũi tên
    function capNhatHighlight(danhSachItem) {
        danhSachItem.forEach((item, index) => {
            if (index === indexHighlight) {
                // Thêm màu nền khi được chọn (khớp với màu đỏ của Huy)
                item.classList.add('bg-blue-50', 'dark:bg-gray-700', 'text-blue-600');
                item.scrollIntoView({ block: 'nearest' }); // Tự cuộn nếu danh sách dài
            } else {
                item.classList.remove('bg-blue-50', 'dark:bg-gray-700', 'text-blue-600');
            }
        });
    }

    inputTimKiem.addEventListener('input', function(e) {
        const q = e.target.value.trim();
        indexHighlight = -1; // Reset vị trí khi gõ chữ mới
        
        if (q.length < 2) {
            vungKetQua.classList.add('hidden');
            return;
        }

        fetch(`index.php?page=api_search_features&q=${q}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    vungKetQua.innerHTML = data.map((item, index) => `
                        <li>
                            <a href="${item.url}" class="search-item block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 transition-colors duration-150">
                                <i class="fas fa-chevron-right mr-2 text-blue-500 text-xs"></i> ${item.ten}
                            </a>
                        </li>
                    `).join('');
                    vungKetQua.classList.remove('hidden');
                } else {
                    vungKetQua.classList.add('hidden');
                }
            });
    });

    // Xử lý phím mũi tên và Enter
    inputTimKiem.addEventListener('keydown', function(e) {
        const items = vungKetQua.querySelectorAll('.search-item');
        
        if (vungKetQua.classList.contains('hidden') || items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault(); // Chặn con trỏ nhảy về cuối input
            indexHighlight = (indexHighlight + 1) % items.length;
            capNhatHighlight(items);
        } 
        else if (e.key === 'ArrowUp') {
            e.preventDefault();
            indexHighlight = (indexHighlight - 1 + items.length) % items.length;
            capNhatHighlight(items);
        } 
        else if (e.key === 'Enter') {
            if (indexHighlight > -1) {
                e.preventDefault();
                // Chuyển trang đến URL của item đang chọn
                window.location.href = items[indexHighlight].getAttribute('href');
            }
        }
        else if (e.key === 'Escape') {
            vungKetQua.classList.add('hidden');
        }
    });

    // Đóng kết quả khi click ra ngoài
    document.addEventListener('click', (e) => {
        if (!inputTimKiem.contains(e.target)) vungKetQua.classList.add('hidden');
    });
</script>