const USER_API = '../../../app/api/userAPI.php';
const ORDER_API = '../../../app/api/orderAPI.php';

async function GetProducts() {
     try {
          let response = await fetch("../../../app/api/productAPi.php");
          let data = await response.json();
          return data;
     } catch (error) {
          console.error(error);
     }
}

async function GetDetailPRoducts(id) {
     try {
          const response = await fetch(`../../../app/api/phienbansoAPi.php?id=${id}`);
          const data = await response.json();
          if (data.status === "success") {
               return data.data;
          } else {
               console.error("Lỗi:", data.message);
          }

     } catch (error) {
          console.error("Lỗi khi gọi API:", error);
     }
}

async function GetColor() {
     try {
          const response = await fetch(`../../../app/api/colorAPi.php`);
          const data = await response.json();
          // console.log( (await response.text()));
          if (data.status === "success") {
               return data.data;
          } else {
               console.error("Lỗi:", data.message);
          }

     } catch (error) {
          console.error("Lỗi khi gọi API:", error);
     }
}

async function GetRam() {
     try {
          const response = await fetch(`../../../app/api/ramAPI.php`);
          const data = await response.json();
          if (data.status === "success") {
               return data.data;
          } else {
               console.error("Lỗi:", data.message);
          }
     } catch (error) {
          console.error("Lỗi khi gọi API RAM:", error);
     }
}

async function GetRom() {
     try {
          const response = await fetch(`../../../app/api/romAPI.php`);
          const data = await response.json();
          if (data.status === "success") {
               return data.data;
          } else {
               console.error("Lỗi:", data.message);
          }
     } catch (error) {
          console.error("Lỗi khi gọi API ROM:", error);
     }
}


async function GetUsers() {
     try {
          const response = await fetch(`${USER_API}`);
          const data = await response.json();
          if (Array.isArray(data)) {
               // khi chưa paginate, data trả về là mảng
               return data;
          }
          if (data.status === 'success') {
               return data.data;
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi gọi API Users:', error);
     }
}

async function GetUserById(makh) {
     try {
          const res = await fetch(`${USER_API}?makh=${makh}`);
          const json = await res.json();
          if (json.status === 'success') {
               return json.data;
          } else {
               console.error('Lỗi:', json.message);
               return null;
          }
     } catch (error) {
          console.error('Lỗi khi gọi API GetUserById:', error);
     }
}


async function GetUsersPaginated(limit, offset) {
     try {
          const response = await fetch(`${USER_API}?limit=${limit}&offset=${offset}`);
          const data = await response.json();
          if (data.status === 'success') {
               return {
                    total: data.total ?? 0,
                    users: data.data
               };
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi gọi API Users Paginated:', error);
     }
}

async function CreateUser(user) {
     try {
          const response = await fetch(USER_API, {
               method: 'POST',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify(user)
          });
          const data = await response.json();
          if (data.status === 'success') {
               return data.message;
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi tạo user:', error);
     }
}

async function UpdateUser(user) {
     try {
          const response = await fetch(USER_API, {
               method: 'PUT',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify(user)
          });
          const data = await response.json();
          if (data.status === 'success') {
               return data.message;
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi cập nhật user:', error);
     }
}

async function UpdateUserStatus(makh, trangthai) {
     try {
          const response = await fetch(USER_API, {
               method: 'PATCH',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify({ makh, trangthai })
          });
          const data = await response.json();
          if (data.status === 'success') {
               return data.message;
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi cập nhật trạng thái user:', error);
     }
}

async function DeleteUser(makh) {
     try {
          const response = await fetch(USER_API, {
               method: 'DELETE',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify({ makh })
          });
          const data = await response.json();
          if (data.status === 'success') {
               return data.message;
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi xóa user:', error);
     }
}


async function GetOrders() {
     try {
          const res = await fetch(ORDER_API);
          const json = await res.json();
          if (json.status === 'success') return json.data;
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi gọi API Orders:', e);
     }
}

async function GetOrdersPaginated(limit, offset) {
     try {
          const res = await fetch(`${ORDER_API}?limit=${limit}&offset=${offset}`);
          const json = await res.json();
          if (json.status === 'success') {
               return { total: json.total, data: json.data };
          }
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi gọi API Orders Paginated:', e);
     }
}

async function GetOrderById(madonhang) {
     try {
          const res = await fetch(`${ORDER_API}?madonhang=${madonhang}`);
          const json = await res.json();
          if (json.status === 'success') {
               return json.data; // { order: {...}, items: [...] }
          }
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi gọi API OrderById:', e);
     }
}

async function CreateOrder(order) {
     try {
          const res = await fetch(ORDER_API, {
               method: 'POST',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify(order)
          });
          const json = await res.json();
          if (json.status === 'success') return json.message;
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi tạo Order:', e);
     }
}

async function UpdateOrder(order) {
     try {
          const res = await fetch(ORDER_API, {
               method: 'PUT',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify(order)
          });
          const json = await res.json();
          if (json.status === 'success') return json.message;
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi cập nhật Order:', e);
     }
}

async function UpdateOrderStatus(madonhang, trangthai) {
     try {
          const res = await fetch(ORDER_API, {
               method: 'PATCH',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify({ madonhang, trangthai })
          });
          const json = await res.json();
          if (json.status === 'success') return json.message;
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi cập nhật trạng thái Order:', e);
     }
}

async function DeleteOrder(madonhang) {
     try {
          const res = await fetch(ORDER_API, {
               method: 'DELETE',
               headers: { 'Content-Type': 'application/json' },
               body: JSON.stringify({ madonhang })
          });
          const json = await res.json();
          if (json.status === 'success') return json.message;
          console.error('Lỗi:', json.message);
     } catch (e) {
          console.error('Lỗi khi xóa Order:', e);
     }
}

async function GetOrdersByCustomer(makh) {
     try {
          const response = await fetch(`${ORDER_API}?makh=${makh}`);
          const data = await response.json();
          if (data.status === 'success') {
               return data.data;
          } else {
               console.error('Lỗi:', data.message);
          }
     } catch (error) {
          console.error('Lỗi khi gọi API OrdersByCustomer:', error);
     }
}

async function GetInvoices() {

     return;
}

async function GetOthers() {

     return;
}

export {
     GetProducts,
     GetInvoices,
     GetOthers,
     GetDetailPRoducts,
     GetColor,
     GetRam,
     GetRom,

     // Các hàm UserService bạn vừa viết
     GetUsers,
     GetUsersPaginated,
     CreateUser,
     UpdateUser,
     UpdateUserStatus,
     DeleteUser,
     GetUserById,
     GetOrders,
     GetOrdersPaginated,
     GetOrderById,
     CreateOrder,
     UpdateOrder,
     UpdateOrderStatus,
     DeleteOrder,
     GetOrdersByCustomer
};

