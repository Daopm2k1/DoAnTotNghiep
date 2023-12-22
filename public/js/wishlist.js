var app = new Vue({
    el: '#wishlist',
    data:{
        wishlist: wishlist,
        paginate_custom:paginate_custom,
        page:page,
    },
    computed: {
        paginate_change: function () {
            // console.log(this.paginate_custom)
            return this.paginate_custom;
        },
    },
    methods: {
        cart_product_id:function(id){
            return 'cart_product_id_' + id;
        },
        cart_product_name:function(id){
            return 'cart_product_name_' + id;
        },
        cart_product_price:function(id){
            return 'cart_product_price_' + id;
        },
        cart_product_qty:function(id){
            return 'cart_product_qty_' + id;
        },
        linkDetail:function(id)
        {
            return location.origin + '/product/' + id + '/detail';
        },
        format_price:function(n)
        {
            return String(parseInt(n)).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        },
        get_image:function(image)
        {
            let path = '';
            if(image.length > 0)
            {
                $.each(image, function(key,value){
                    // console.log(value);
                    if(value.is_primary == 1)
                    {
                        path = value.path;
                    }
                });
            }
            // console.log(image);
            return location.origin + path;
        },
        li_item:function(id)
        {
            return 'li_' + id;
        },
        removeWishlist:function(id)
        {
            let me =  this;
            $.ajax({
                url: location.origin + '/remove-wishlist',
                method:"POST",
                data:{
                    product_id: id,
                    page:this.page,
                    _token:$('meta[name=csrf-token]').attr('content'),
                },
                success:function(res){
                    // console.log(res);
                    if(!res.error)
                    {
                        if(res.data.data.length > 0)
                        {
                            me.wishlist.data = [];
                            $.each(res.data.data,function(key,value){
                                me.wishlist.data.push(value);
                            })

                            me.paginate_custom = res.paginate;
                            me.page = res.page;
                        }
                        else
                        {
                            if(me.page == 1)
                            {
                                window.location.href = location.origin + "/wishlist";
                            }
                            else
                            {
                                window.location.href = location.origin + "/wishlist?page=" + (me.page - 1);
                            }
                        }
                    }
                    else
                    {
                        swal({
                            title: "Lỗi!",
                            text: res.message,
                            type: "error",
                            confirmButtonClass: "btn-danger btn",
                        });
                    }
                }
            });
        }
    }
})