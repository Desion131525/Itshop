/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){

	//减少
	$(".reduce_num").click(function(){
		var amount = $(this).parent().find(".amount");
		if (parseInt($(amount).val()) <= 1){
			alert("商品数量最少为1");
		} else{
			$(amount).val(parseInt($(amount).val()) - 1);
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));

		//获取商品id
        var goods_id = $(this).closest('tr').attr('goods_id');
		//获取商品数量
		var amount = $(amount).val();
		change(goods_id,amount);

		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
	});

	//增加
	$(".add_num").click(function(){
		var amount = $(this).parent().find(".amount");
		$(amount).val(parseInt($(amount).val()) + 1);
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));

        //获取商品id
        var goods_id = $(this).closest('tr').attr('goods_id');
        //获取商品数量
        var amount = $(amount).val();
        change(goods_id,amount);


		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
	});

	//直接输入
	$(".amount").blur(function(){

		if (parseInt($(this).val()) < 1){
			alert("商品数量最少为1");
			$(this).val(1);
		}

        //获取商品id
        var goods_id = $(this).closest('tr').attr('goods_id');
        //获取商品数量
        var amount = $('.amount').val();
        change(goods_id,amount);

		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));



		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));

	});
	
	//修改购物车数量
	var change = function (goods_id,amount) {

		$.post('/index/ajax_cart?type=change',{goods_id:goods_id,amount:amount},function (data) {
			
        });
    };

	//删除商品数
	$('.del').click(function () {
		if(confirm('确定删除?'))
		{
            //获取商品id
            var goods_id = $(this).closest('tr').attr('goods_id');
            //获取商品数量
            var amount = $('.amount').val();
            var that = this;
            $.post('/index/ajax_cart?type=del',{goods_id:goods_id,amount:amount},function (data) {
                if(data == '1')
                {
                    $(that).closest('tr').remove();
                }else {
                    alert('删除失败');
                }
            });
		}

    });


    var total = 0;
    $(".col5 span").each(function(){
        total += parseFloat($(this).text());
        console.debug(total);
    });

    $("#total").text(total.toFixed(2));


});