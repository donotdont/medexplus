class Product {
    urls;
    template = `<h4>#product_group#</h4>
    <h1 class="fontH">#product_name#</h1>
    <h4>#product_brand#</h4>

    <p class="fontP">#product_description#</p>
    <img src="#product_cover#">`;
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
                    document.querySelector('.product-desciption').innerHTML = '';

                    let newProductHTML = templateHTML;
                    newProductHTML = newProductHTML.replace(/#product_cover#/g, product.product_cover ? product.product_cover : "/assets/images/products/no-image.jpg");
                    newProductHTML = newProductHTML.replace(/#product_name#/g, product.product_name);
                    newProductHTML = newProductHTML.replace(/#product_description#/g, product.product_description_en ? product.product_description_en : "-- Please contact our office for further details. --" );
                    newProductHTML = newProductHTML.replace(/#product_group#/g, product.product_group );
                    newProductHTML = newProductHTML.replace(/#product_brand#/g, product.product_brand );

                    /*if (product.product_price) {
                        newProductHTML = newProductHTML.replace(/#BTNACTION#/g, `<button type="button" class="btn btn-success btn-sm">Buy Now</button>`);
                    } else {
                        newProductHTML = newProductHTML.replace(/#BTNACTION#/g, `<button type="button" class="btn btn-success btn-sm">Quotation</button>`);
                    }*/
                    document.querySelector('.product-desciption').innerHTML = newProductHTML;
                }
            }
        }

        var query = {
            query: `query getProduct($id_product: Int!){
                product(id_product: $id_product){
                  id_product
                  product_name
                  product_cover
                  product_group
                  product_brand
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