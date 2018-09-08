// Handle change product type in add product form

var $product_type = $('#appbundle_product_product_type');

// When product type gets selected ...
$product_type.change(function () {
    // ... retrieve the corresponding form.
    var $form = $(this).closest('form');
    // Simulate form data, but only include the selected product type value.
    var data = {};
    data[$product_type.attr('name')] = $product_type.val();
    // Submit data via AJAX to the form's action path.
    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: data,
        success: function (html) {
            // Replace current position field ...
            $('#appbundle_product_sale_amount').replaceWith(
                // ... with the returned one from the AJAX response.
                $(html).find('#appbundle_product_sale_amount')
            );


        }
    });
});


// Assign active class and initiate Cart Calculator
$(document).ready(function () {
    // get current URL path and assign 'active' class
    var pathname = window.location.pathname;
    $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');

    if (pathname == '/addToCart'){
        $('#viewCart').addClass('active');
    }

    calc();

});

//Cart Calculator
function calc() {

    var grandTotal = 0.0;

    $("table tbody tr").each(function () {

        var price = $(this).find("td").eq(1).text().substr(1);
        var quantity = $(this).find("td").eq(2).find("input").val();

        var subTotal = parseFloat(price) * quantity;
        $(this).find("td").eq(3).text('$' + subTotal);

        grandTotal += subTotal;

    });

    document.getElementById("grandTotal").innerHTML = 'Total $' + grandTotal;

}




//Remove Cart item
$('button.removeItem').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').data('id');
    $('#myModal').data('id', id).modal('show');
});


$('#deleteItem').click(function () {

    var id = $('#myModal').data('id');

    $.ajax({
        type: "POST",
        url: "/removeCartItem",
        data: {
            'cart_item_id': id
        },
        success: function (response) {
            if (response == "success") {
                $('[data-id=' + id + ']').remove();
                $('#myModal').modal('hide');
            } else if (response == "last item") {

                $('#displayCart').html('<div class="alert alert-warning">\n' +
                    '<strong>Warning!</strong> There are no items in your cart\n' +
                    '</div>\n' +
                    '<a href="/" class="btn btn-warning">\n' +
                    '<i class="fa fa-angle-left"></i> Back to shop\n' +
                    '</a>');

                $('#myModal').modal('hide');
            }
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        }
    });
});


//Remove All cart items
$('button.removeAllItems').on('click', function (e) {
    e.preventDefault();
    var id = [];

    $("table tbody tr").each(function () {
        id.push($(this).closest('tr').data('id'));
    });
    // console.log(id);
    $('#myModal2').data('id', id).modal('show');
});

$('#deleteAll').click(function () {

    var id = $('#myModal2').data('id');


    $.ajax({
        type: "POST",
        url: "/removeAllCartItems",
        data: {
            'cart_item_id': id
        },
        success: function (response) {
            if (response == "success") {

                $('#displayCart').html('<div class="alert alert-warning">\n' +
                    '<strong>Warning!</strong> There are no items in your cart\n' +
                    '</div>\n' +
                    '<a href="/" class="btn btn-warning">\n' +
                    '<i class="fa fa-angle-left"></i> Back to shop\n' +
                    '</a>');
                $('#myModal2').modal('hide');
            }
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        }
    });
});


//Add to wishlist
$('.add-to-wishlist').click(function () {

    var id = $(this).parent().parent().parent('.product').attr('id');

    var button = $(this);

    $.ajax({
        type: "POST",
        url: "/addToWishList",
        data: {
            'product_id': id
        },
        success: function (response) {
            if (response.message == "Added") {
                button.find('i').toggleClass('fa fa-heart-o fa fa-heart').css('color','red');
            }else{
                button.find('i').toggleClass('fa fa-heart fa fa-heart-o').css('color','black');
            }

            $('#wish_list_qty').text(response.wish_list);
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        }
    });
});



//Remove Cart item
$('button.removeItemWishList').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').data('id');
    $('#myModal3').data('id', id).modal('show');
});


$('#deleteWishListItem').click(function () {

    var id = $('#myModal3').data('id');

    $.ajax({
        type: "POST",
        url: "/removeWishListItem",
        data: {
            'cart_item_id': id
        },
        success: function (response) {
            if (response == "success") {
                $('[data-id=' + id + ']').remove();
                $('#myModal3').modal('hide');
            } else if (response == "last item") {

                $('#displayWishList').html('<div class="alert alert-warning">\n' +
                    '<strong>Warning!</strong> There are no items in your wish list\n' +
                    '</div>\n' +
                    '<a href="/" class="btn btn-warning">\n' +
                    '<i class="fa fa-angle-left"></i> Back to shop\n' +
                    '</a>');

                $('#myModal3').modal('hide');
            }
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        }
    });
});


//Remove All wishlist items
$('button.removeAllWishListItems').on('click', function (e) {
    e.preventDefault();
    var id = [];

    $("table tbody tr").each(function () {
        id.push($(this).closest('tr').data('id'));
    });
    // console.log(id);
    $('#myModal4').data('id', id).modal('show');
});

$('#deleteAllWishList').click(function () {

    var id = $('#myModal4').data('id');


    $.ajax({
        type: "POST",
        url: "/removeAllWishListItems",
        data: {
            'cart_item_id': id
        },
        success: function (response) {
            if (response == "success") {


                $('#displayWishList').html('<div class="alert alert-warning">\n' +
                    '<strong>Warning!</strong> There are no items in your wish list\n' +
                    '</div>\n' +
                    '<a href="/" class="btn btn-warning">\n' +
                    '<i class="fa fa-angle-left"></i> Back to shop\n' +
                    '</a>');
                $('#myModal4').modal('hide');
            }
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        }
    });
});


$('button.moveToCart').click(function (e) {

    e.preventDefault();
    var cart_item_id = $(this).closest('tr').data('id');

    $.ajax({
        type: "POST",
        url: "/moveToCart",
        data: {
            'cart_item_id': cart_item_id
        },
        success: function (response) {
            if (response == "cart") {
                window.location.href='/viewCart';
            }else if (response == "homepage"){
                window.location.href='/';
            }else{
                window.location.href='/viewWishList';
            }
        },

        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error : ' + errorThrown);
        }
    });
});


