class Home{
    getCategory(){
        var urls = window.location.pathname.split('/');
        var xmlhttp = new XMLHttpRequest(); // new HttpRequest instance 
        var theUrl = window.location.origin + "/graphql/";
        xmlhttp.open("POST", theUrl);
        xmlhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xmlhttp.onreadystatechange = function() { //Call a function when the state changes.
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var result = JSON.parse(xmlhttp.responseText);
                if (result.data) {
                    console.log(result.data);
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
                products{
                  id_product
                  product_name
                  product_brand
                  category{
                    id_category
                    category_name
                  }
                }
              }`,
            variables: {}
        };
        xmlhttp.send(JSON.stringify(query));
    }
}