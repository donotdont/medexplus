class Quotation {
    urls;
    template = `<table class="table shoping-cart-table">
    <tbody>
        <tr>
            <td width="90">
                <div class="cart-product-imitation">
                    <img src="#product_cover#" class="img-fluid" alt="image product" />
                    <input type="hidden" class="id-product" value="#id_product#" />
                </div>
            </td>
            <td class="desc">
                <h3>
                    <a href="#" class="text-navy">
                    #product_name#
                    </a>
                </h3>
                <p class="small">
                    #product_description#
                </p>

                <div class="m-t-sm">

                    <a href="#" class="text-muted"><i class="fa fa-trash"></i> Remove
                        item</a>
                </div>
            </td>

            <td width="65">
                <input type="text" class="form-control quotation-quantity" placeholder="1" value="1">
            </td>
        </tr>
    </tbody>
</table>`;

    constructor() {
        this.urls = window.location.origin + "/graphql/";
    }
    getProduct() {
        var templateHTML = this.template;
        var xmlhttp = new XMLHttpRequest(); // new HttpRequest instance 
        xmlhttp.open("POST", this.urls);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.onreadystatechange = function () { //Call a function when the state changes.
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var result = JSON.parse(xmlhttp.responseText);
                if (result.data) {
                    console.log(result.data);
                    let product = result.data.product;
                    document.querySelector('.table-responsive').innerHTML = '';

                    let newProductHTML = templateHTML;
                    newProductHTML = newProductHTML.replace(/#id_product#/g, product.id_product);
                    newProductHTML = newProductHTML.replace(/#product_cover#/g, product.product_cover ? product.product_cover : "/assets/images/products/no-image.jpg");
                    newProductHTML = newProductHTML.replace(/#product_name#/g, product.product_name);
                    newProductHTML = newProductHTML.replace(/#product_description#/g, product.product_description_en ? product.product_description_en : "-- Please contact our office for further details. --");

                    /*if (product.product_price) {
                        newProductHTML = newProductHTML.replace(/#BTNACTION#/g, `<button type="button" class="btn btn-success btn-sm">Buy Now</button>`);
                    } else {
                        newProductHTML = newProductHTML.replace(/#BTNACTION#/g, `<button type="button" class="btn btn-success btn-sm">Quotation</button>`);
                    }*/
                    document.querySelector('.table-responsive').innerHTML = newProductHTML;
                }
            }
        }

        var query = {
            query: `query getProduct($id_product: Int!){
                product(id_product: $id_product){
                  id_product
                  product_name
                  product_cover
                  product_description_en
                  category{
                    id_category
                    category_name
                  }
                }
              }`,
            variables: {
                "id_product": window.location.pathname && window.location.pathname.split("/").length > 0 && window.location.pathname.split("/")[2] ? parseInt(window.location.pathname.split("/")[2]) : 1
            }
        };
        xmlhttp.send(JSON.stringify(query));
    }
    postQuotation() {
        var templateHTML = this.template;
        var xmlhttp = new XMLHttpRequest(); // new HttpRequest instance 
        xmlhttp.open("POST", this.urls);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.onreadystatechange = function () { //Call a function when the state changes.
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var result = JSON.parse(xmlhttp.responseText);
                if (result.data) {
                    //console.log(result.data);
                    document.querySelector('.card').outerHTML = `<div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">สำเร็จ</h4>
                    <p>ขอบคุณค่ะ เราจะติดต่อกลับ</p>
                    <hr>
                    <p class="mb-0"></p>
                  </div>`;
                }
            }
        }

        var query = {
            query: `mutation CreateQuotation($id_product:Int!,$quotation_quantity:Int!,$quotation_customer_name:String!,$quotation_customer_address:String!,$quotation_customer_phone:String!){
                create_quotation(id_product:$id_product,quotation_quantity:$quotation_quantity,quotation_customer_name:$quotation_customer_name,quotation_customer_address:$quotation_customer_address,quotation_customer_phone:$quotation_customer_phone){
                  id_quotation
                  id_product
                  quotation_quantity
                  quotation_customer_name
                  quotation_customer_address
                  quotation_customer_phone
                }
              }`,
            variables: {
                "id_product": parseInt(document.querySelector('.id-product').value),
                "quotation_quantity": parseInt(document.querySelector('.quotation-quantity').value),
                "quotation_customer_name": document.querySelector('.customer-name').value,
                "quotation_customer_address": document.querySelector('.customer-address').value,
                "quotation_customer_phone": document.querySelector('.customer-phone').value
            }
        };
        xmlhttp.send(JSON.stringify(query));
    }
}