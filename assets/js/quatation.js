class Quatation {
    urls;
    template =`<table class="table shoping-cart-table">
    <tbody>
        <tr>
            <td width="90">
                <div class="cart-product-imitation">
                </div>
            </td>
            <td class="desc">
                <h3>
                    <a href="#" class="text-navy">
                    #product_name#
                    </a>
                </h3>
                <p class="small">
                    Flow cytometry (FCM) + Tri-angle laser scatter for WBC differentiation
                    Impedance method for WBC, RBC and PLT test
                    Cyanide free colorimetry for HGB test
                    Latex-enhanced scattering immunoturbidimetry for CRP test
                </p>

                <div class="m-t-sm">

                    <a href="#" class="text-muted"><i class="fa fa-trash"></i> Remove
                        item</a>
                </div>
            </td>

            <td width="65">
                <input type="text" class="form-control" placeholder="1">
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
                    newProductHTML = newProductHTML.replace(/#product_cover#/g, product.product_cover ? product.product_cover : "/assets/images/products/no-image.jpg");
                    newProductHTML = newProductHTML.replace(/#product_name#/g, product.product_name);
                    newProductHTML = newProductHTML.replace(/#product_description#/g, product.product_description_en ? product.product_description_en : "-- Not Found --" );

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
}