var app = new Vue({
    el: '#reviews',
    data:{
        auth:auth,
        page:page,
        comments: comments,
        product_id:product_id,
        all:false,
    },
    // mounted() {
    //     console.log(this.comments[0]);
    // },
    methods: {
        format_date:function (created_at) {
            return moment(created_at).fromNow();
            // return  new Date(created_at).toJSON().slice(0,10).split('-').reverse().join('/');
        },
        // format_time:function (created_at) {
        //    return new Date(created_at).toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
        // },
        comment:function()
        {
            $('.error-comment').css('display','none');
            if($('#comment').val() == '')
            {
                $('.error-comment_text').css('display','block');
                return;
            }
            else
            {
                $('.error-comment_text').css('display','none');
            }

            let data = {
                comment_text : $('#comment').val(),
                product_id : this.product_id,
                _token : $('meta[name=csrf-token]').attr('content')
            }

            let me = this;

            $.ajax({
                url: location.origin + '/api/add-comment',
                method: 'POST',
                data: data,
                success:function(res)
                {
                    // console.log(res);   
                    if(!res.error)
                    {
                        if(typeof me.comments == 'undefined'){
                            me.comments = [];
                        }
                        // .push(res.data);
                        me.comments.splice(0, 0,res.data);
                        $('#comment').val('');
                    }
                    else
                    {
                        $('.error-comment_text').css('display','block');
                        return;
                    }
                }
            })   
        },
        getClassForm:function(id)
        {
            return 'form_reply_'+ id;
        },
        showReply:function(id)
        {
            // if($('.form-child').hasClass('active'))
            // {
            //     $('.form-child').removeClass('active');
            // }
            // else
            // {
                $('.form-child').removeClass('active');
                $('#form_reply_' + id).addClass('active');
                $('.error-comment').css('display','none');
            // }
        },
        getErrorName(id)
        {
            return 'error-comment_'+ id;
        },
        getNameReply(id)
        {
            return 'comment_'+ id;
        },
        replyComment:function(id)
        {
            $('.error-comment').css('display','none');

            let id_comment = '.comment_' + id;
            let error_comment =  '.error-comment_' + id;
            let form_comment = '#form_reply_' + id;

            if($(id_comment).val() == '')
            {
                $(error_comment).css('display','block');
                return;
            }
            else
            {
                $(error_comment).css('display','none');
            }

            let data = {
                comment_text : $(id_comment).val(),
                comment_id : id,
                product_id : this.product_id,
                _token : $('meta[name=csrf-token]').attr('content')
            }

            let me = this;

            $.ajax({
                url: location.origin + '/api/add-comment',
                method: 'POST',
                data: data,
                success:function(res)
                {
                    // console.log(res);   
                    if(!res.error)
                    {
                        if(me.comments.length > 0)
                        {
                            $.each(me.comments,function(key,value){
                                if(value.id == res.data.parent_id)
                                {
                                    if(typeof me.comments[key].children == 'undefined'){
                                        me.comments[key].children = [];
                                    }
                                    // me.comments[key].children.push(res.data);
                                    me.comments[key].children.splice(0, 0,res.data);
                                }
                            });
                        }
                      
                        $(id_comment).val('');
                        $(form_comment).removeClass('active');
                    }
                    else
                    {
                        $(error_comment).css('display','block');
                        return;
                    }
                }
            })   
        },
        getAvatar:function(image)
        {
            if(image == null)
            {
                return location.origin + '/images/none-user.png';
            }

            return location.origin + image;
        },
        deleteComment:function(id,comment_id = 0)
        {
            let data = {
                parent_id :  comment_id,
                comment_id : id,
                _token : $('meta[name=csrf-token]').attr('content')
            };
            let me = this;

            swal({
                title: "Xoá bình luận",
                text: "Bạn có chắc chắn muốn xoá bình luận ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Xoá",
                cancelButtonText: "Trở lại",
                closeOnConfirm: false,
                confirmButtonClass: "btn-custom",
                closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        $.ajax({
                            url: location.origin + '/api/delete-comment',
                            method: 'POST',
                            data: data,
                            success:function(res)
                            {
                                if(!res.error)
                                {
                                    if(!res.child)
                                    {
                                        let index = -1;
                                       
                                        if(me.comments.length > 0)
                                        {
                                            $.each(me.comments,function(key,value){
                                                if(value.id == res.data.id)
                                                {
                                                    index = key;
                                                }
                                            });
                                        }
            
                                        if (index > -1) {
                                            me.comments.splice(index, 1);
                                        }
                                    }
                                    else
                                    {
                                        let index = -1;
                                        let key_index = -1;
                                        if(me.comments.length > 0)
                                        {
                                            $.each(me.comments,function(key,value){
                                                if(typeof value.children != 'undefined'){
                                                    $.each(me.comments[key].children, function(k,v){
                                                        if(v.id == res.data.id)
                                                        {
                                                            index = k;
                                                            key_index  = key;
                                                        }
                                                    })
                                                }
                                            });
                                        }
            
                                        if (index > -1 && key_index > -1) {
                                            me.comments[key_index].children.splice(index, 1);
                                        }
                                    }
                                    swal.close();
                                }
                            },
                            error: function (error) {
                                if (error.status === 419) {
                                    window.location.href = location.origin + "/login"
                                } 
                            },
                        })  
                    } 
                    else
                    {
                        swal.close();
                    }
                });
        },
        showMore:function()
        {
            let me = this;

            $.ajax({
                url: location.origin + '/api/load-more',
                method: 'GET',
                data: {
                    'page':this.page,
                    'product_id':this.product_id,
                },
                success:function(res)
                {
                    // console.log(res);
                    if(!res.error)
                    {
                        me.page = res.page;
                        me.all = res.all,
                        me.comments = [];
                        
                        if(res.data.length > 0)
                        {
                            $.each(res.data,function(key,value){
                                me.comments.push(value);
                            })
                        }
                       
                    }
                },
                error: function (error) {
                    if (error.status === 419) {
                        window.location.href = location.origin + "/login"
                    } 
                },
            })  
        },
        checkPermission:function(user_id)
        {
            // console.log(user_id,this.auth);
            if(user_id == this.auth)
            {
                return true;
            }
            return false;
        }
    }
})