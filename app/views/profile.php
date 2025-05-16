<?php
// Bỏ var_dump($_SESSION) trong môi trường production
// var_dump($_SESSION);

function renderProfile()
{
    // Kiểm tra người dùng đã đăng nhập chưa
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        // Lấy thông tin người dùng từ session
        $userId = $_SESSION['user_id'] ?? '';
        $userName = $_SESSION['user_name'] ?? 'Chưa cập nhật';
        $userEmail = $_SESSION['user_email'] ?? 'Chưa cập nhật';
        $userPhone = $_SESSION['user_phone'] ?? 'Chưa cập nhật';
        
        // Lấy thông tin chi tiết hơn từ database
        global $conn;
        $userAddress = 'Chưa cập nhật';
        $userJoinDate = 'Chưa cập nhật';
        
        if ($userId && isset($conn)) {
            $query = "SELECT diachi, ngaythamgia FROM khachhang WHERE makh = ? AND trangthai = 1";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    // Xử lý các giá trị NULL - hiển thị "Chưa cập nhật"
                    $userAddress = ($row['diachi'] === NULL || $row['diachi'] === '') ? 'Chưa cập nhật' : $row['diachi'];
                    
                    // Format ngày tham gia
                    if ($row['ngaythamgia'] !== NULL && $row['ngaythamgia'] !== '') {
                        $date = new DateTime($row['ngaythamgia']);
                        $userJoinDate = $date->format('d/m/Y');
                    }
                }
                $stmt->close();
            }
        }
        
        // Thiết lập script để đồng bộ dữ liệu với sessionStorage cho JavaScript
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                if (sessionStorage.getItem("loginAccount") === null) {
                    // Nếu chưa có dữ liệu trong sessionStorage, đồng bộ từ PHP session
                    const userData = {
                        user_id: "' . $userId . '",
                        user_name: "' . addslashes($userName) . '",
                        user_email: "' . addslashes($userEmail) . '",
                        user_phone: "' . addslashes($userPhone) . '",
                        diachi: "' . addslashes($userAddress) . '",
                        ngaythamgia: "' . addslashes($userJoinDate) . '"
                    };
                    sessionStorage.setItem("loginAccount", JSON.stringify(userData));
                    sessionStorage.setItem("login", "true");
                }
            });
        </script>';
    } else {
        // Giá trị mặc định nếu không đăng nhập
        $userName = 'Vui lòng đăng nhập';
        $userEmail = 'Vui lòng đăng nhập';
        $userPhone = 'Vui lòng đăng nhập';
        $userAddress = 'Vui lòng đăng nhập';
        $userJoinDate = 'Vui lòng đăng nhập';
    }
?>
     <section id="profile-content" class="root-session-content grid-col col-l-12 col-m-12 col-s-12 margin-y-12 disable">
          <div class="profile-title padding-bottom-8 padding-top-16">
               <span class="uppercase font-size-20">Thông tin cá nhân</span>
          </div>

          <div class="profile-ui-content">
               <div class="grid-cols col-l-12 col-m-12 col-s-12 no-gutter">
                    <div class="profile-container flex">
                         <!-- Phần sidebar thông tin cá nhân -->
                         <div class="profile-sidebar grid-col col-l-3 col-m-4 col-s-12">
                              <div class="profile-avatar flex justify-center margin-y-16">
                                   <img src="./assets/images/icons/web_logo/favicon.ico" alt="Avatar" class="avatar-image" />
                              </div>
                              <div class="profile-menu">
                                   <ul class="profile-menu-list">
                                        <li class="profile-menu-item active" data-tab="user-info">
                                             <i class="fa-solid fa-user"></i>
                                             <span>Thông tin tài khoản</span>
                                        </li>
                                        <li class="profile-menu-item" data-tab="edit-info">
                                             <i class="fa-solid fa-edit"></i>
                                             <span>Chỉnh sửa thông tin</span>
                                        </li>
                                        <li class="profile-menu-item" data-tab="change-password">
                                             <i class="fa-solid fa-key"></i>
                                             <span>Thay đổi mật khẩu</span>
                                        </li>
                                        
                                        
                                   </ul>
                              </div>
                         </div>

                         <!-- Phần nội dung thông tin cá nhân -->
                         <div class="profile-content grid-col col-l-9 col-m-8 col-s-12">
                              <!-- Tab thông tin tài khoản -->
                              <div class="profile-tab active" id="user-info-tab">
                                   <h3 class="margin-bottom-16">Thông tin tài khoản</h3>
                                   <div class="user-info-display">
                                        <div class="info-row">
                                             <div class="info-label">Họ và tên</div>
                                             <div class="info-value" id="display-fullname"><?php echo htmlspecialchars($userName); ?></div>
                                        </div>
                                        <div class="info-row">
                                             <div class="info-label">Số điện thoại</div>
                                             <div class="info-value" id="display-phone"><?php echo htmlspecialchars($userPhone); ?></div>
                                        </div>
                                        <div class="info-row">
                                             <div class="info-label">Email</div>
                                             <div class="info-value" id="display-email"><?php echo htmlspecialchars($userEmail); ?></div>
                                        </div>
                                        <div class="info-row">
                                             <div class="info-label">Địa chỉ</div>
                                             <div class="info-value" id="display-address"><?php echo htmlspecialchars($userAddress); ?></div>
                                        </div>
                                        <div class="info-row">
                                             <div class="info-label">Ngày tham gia</div>
                                             <div class="info-value" id="display-joindate"><?php echo htmlspecialchars($userJoinDate); ?></div>
                                        </div>
                                   </div>
                              </div>

                              <!-- Tab chỉnh sửa thông tin tài khoản -->
                              <div class="profile-tab" id="edit-info-tab">
                                   <h3 class="margin-bottom-16">Chỉnh sửa thông tin tài khoản</h3>
                                   <form id="profile-info-form" class="profile-form">
                                        <div class="form-row">
                                             <div class="form-group grid-col col-l-12 col-m-12 col-s-12">
                                                  <label for="profile-fullname">Họ tên của bạn</label>
                                                  <input type="text" id="profile-fullname" class="full-width" value="<?php echo ($userName !== 'Chưa cập nhật' && $userName !== 'Vui lòng đăng nhập') ? htmlspecialchars($userName) : ''; ?>" />
                                             </div>
                                        </div>
                                        <div class="form-row">
                                             <div class="form-group grid-col col-l-12 col-m-12 col-s-12">
                                                  <label for="profile-phone">Số điện thoại</label>
                                                  <input type="tel" id="profile-phone" class="full-width" pattern="[0-9]{10,11}" title="Số điện thoại phải có 10-11 chữ số" value="<?php echo ($userPhone !== 'Chưa cập nhật' && $userPhone !== 'Vui lòng đăng nhập') ? htmlspecialchars($userPhone) : ''; ?>" />
                                             </div>
                                        </div>
                                        <div class="form-row">
                                             <div class="form-group grid-col col-l-12 col-m-12 col-s-12">
                                                  <label for="profile-email">Email</label>
                                                  <input type="email" id="profile-email" class="full-width" value="<?php echo ($userEmail !== 'Chưa cập nhật' && $userEmail !== 'Vui lòng đăng nhập') ? htmlspecialchars($userEmail) : ''; ?>" />
                                             </div>
                                        </div>
                                        <div class="form-row">
                                             <div class="form-group grid-col col-l-12 col-m-12 col-s-12">
                                                  <label for="profile-address">Địa chỉ</label>
                                                  <input type="text" id="profile-address" class="full-width" value="<?php echo ($userAddress !== 'Chưa cập nhật' && $userAddress !== 'Vui lòng đăng nhập') ? htmlspecialchars($userAddress) : ''; ?>" />
                                             </div>
                                        </div>
                                        <div class="form-actions margin-top-16">
                                             <button type="submit" class="button full-width">CẬP NHẬT TÀI KHOẢN</button>
                                        </div>
                                   </form>
                              </div>

                              <!-- Tab đổi mật khẩu -->
                              <div class="profile-tab" id="change-password-tab">
                                   <h3 class="margin-bottom-16">Thay đổi mật khẩu</h3>
                                   <form id="change-password-form" class="profile-form">
                                        <div class="form-group margin-bottom-16">
                                             <label for="current-password">Mật khẩu cũ <span class="text-danger">*</span></label>
                                             <div class="password-input-container">
                                                  <input type="password" id="current-password" class="full-width" required />
                                                  <button type="button" class="toggle-password">
                                                       <i class="fa-solid fa-eye"></i>
                                                  </button>
                                             </div>
                                        </div>
                                        <div class="form-group margin-bottom-16">
                                             <label for="new-password">Mật khẩu mới <span class="text-danger">*</span></label>
                                             <div class="password-input-container">
                                                  <input type="password" id="new-password" class="full-width" minlength="6" required />
                                                  <button type="button" class="toggle-password">
                                                       <i class="fa-solid fa-eye"></i>
                                                  </button>
                                             </div>
                                             <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                                        </div>
                                        <div class="form-group margin-bottom-16">
                                             <label for="confirm-password">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                             <div class="password-input-container">
                                                  <input type="password" id="confirm-password" class="full-width" required />
                                                  <button type="button" class="toggle-password">
                                                       <i class="fa-solid fa-eye"></i>
                                                  </button>
                                             </div>
                                        </div>
                                        <div class="form-actions">
                                             <button type="submit" class="button full-width">CẬP NHẬT MẬT KHẨU</button>
                                        </div>
                                   </form>
                              </div>

                            
                              
                         </div>
                    </div>
               </div>
          </div>
     </section>
     
     <!-- CSS được giữ nguyên -->
     <!-- <style>
          .profile-container {
               background-color: #fff;
               border-radius: 8px;
               box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
               overflow: hidden;
          }
          
          .profile-sidebar {
               background: #f5f5f5;
               padding: 20px 0;
               border-right: 1px solid #e0e0e0;
          }
          
          .profile-avatar {
               margin: 0 auto;
          }
          
          .avatar-image {
               width: 100px;
               height: 100px;
               border-radius: 50%;
               object-fit: cover;
               border: 3px solid var(--main-color);
          }
          
          .profile-menu-list {
               list-style: none;
               padding: 0;
               margin: 0;
          }
          
          .profile-menu-item {
               padding: 12px 20px;
               cursor: pointer;
               transition: all 0.3s ease;
               display: flex;
               align-items: center;
          }
          
          .profile-menu-item:hover {
               background-color: rgba(var(--main-color-rgb), 0.1);
          }
          
          .profile-menu-item.active {
               background-color: var(--main-color);
               color: white;
          }
          
          .profile-menu-item i {
               margin-right: 10px;
               width: 20px;
               text-align: center;
          }
          
          .profile-content {
               padding: 30px;
          }
          
          .profile-tab {
               display: none;
          }
          
          .profile-tab.active {
               display: block;
          }
          
          .user-info-display {
               background-color: #f9f9f9;
               border-radius: 8px;
               padding: 20px;
          }
          
          .info-row {
               display: flex;
               margin-bottom: 15px;
               border-bottom: 1px solid #eee;
               padding-bottom: 15px;
          }
          
          .info-row:last-child {
               border-bottom: none;
               margin-bottom: 0;
               padding-bottom: 0;
          }
          
          .info-label {
               width: 150px;
               font-weight: bold;
               color: #555;
          }
          
          .info-value {
               flex: 1;
          }
          
          .profile-form .form-group {
               margin-bottom: 20px;
          }
          
          .profile-form label {
               display: block;
               margin-bottom: 8px;
               font-weight: 500;
          }
          
          .profile-form input {
               padding: 10px 15px;
               border: 1px solid #ddd;
               border-radius: 4px;
          }
          
          .password-input-container {
               position: relative;
          }
          
          .toggle-password {
               position: absolute;
               right: 10px;
               top: 50%;
               transform: translateY(-50%);
               background: none;
               border: none;
               cursor: pointer;
               color: #555;
          }
          
          .form-actions {
               margin-top: 20px;
          }
          
          .text-danger {
               color: #dc3545;
          }
          
          .text-muted {
               color: #6c757d;
               font-size: 0.85em;
          }
          
          /* Responsive adjustments */
          @media (max-width: 768px) {
               .profile-container {
                    flex-direction: column;
               }
               
               .profile-sidebar {
                    border-right: none;
                    border-bottom: 1px solid #e0e0e0;
               }
               
               .info-row {
                    flex-direction: column;
               }
               
               .info-label {
                    width: 100%;
                    margin-bottom: 5px;
               }
          }
     </style> -->
<?php
}
?>