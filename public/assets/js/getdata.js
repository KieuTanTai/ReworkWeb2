async function GetProducts() {
     try {
          let response = await fetch("../../../app/api/productAPi.php");
          let data = await response.json();
          console.log(response); 
          console.log(data);
          return data;
     } catch (error) {
          console.error(error);
     }
}

async function GetCustomers() {

     return ;
}

async function GetAccounts() {

     return ;
}

async function GetInvoices() {

     return ;
}

async function GetOthers() {

     return ;
}

export { GetProducts, GetCustomers, GetAccounts, GetInvoices, GetOthers };
