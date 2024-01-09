class Home {
    urls;
    template = `<div class="col-md-3">
        <div class="card mb-2">
        <a class="link" href="#LINK#"><img class="img-fluid img-fluid" src="#IMG#" alt=""></a>
        <div class="card-body">
            <h5 class="card-title">#NAME#</h5>
            <p class="card-text">#GROUP#</p>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-link btn-sm"><i class="fa-solid fa-heart" style="font-size: 1.5em;color:#dddddd"></i></button>
            #BTNACTION#
        </div>
        </div>
    </div>`;
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
                    document.querySelector('.product').innerHTML = '';
                    let productHTML = '';
                    result.data.products.forEach(function (product, index) {
                        let newProductHTML = templateHTML;
                        newProductHTML = newProductHTML.replace(/#IMG#/g, product.product_cover ? product.product_cover : "/assets/images/products/no-image.jpg");
                        newProductHTML = newProductHTML.replace(/#NAME#/g, product.product_name);
                        newProductHTML = newProductHTML.replace(/#GROUP#/g, product.product_group);
                        newProductHTML = newProductHTML.replace(/#LINK#/g, "/product/" + product.id_product);
                        if(product.product_price){
                            newProductHTML = newProductHTML.replace(/#BTNACTION#/g, `<button type="button" class="btn btn-success btn-sm">Buy Now</button>`);
                        }else{
                            newProductHTML = newProductHTML.replace(/#BTNACTION#/g, `<button type="button" class="btn btn-success btn-sm">Quotation</button>`);
                        }
                        productHTML += newProductHTML
                    });
                    document.querySelector('.product').innerHTML = productHTML;
                }
            }
        }

        var query = {
            query: `query getProduct($id_category: Int){
                products(id_category:$id_category){
                  id_product
                  product_cover
                  product_name
                  product_group
                  product_brand
                  product_price
                  category{
                    id_category
                    category_name
                  }
                }
              }`,
            variables: {
                "id_category": document.querySelector('input[name="listGroupCheckableRadios"]:checked').value ? parseInt(document.querySelector('input[name="listGroupCheckableRadios"]:checked').value) : 1
            }
        };
        xmlhttp.send(JSON.stringify(query));
    }
    getCategory() {
        var xmlhttp = new XMLHttpRequest(); // new HttpRequest instance 
        xmlhttp.open("POST", this.urls);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.onreadystatechange = function () { //Call a function when the state changes.
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var result = JSON.parse(xmlhttp.responseText);
                if (result.data) {
                    //console.log(result.data);
                    document.querySelector('.list-group.list-group-checkable').querySelectorAll('label').forEach(function (item, index) {
                        // Menu
                        let categoryProductsArray = result.data.category_products;
                        let foundValue = categoryProductsArray.filter(obj => obj.category_name === item.querySelector('h6').innerHTML)
                        if (foundValue.length > 0) {
                            item.querySelector('.d-block.small.opacity-50').innerText = foundValue[0].count_item + ' item(s)';
                        }

                        // Product

                    });
                }
            }
        }

        var query = {
            query: `query{
                categories{
                  id_category
                  category_name
                }
                category_products{
                  id_category
                  category_name
                  count_item
                }
              }`,
            variables: {}
        };
        xmlhttp.send(JSON.stringify(query));
    }
}