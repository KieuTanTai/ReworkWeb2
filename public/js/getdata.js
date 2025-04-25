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

async function GetCustomers() {

     return;
}

async function GetAccounts() {

     return;
}

async function GetInvoices() {

     return;
}

async function GetOthers() {

     return;
}

export { GetProducts, GetCustomers, GetAccounts, GetInvoices, GetOthers, GetDetailPRoducts, GetColor, GetRam, GetRom };
