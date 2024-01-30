
<div class="container d-flex min-vh-100 justify-content-center align-items-center">
    <div class="card shadow-lg">
        <div class="card-header">
            <h5 style="text-align: center;">ใบเสนอราคา quotation</h5>
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="cus-name" class="form-label">
                                <h5>ชื่อบริษัทลูกค้า</h5>
                                <h6>Company name</h6>
                            </label>
                            <input type="email" class="form-control customer-name" id="cus-name" placeholder="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="add-cus" class="form-label">
                                <h5>ที่อยู่</h5>
                                <h6>Address</h6>
                            </label>
                            <textarea class="form-control customer-address" aria-label="With textarea"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="phone-cus" class="form-label">
                                <p>
                                <h5>เบอร์โทร</h5>
                                <h6>Phone</h6>
                                </p>
                            </label>
                            <input type="email" class="form-control customer-phone" id="phone-cus" placeholder="">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cus-name" class="form-label">
                        <h5>สินค้า</h5>
                        <h6>Product</h6>
                    </label>
                </div>

                <div class="mb-3">
                    <div class="table-responsive">

                    </div>

                </div>
            </blockquote>
        </div>

        <div class="card-footer">
            <button class="btn btn-success float-end send-quotation">Send</button>
            <button class="btn btn-white"><i class="fa fa-arrow-left"></i> Continue shopping</button>


        </div>
    </div>
</div>
<script src="/assets/js/quotation.js?v=<?= $version ?>"></script>
<script>
    window.addEventListener("load", function(event) {
        var quotation = new Quotation();
        quotation.getProduct();

        document.querySelector('.send-quotation').addEventListener("click", function(event) {
            quotation.postQuotation();
        });
    });
</script>